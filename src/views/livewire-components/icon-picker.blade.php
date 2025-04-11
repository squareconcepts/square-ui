<div wire:init="init">
    @if($apiTokenIsEmpty)
        <flux:callout icon="exclamation-triangle" variant="warning">
            <flux:callout.heading>Font Awesome Api key Leeg</flux:callout.heading>
            <flux:callout.text>Om font awesome te gebruiken hebben we een API Key nodig.  Ga naar <flux:link variant="ghost" href="https://fontawesome.com/account/general" class="text-amber-700"> FontAwesome -> Account -> General</flux:link>  Onder API Tokesn staat de key die we nodig hebben. </flux:callout.text>
            <flux:separator />
            <x-slot name="actions">
                <flux:input.group>
                    <flux:input wire:model="api_token" placeholder="Api Key" />
                    <flux:button icon="check" variant="positive" wire:click="storeApiKey()">Opslaan</flux:button>
                </flux:input.group>
            </x-slot>
        </flux:callout>
    @else
        <flux:input.group>
            <flux:select variant="listbox"  placeholder="Choose industries..." class="max-w-fit">
                @foreach($styles as $style_option)
                    <flux:select.option value="{{ $style_option['value'] }}">{{ $style_option['name'] }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:select variant="listbox" searchable :filter="false">
                <x-slot name="search">
                    <flux:select.search class="px-4" placeholder="Search industries..."  wire:model="name" wire:keyup.debounce.100ms="searchName"/>
                </x-slot>
                @foreach($icons as $icon)
                    <flux:select.option wire:key="icon-{{$icon}}" value="{{$icon}}">
                        <i class="{{$style}} fa-{{$name}} w-32 !flex justify-center items-center h-full"></i>
                        {{ $icon }}</flux:select.option>
                @endforeach
            </flux:select>
        </flux:input.group>
        {{$style}}
        {{$name}}
    @endif


</div>

{{--<div class="flex items-center justify-evenly" >--}}
{{--    @if($apiTokenIsEmpty)--}}
{{--        <flux:callout icon="clock">--}}
{{--            <flux:callout.heading>Subscription expiring soon</flux:callout.heading>--}}
{{--            <flux:callout.text>Your current plan will expire in 3 days. Renew now to avoid service interruption and continue accessing premium features.</flux:callout.text>--}}
{{--            <x-slot name="actions">--}}
{{--                <flux:button>Renew now</flux:button>--}}
{{--                <flux:button variant="ghost" href="/pricing">View plans</flux:button>--}}
{{--            </x-slot>--}}
{{--        </flux:callout>--}}
{{--        <div class="flex gap-2">--}}
{{--            <flux:input wire:model="api_token" label="Font Awesome API Key" />--}}
{{--            <flux:button variant="positive" >Opslaan</flux:button>--}}
{{--        </div>--}}
{{--    @else--}}
{{--        <select--}}
{{--                wire:model.live="style"--}}
{{--                class="w-80 input-style flex-1"--}}
{{--                placeholder="{{trans('square-ui::square-ui.select_family')}}"--}}
{{--        >--}}
{{--            @foreach($styles as $style_option)--}}
{{--                <option value="{{ $style_option['value'] }}">{{ $style_option['name'] }}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}

{{--        <span class="">--}}
{{--           <i class="{{$style}} fa-{{$name}} w-32 !flex justify-center items-center"></i>--}}
{{--        </span>--}}

{{--        <div class="flex-1">--}}
{{--            <x-square-ui::dropdown>--}}
{{--                <x-slot:triggerSlot>--}}
{{--                    <input type="text" class="input-style w-60" wire:model="name" wire:keyup.debounce.100ms="searchName" placeholder="{{trans('square-ui::square-ui.search_icon')}}" list="iconOptions" />--}}
{{--                </x-slot:triggerSlot>--}}
{{--                <ul>--}}
{{--                    @forelse($icons as $key => $result)--}}
{{--                        <li class="cursor-pointer px-2 py-1 hover:bg-slate-100" wire:key="icon-{{ $result }}" wire:click="setOption('{{ $result }}')" x-on:click="open = false;">{{ $result }}</li>--}}
{{--                    @empty--}}
{{--                        <li>{{ __("No results found") }}</li>--}}
{{--                    @endforelse--}}
{{--                </ul>--}}
{{--            </x-square-ui::dropdown>--}}
{{--        </div>--}}
{{--    @endif--}}
{{--</div>--}}


