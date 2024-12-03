<x-filament-panels::page>
    <livewire:list-filters />
    @foreach($questionnaires as $questionnaire)
    <livewire:list-reports :questionnaire="$questionnaire->id" :key="$questionnaire->id" />
    @endforeach
</x-filament-panels::page>