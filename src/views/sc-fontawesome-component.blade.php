<div class="flex items-center justify-center" wire:init="init">
    @if($apiTokenIsEmpty)
        <x-input type="text"  wire:model="api_token" placeholder="Font Awesome API Key" label="Font Awesome API Key"  class="w-full">
            <x-slot name="append">
                <div class="absolute inset-y-0 right-0 flex items-center p-0.5">
                    <x-button
                            wire:click="storeApiKey"
                            class="h-full rounded-r-md"
                            icon="check"
                            positive
                            squared
                    />
                </div>
            </x-slot>
        </x-input>
    @else
        <x-select
                option-label="name"
                option-value="value"
                :options="$styles"
                wire:model.live="style"
                class="w-80"
                placeholder="{{trans('square-ui::square-ui.select_family')}}"
        />

        <i class="{{$style}} fa-{{$name}} w-32 !flex justify-center items-center"></i>

        <x-input type="text" class="w-60" wire:model="name" wire:keyup.debounce.500ms="searchName" placeholder="{{trans('square-ui::square-ui.search_icon')}}" list="iconOptions" />

        <datalist id="iconOptions">
            @foreach($icons as $key => $result)
                <option wire:key="icon-{{ $result }}" data-value="{{ $result }}" value="{{ $result }}"></option>
            @endforeach
        </datalist>

    @endif
</div>


