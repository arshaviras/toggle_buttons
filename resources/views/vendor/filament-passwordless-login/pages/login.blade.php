<x-filament-panels::page.simple>
    @if(! $this->sent && $this->userexist === null)
        <x-filament-panels::form wire:submit="authenticate">
            {{ $this->form }}

            <x-filament-panels::form.actions
                :actions="$this->getCachedFormActions()"
                :full-width="$this->hasFullWidthFormActions()"
            />
        </x-filament-panels::form>
    @elseif($this->userexist === false)
        <x-filament-panels::form wire:submit="authenticate">
            {{ $this->form }}

            <x-filament-panels::form.actions
                :actions="$this->getCachedFormActions()"
                :full-width="$this->hasFullWidthFormActions()"
            />
        </x-filament-panels::form>
        <p class="text-center font-medium" style="color: #f87171">
            This email was not found! Try valid email.
        </p>
    @else
        <p class="text-center font-medium" style="color: #16a34a">
            You will receive an email with a link to login shortly.
        </p>
    @endif
</x-filament-panels::page.simple>
