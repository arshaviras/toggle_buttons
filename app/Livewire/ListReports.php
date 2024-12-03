<?php

namespace App\Livewire;

use App\Models\AcademicTerm;
use App\Models\Lecturer;
use App\Models\Question;
use App\Models\Questionnaire;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;
use Livewire\Component;


class ListReports extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $questionnaire;
    public $chairId;
    public $academicTermId;
    public $avgVoteType;


    public Questionnaire $questionnaireDetails;
    public AcademicTerm $academicTerm;

    #[On('chairChanged')]
    public function chairChanged($chairId)
    {
        $this->chairId = (int) $chairId;
        $this->resetTable(); // Reset the table state
    }

    #[On('academicTermChanged')]
    public function academicTermChanged($academicTermId)
    {
        $this->academicTermId = (int) $academicTermId;
        $this->resetTable(); // Reset the table state
    }

    #[On('avgVoteTypeChanged')]
    public function avgVoteTypeChanged($avgVoteType)
    {
        $this->avgVoteType = $avgVoteType;
        $this->resetTable(); // Reset the table state
    }

    public function mount(): void
    {
        $this->questionnaireDetails = Questionnaire::find($this->questionnaire);
    }

    public function render()
    {
        return view('livewire.list-reports', [
            'questionnaireDetails' => $this->questionnaireDetails
        ]);
    }


    //#[Computed]
    public function table(Table $table): Table
    {
        $textColumns = [];

        $questions = Question::with(['questionnaire'])
            ->whereRelation('questionnaire', 'id', $this->questionnaire)
            ->get();

        $questionnaireType = $this->questionnaireDetails->type->value;

        if ($questionnaireType != 'chair') {
            $firstColumns[] = ImageColumn::make('photo')
                ->action(
                    Action::make('enlarged-photo')
                        ->modalWidth(MaxWidth::ExtraSmall)
                        ->modalHeading(fn($record): string => $record->full_name)
                        ->modalDescription(fn($record): string => $record->chair->name)
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                        ->modalContent(
                            fn($record) =>
                            new HtmlString('<img src="' . asset('storage/' . ($record->photo ?? 'placeholder.jpg')) . '" />')
                        )
                )
                ->toggleable()
                ->defaultImageUrl(url('/storage/placeholder.jpg'))
                ->extraHeaderAttributes([
                    'class' => 'w-8'
                ])
                ->size(50)
                ->circular();
            $firstColumns[] = TextColumn::make('full_name')
                ->label(__('Lecturer'))
                ->wrap()
                ->searchable(['first_name', 'last_name', 'father_name'])
                ->forceSearchCaseInsensitive()
                ->action(function ($record) {
                    return redirect()->to('/admin/lecturers/' . $record->id . '?activeRelationManager=2');
                })
                ->description(function ($record) {
                    return $record->chair->name;
                });
        } else {
            $firstColumns[] = TextColumn::make('chair.name')
                ->searchable(['name'])
                ->forceSearchCaseInsensitive()
                ->label(__('Chair'));
        }

        $textColumns[] = ColumnGroup::make(__('Name'), $firstColumns)
            ->alignment(Alignment::Center)
            ->wrapHeader();

        //Questions Columns
        foreach ($questions as $question) {
            $student_types = ['HQ' => 'armenian', 'OE' => 'english'];

            $columnGroups = array_map(function ($key, $student_type) use ($question, $questionnaireType) {
                return TextColumn::make($student_type . $question['id'])
                    ->label(__($key))
                    ->alignment(Alignment::Center)
                    ->placeholder('-')
                    ->badge()
                    ->state(fn($record) => ($questionnaireType === 'chair' ? $record->chair?->votes : $record->votes)
                        ->where('question_id', $question->id)
                        ->where('questionnaire_type', $questionnaireType)
                        ->where('student_group_type', $student_type)
                        ->avg('avg_vote'))
                    ->formatStateUsing(fn($state) => empty($state) ? null : round($state, 1))
                    ->color(fn($state) => $state <= 3.5 ? 'danger' : 'success');
            }, array_keys($student_types), $student_types);

            $textColumns[] = ColumnGroup::make($question['id'], $columnGroups)
                ->wrapHeader()
                ->alignment(Alignment::Center)
                ->label($question['name']);
        }

        //Total number of students
        $columnGroups = array_map(function ($key, $student_type) use ($question) {
            return TextColumn::make('total_' . $student_type . $question['id'])
                ->label(__($key))
                ->alignment(Alignment::Center)
                ->color(Color::Indigo)
                ->badge()
                ->state(fn($record) => $record->groups
                    ->where('type.value', $student_type)
                    ->sum('students_count'));
        }, array_keys($student_types), $student_types);

        $textColumns[] = ColumnGroup::make(__('Total number of students'), $columnGroups)
            ->alignment(Alignment::Center)
            ->wrapHeader();

        //Voted number of students
        $columnGroups = array_map(function ($key, $student_type) use ($question, $questionnaireType) {
            return TextColumn::make('sum_' . $student_type . $question['id'])
                ->label(__($key))
                ->alignment(Alignment::Center)
                ->badge()
                ->placeholder('-')
                ->state(fn($record) => ($questionnaireType === 'chair' ? $record->chair?->votes : $record->votes)
                    ->where('student_group_type', $student_type)
                    ->where('questionnaire_type', $questionnaireType)
                    ->value('voted_students'));
        }, array_keys($student_types), $student_types);

        $textColumns[] = ColumnGroup::make(__('Voted number of students'), $columnGroups)
            ->wrapHeader()
            ->alignment(Alignment::Center);


        //Total Votes
        $columnGroups = array_map(function ($key, $student_type) use ($question, $questionnaireType) {
            return TextColumn::make('sumVotes_' . $student_type . $question['id'])
                ->label(__($key))
                ->alignment(Alignment::Center)
                ->badge()
                ->placeholder('-')
                ->state(fn($record) => ($questionnaireType === 'chair' ? $record->chair?->votes : $record->votes)
                    ->where('questionnaire_type', $questionnaireType)
                    ->where('student_group_type', $student_type)
                    ->value('avg_vote'))
                ->formatStateUsing(fn($state) => empty($state) ? null : round($state, 1))
                ->color(fn($state) => $state <= 3.5 ? 'danger' : 'success');
        }, array_keys($student_types), $student_types);

        $textColumns[] = ColumnGroup::make(__('Total Votes'), $columnGroups)
            ->wrapHeader()
            ->alignment(Alignment::Center);


        //Grand Total Vote
        $textColumns[] = TextColumn::make('grand' . $this->questionnaire)
            ->label(__('Grand Total'))
            ->wrapHeader()
            ->alignment(Alignment::Center)
            ->badge()
            ->placeholder('-')
            ->state(function ($record) use ($questionnaireType) {
                return ($questionnaireType === 'chair' ? $record->chair?->votes : $record->votes)
                    ->where('questionnaire_type', $questionnaireType)
                    ->avg('avg_vote');
            })
            ->formatStateUsing(fn($state) => empty($state) ? null : round($state, 1))
            ->color(fn($state) => $state <= 3.5 ? 'danger' : 'success');

        //Building Tables

        $buildVotesQuery = function ($query) {
            return $query->join('students', 'votes.student_id', '=', 'students.id')
                ->join('groups', 'students.group_id', '=', 'groups.id')
                ->join('questions', 'votes.question_id', '=', 'questions.id')
                ->join('questionnaires', 'questions.questionnaire_id', '=', 'questionnaires.id')
                ->select(
                    'votable_id',
                    'question_id',
                    'questionnaires.type as questionnaire_type',
                    'academic_term_id',
                    'groups.type as student_group_type',
                    DB::raw('AVG(vote) as avg_vote'),
                    DB::raw('COUNT(votes.student_id) as voted_students')
                )
                ->groupBy('votable_id', 'question_id', 'questionnaire_type', 'academic_term_id', 'groups.type');
        };

        return $table
            ->modelLabel(__('Lecturer'))
            ->pluralModelLabel(__('Lecturers'))
            ->query(
                Lecturer::query()
                    ->with([
                        'votes' => function ($query) use ($buildVotesQuery) {
                            $buildVotesQuery($query);
                        },
                        'chair.votes' => function ($query) use ($buildVotesQuery) {
                            $buildVotesQuery($query);
                        },
                        'groups' => function ($query) {
                            $query->withCount('students');
                        },
                    ])
                    ->addSelect([
                        'total_avg_vote' => function ($query) use ($questionnaireType) {
                            $query->select(DB::raw('AVG(vote)'))
                                ->from('votes')
                                ->join('questions', 'votes.question_id', '=', 'questions.id')
                                ->join('chairs', 'votes.votable_id', '=', 'chairs.id')
                                ->whereColumn('votes.votable_id', ($questionnaireType === 'chair' ? 'chairs.id' : 'lecturers.id'))
                                ->where('questions.questionnaire_id', $this->questionnaire);
                        }
                    ])
                    ->whereRelation('groups.questionnaires', 'questionnaires.id', $this->questionnaire)
                    ->when($this->academicTermId, function ($query) {
                        $query->whereRelation('votes.academicTerm', 'id', $this->academicTermId);
                    }, function ($query) {
                        $query->whereRelation('votes.academicTerm', 'id', AcademicTerm::activeId());
                    })
                    ->when($this->chairId, function ($query) {
                        return $query->whereRelation('chair', 'id', $this->chairId);
                    })
                    ->when($this->avgVoteType === 'lower' || $this->avgVoteType === 'higher', function ($query) {
                        return $query->having('total_avg_vote', $this->avgVoteType === 'lower' ? '<=' : '>', 3.5);
                    })
                    ->when($this->avgVoteType === 'all', function ($query) {
                        return $query->withoutGlobalScope('total_avg_vote');
                    }),
            )
            ->columns($textColumns);
        //->paginated(false);
    }
}
