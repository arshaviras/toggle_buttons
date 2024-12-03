<x-filament-panels::page>
    @if(!$is_voted)
        @if(!$completed)
        <form wire:submit="create">
            {{ $this->form }}
        </form>
        @else
        <x-filament::section>
            <x-slot name="heading">
                {{ __('Thank You for participating to this poll') }}
            </x-slot>

            <p class="text-center">{{ $message }}</p>
        </x-filament::section>
        @endif
    @else
        <x-filament::section>
            <x-slot name="heading">
                {{ __('You are already voted!') }}
            </x-slot>

            <p class="text-center">{!! __('Dear Student, You are already participated to this poll.<br>Double participating not allowed.') !!}</p>
        </x-filament::section>
    @endif
</x-filament-panels::page>