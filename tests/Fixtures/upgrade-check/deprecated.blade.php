<x-square-ui::card title="Klant">…</x-square-ui::card>
<x-square-ui::card-header-that-does-not-exist />
<x-square-ui.inputs::datepicker wire:model="date" />
<x-square-ui::inputs.time-picker wire:model="time" />
<x-square-ui.inputs.pin wire:model="pin" />
<livewire:square-ui::data-table :results="$rows" />
{{-- <x-square-ui::dropzone /> --}}
