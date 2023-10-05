<div class="flex items-center justify-evenly" wire:init="init">
    @if($apiTokenIsEmpty)
        <div class="flex gap-2">
            <input class="input-style" type="text"  wire:model="api_token" placeholder="Font Awesome API Key" label="Font Awesome API Key"  />
            <x-square-ui::button type="positive" label="Opslaan"/>
        </div>
    @else
        <select
                wire:model.live="style"
                class="w-80 input-style flex-1"
                placeholder="{{trans('square-ui::square-ui.select_family')}}"
        >
            @foreach($styles as $style_option)
                <option value="{{ $style_option['value'] }}">{{ $style_option['name'] }}</option>
            @endforeach
        </select>

        <span class="">
           <i class="{{$style}} fa-{{$name}} w-32 !flex justify-center items-center"></i>
        </span>

        <div class="flex-1">
            <x-square-ui::dropdown>
                <x-slot:triggerSlot>
                    <input type="text" class="input-style w-60" wire:model="name" wire:keyup.debounce.100ms="searchName" placeholder="{{trans('square-ui::square-ui.search_icon')}}" list="iconOptions" />
                </x-slot:triggerSlot>
                <ul>
                    @forelse($icons as $key => $result)
                        <li class="cursor-pointer px-2 py-1 hover:bg-slate-100" wire:key="icon-{{ $result }}" wire:click="setOption('{{ $result }}')" x-on:click="open = false;">{{ $result }}</li>
                    @empty
                        <li>{{ __("No results found") }}</li>
                    @endforelse
                </ul>
            </x-square-ui::dropdown>
        </div>
    @endif
</div>


