<?php

namespace App\Livewire;

use App\Models\AcademicTerm;
use App\Models\Chair;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Illuminate\Support\Str;

class ListFilters extends Component implements HasForms
{

    use InteractsWithForms;

    public $chair;
    public $academicTerm;
    public $avgVoteType;

    public function mount(): void
    {
        $this->form->fill();
    }

    // public function mount(): void
    // {
    //     $this->chairDetails = Chair::all();
    // }

    public function changeChair()
    {
        $this->dispatch('chairChanged', $this->chair);
    }

    public function changeAcademicTerm()
    {
        $this->dispatch('academicTermChanged', $this->academicTerm);
    }

    public function changeAvgVoteType()
    {
        $this->dispatch('avgVoteTypeChanged', $this->avgVoteType);
    }

    public function render()
    {
        return view(
            'livewire.list-filters'
            // , [
            //     'chairs' => $this->chairDetails
            // ]
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->model(Chair::class)
            ->schema([
                Actions::make([
                    Action::make('print')->alpineClickHandler('window.print()')
                ])
                    ->alignment(Alignment::Right),
                Section::make(__('Filters'))
                    ->schema([
                        Select::make('chair')
                            ->options(Chair::orderBy('name')->get()->pluck('name', 'id'))
                            ->optionsLimit(200)
                            ->searchable(['name'])
                            ->forceSearchCaseInsensitive()
                            ->getSearchResultsUsing(fn(string $search): array =>
                            Chair::whereRaw('LOWER(`name`) LIKE ? ', ['%' . Str::lower($search) . '%'])
                                ->limit(50)->pluck('name', 'id')->toArray())
                            ->native(false)
                            ->preload()
                            ->extraAttributes([
                                //'wire:model' => "chair",
                                'wire:change' => "changeChair",
                            ])
                            ->native(false)
                            ->hint(new HtmlString(Blade::render('<x-filament::loading-indicator class="h-5 w-5" wire:loading wire:target="chair" />'))),
                        Select::make('academicTerm')
                            ->label(__('Academic Term'))
                            ->options(AcademicTerm::all()->pluck('full_name', 'id'))
                            ->default(AcademicTerm::activeId())
                            ->extraAttributes([
                                'wire:model' => "academicTerm",
                                'wire:change' => "changeAcademicTerm",
                            ])
                            ->native(false)
                            ->hint(new HtmlString(Blade::render('<x-filament::loading-indicator class="h-5 w-5" wire:loading wire:target="academicTerm" />'))),
                        ToggleButtons::make('avgVoteType')
                            ->label(__('Grand Total'))
                            ->grouped()
                            ->options([
                                'all' => __('All'),
                                'lower' => '<= 3.5',
                                'higher' => '> 3.5',
                            ])
                            ->colors([
                                'all' => 'info',
                                'lower' => 'danger',
                                'higher' => 'success',
                            ])
                            ->icons([
                                'all' => 'heroicon-o-bars-4',
                                'lower' => 'heroicon-o-arrow-trending-down',
                                'higher' => 'heroicon-o-arrow-trending-up',
                            ])
                            ->default('all')
                            ->extraAttributes([
                                //'wire:model' => "academicTerm",
                                'wire:change' => "changeAvgVoteType",
                            ])
                            ->hint(new HtmlString(Blade::render('<x-filament::loading-indicator class="h-5 w-5" wire:loading wire:target="avgVoteType" />'))),
                    ])
                    ->icon('heroicon-m-funnel')
                    ->columns(3)
            ]);
    }
}
