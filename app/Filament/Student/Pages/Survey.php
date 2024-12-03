<?php

namespace App\Filament\Student\Pages;

use App\Enums\Performance;
use App\Models\Suggestion;
use App\Models\AcademicTerm;
use App\Models\Comment;
use App\Models\Group;
use App\Models\Lecturer;
use App\Models\Questionnaire;
use App\Models\Student;
use App\Models\User;
use App\Models\Vote;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\App;

class Survey extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.student.pages.survey';

    protected ?string $heading = '';

    public ?array $data = [];

    public bool $is_voted = false;
    public bool $completed = false;
    public ?string $message = null;
    public ?string $state = null;



    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        $this->is_voted = Student::where('id', auth()->id())->first()->is_voted;

        $types = ['practice', 'lecture', 'chair'];
        $steps = [];
        $locales = [
            'armenian' => 'hy',
            'english' => 'en',
            'russian' => 'ru'
        ];


        $group_type = Group::whereRelation('students', 'students.id', auth()->id())->first()->type->value;

        App::setLocale(Arr::get($locales, $group_type, 'en'));

        $steps[] = Wizard\Step::make(__('Introduction'))
            ->schema([
                Placeholder::make('Introduction')
                    ->label(__('The educational process of department and the assessment of  lecturers by students'))
                    ->disabled()
                    ->content(new HtmlString(__('<p>Dear student, this poll is passes every semester. It gives an opportunity to assess the quality of the teaching process, as well as to make the qualitative characteristics of the lecturer.Your sincere and objective assessments and opinions will contribute to the development of the effectiveness of the educational process.</p><p><strong>“Practice”</strong> section includes the subjects studied in the previous semester, the name and surname of the professors who have taught you, indicators of assessment of their professional and pedagogical skills and competences. </p><p><strong>“Lecture” </strong> section presents names and surnames of professors conducted lectures during previous semester and qualitative indicators for their activity assessment.</p><p><strong>“Department”</strong> section includes questions about departments You have studied during previous semester.</p><p>Scores are based on a 5-point scale, where 1 is the lowest and 5 is the highest.</p><p><strong>The poll is anonymous.</strong></p>'))),
                ToggleButtons::make('Performance')
                    ->label(__('Academic Performance'))
                    ->grouped()
                    ->required()
                    ->options(Performance::class)
            ]);


        foreach ($types as $type) {

            $questionnaires = Questionnaire::with('questions')
                ->where('type', $type)
                ->whereRelation('academicTerms', 'is_active', true)
                ->whereRelation('students', 'students.id', auth()->id())->get();

            if ($type != 'chair') {
                $lecturers = Lecturer::with('chair')
                    ->whereHas('groups.students', function (Builder $query) use ($type) {
                        $query->where('lecturer_type', 'like', '%' . $type . '%')
                            ->where('students.id', auth()->id());
                    })->get();
                // ->whereRelation('groups', 'lecturer_type', 'like', '%' . $type . '%')
                // ->whereRelation('groups.students', 'students.id', auth()->id())->get();
            } else {
                $lecturers = Lecturer::with('chair')
                    ->whereRelation('groups.students', 'students.id', auth()->id())->get();
            }


            foreach ($questionnaires as $questionnaire) {
                $sections = [];

                foreach ($lecturers as $lecturer) {
                    $toggles = [];

                    foreach ($questionnaire['questions']->sortBy('sort') as $question) {
                        if (in_array($group_type, $question['group_type'])) {
                            $toggles[] = ToggleButtons::make(($type != 'chair' ? 'lec-' . $lecturer['id'] : 'ch-' . $lecturer->chair['id']) . '_q-' . $question['id'])
                                ->label($question['name'])
                                ->grouped()
                                ->required($type != 'chair' ? fn(Get $get): bool => !$get('other-' . $type . '-' . $lecturer['id']) : true)
                                ->disabled($type != 'chair' ? fn(Get $get): bool => $get('other-' . $type . '-' . $lecturer['id']) ?? false : false)
                                ->options([
                                    '1' => '1',
                                    '2' => '2',
                                    '3' => '3',
                                    '4' => '4',
                                    '5' => '5',
                                ]);
                        };
                    }
                    $toggles[] = Textarea::make($type != 'chair' ? 'lecComment-' . $type . '-' . $lecturer['id'] : 'chComment-' . $lecturer->chair['id'])
                        ->label(__('Comment'))
                        //->hidden($type != 'chair')
                        ->disabled(fn(Get $get): bool => $get('other-' . $type . '-' . $lecturer['id']) ?? false)
                        ->placeholder(__('Please comment for this ' . ($type != 'chair' ? 'lecturer in ' . $type : 'chair')))
                        ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('Some more information...'))
                        ->autosize()
                        ->minLength(2)
                        ->maxLength(2500);
                    if ($type != 'chair') {
                        $sections[] = Section::make($lecturer['last_name'] . ' ' . $lecturer['first_name'] . ' ' . $lecturer['father_name'])
                            ->description($lecturer->chair['name'])
                            ->icon('/storage/' . $lecturer['photo'])
                            ->iconSize('h-16 w-16 max-w-none object-cover object-center rounded-full ring-white dark:ring-gray-900')
                            ->id($lecturer['id'])
                            ->columnSpan(2)
                            ->schema([
                                Toggle::make('other-' . $type . '-' . $lecturer['id'])
                                    ->label(__('Other Lecturer'))
                                    ->onIcon('heroicon-m-bolt')
                                    ->offIcon('heroicon-m-user')
                                    ->dehydrated(false)
                                    ->hint(new HtmlString(Blade::render('<x-filament::loading-indicator class="h-5 w-5" wire:loading wire:target="data.' . 'other-' . $type . '-' . $lecturer['id'] . '" />')))
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function (Set $set) use ($lecturer, $questionnaire, $group_type, $type) {
                                        foreach ($questionnaire['questions'] as $question) {
                                            if (in_array($group_type, $question['group_type'])) {
                                                $set('lec-' . $lecturer['id'] . '_q-' . $question['id'], null);
                                                $this->resetErrorBag();
                                            }
                                        }
                                        $set($type != 'chair' ? 'lecComment-' . $type . '-' . $lecturer['id'] : 'chComment-' . $lecturer->chair['id'], null);
                                    }),
                                Section::make()
                                    ->schema(
                                        $toggles
                                    )
                            ]);
                    } else {
                        $sections[] = Section::make($lecturer->chair['name'])
                            ->id($lecturer->chair['id'])
                            ->columnSpan(2)
                            ->schema(
                                $toggles
                            );
                    }
                }

                $steps[] = Wizard\Step::make(__(ucfirst($type)))
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'sm' => 1,
                            'md' => 2,
                            'lg' => 3,
                            'xl' => 4,
                            '2xl' => 6,
                        ])
                            ->schema(
                                $sections
                            )
                    ]);
            }
        }

        $steps[] = Wizard\Step::make(__('Suggestion'))
            ->schema([
                Textarea::make('Suggestion')
                    ->label(__('Suggestion'))
                    ->placeholder(__('Please describe your overall suggestions here...'))
                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('Some more information...'))
                    ->autosize()
                    ->autofocus()
                    ->minLength(2)
                    ->maxLength(2500)
            ]);

        //dd($steps);
        return $form
            ->schema([
                Wizard::make($steps)
                    ->submitAction(new HtmlString(Blade::render(
                        <<<BLADE
    <x-filament::button
        form="create"
        type="submit"
        color="success"
        size="md"
    >
        {{ __('Submit') }}
    </x-filament::button>
BLADE
                    ))),
            ])
            ->statePath('data');
    }


    public function create(): void
    {
        $active_academic_term_id = AcademicTerm::where('is_active', true)->first()->id;

        $votables = ['lec' => 'Lecturer', 'ch' => 'Chair'];

        foreach ($votables as $prefix => $votable_type) {
            foreach ($this->data as $key => $value) {
                $exp_key = explode('-', $key);
                if ($exp_key[0] == $prefix) {
                    $vote = $value;
                    $votable_id = Str::between($key, $prefix . '-', '_q-');
                    $question_id = Str::after($key, '_q-');

                    Vote::create([
                        'student_id' => auth()->id(),
                        'academic_term_id' => $active_academic_term_id,
                        'question_id' => $question_id,
                        'votable_type' => 'App\Models\\' . $votable_type,
                        'votable_id' => $votable_id,
                        'vote' => $vote,
                    ]);
                }
            }
        }

        $commentables = ['lecComment' => 'Lecturer', 'chComment' => 'Chair'];

        foreach ($commentables as $prefix => $commentable_type) {
            foreach ($this->data as $key => $value) {
                $exp_key = explode('-', $key);
                if ($exp_key[0] == $prefix) {
                    $comment = $value;
                    $commentable_id = Str::afterLast($key, '-');
                    $lecturer_type = $prefix != 'chComment' ? Str::between($key, '-', '-') : null;

                    if (!is_null($comment)) {
                        Comment::create([
                            'student_id' => auth()->id(),
                            'academic_term_id' => $active_academic_term_id,
                            'lecturer_type' => $lecturer_type,
                            'commentable_type' => 'App\Models\\' . $commentable_type,
                            'commentable_id' => $commentable_id,
                            'comment' => $comment,
                        ]);
                    }
                }
            }
        }
        if (!is_null($this->data['Suggestion'])) {
            Suggestion::updateOrCreate([
                'student_id' => auth()->id(),
                'academic_term_id' => $active_academic_term_id,
                'performance' => $this->data['Performance'],
                'suggestion' => $this->data['Suggestion'],
            ]);
        }

        Student::where('id', auth()->id())
            ->update(['is_voted' => true]);

        $group = Group::whereRelation('students', 'students.id', auth()->id())->first()->name;
        $username = auth()->user()->username;

        Notification::make()
            ->title(__('New student has voted!'))
            ->info()
            ->body(__('Student with username :username from group :group has voted!', ['username' => $username, 'group' => $group]))
            ->sendToDatabase(User::find(2));

        $this->completed = true;

        if ($this->completed = true) {
            $this->state = 'success';
            $this->message = __('Your votes are sucssessfully submited.');
        } else {
            $this->state = 'failure';
            $this->message = __('Your votes are not submited. Please contact to DUA Team');
        }
    }
}
