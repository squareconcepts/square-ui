<div class="flex flex-col gap-2 relative" x-data="{showDropdown: false}">
    <x-square-ui::dropdown>
        <x-slot:triggerSlot>
            <x-input wire:model="value.{{ config('app.locale') }}" class="py-2" style="padding-left: 40px" wire:keyup.debounce.500ms="emitValue">
                <x-slot name="prepend">
                    <div class="absolute inset-y-0 left-0 flex items-center p-0.5 z-20 cursor-pointer" x-on:click="showDropdown = true">
                        <x-dynamic-component component="flag-country-{{ config('square-ui.languages')[config('app.locale')] }}" class="w-6 h-6 ml-2"/>
                    </div>
                </x-slot>
            </x-input>
        </x-slot:triggerSlot>
        <div class="flex flex-col gap-2">
            @foreach(config('square-ui.languages') as $lang => $flag)
                @if($lang != config('app.locale'))
                    <x-input wire:model="value.{{ $lang }}" class="py-2" style="padding-left: 40px;" wire:keyup.debounce.500ms="emitValue">
                        <x-slot name="prepend">
                            <div class="absolute inset-y-0 left-0 flex items-center p-0.5 z-20">
                                <x-dynamic-component component="flag-country-{{ $flag }}" class="w-6 h-6 ml-2 "/>
                            </div>
                        </x-slot>
                    </x-input>
                @endif
            @endforeach
        </div>
    </x-square-ui::dropdown>
</div>
