<div class="flex items-center justify-center" wire:init="init">
    @if($apiTokenIsEmpty)
        <div class="flex gap-2">
            <input class="input-style" type="text"  wire:model="api_token" placeholder="Font Awesome API Key" label="Font Awesome API Key"  />
            <x-square-ui::button type="positive" label="Opslaan"/>
        </div>
    @else
        <select
                wire:model.live="style"
                class="w-80 input-style"
                placeholder="{{trans('square-ui::square-ui.select_family')}}"
        >
            @foreach($styles as $style_option)
                <option value="{{ $style_option['value'] }}">{{ $style_option['name'] }}</option>
            @endforeach
        </select>

        <span>
           <i class="{{$style}} fa-{{$name}} w-32 !flex justify-center items-center"></i>
        </span>

        <input type="text" class="input-style w-60" wire:model="name" wire:keyup.debounce.100ms="searchName" placeholder="{{trans('square-ui::square-ui.search_icon')}}" list="iconOptions" />

        <datalist id="iconOptions">
            @foreach($icons as $key => $result)
                <option wire:key="icon-{{ $result }}" data-value="{{ $result }}" value="{{ $result }}"></option>
            @endforeach
        </datalist>
    @endif
</div>


