@php
    $defaultLocale = config('app.locale');
    $languages = config('square-ui.languages', []);
    // Vlag via de mapping in config('square-ui.languages') (bijv. en => gb); er is geen en.svg.
    $flagUrl = fn (string $lang) => asset('vendor/squareconcepts/square-ui/flags/' . ($languages[$lang] ?? $lang) . '.svg');
    $flagAlt = fn (string $lang) => (class_exists(\Locale::class) ? (\Locale::getDisplayLanguage($lang, app()->getLocale()) ?: $lang) : $lang);
@endphp
<x-square-ui.tooltip>
    <x-slot:toggle>
        @if($useTextArea)
            <flux:field>
                <flux:label> <img src="{{ $flagUrl($defaultLocale) }}" alt="{{ $flagAlt($defaultLocale) }}" class="w-6 h-auto rounded mr-2"> {{$label ?? __('square-ui::square-ui.translatable_field', ['lang' => config('app.locale')])}}</flux:label>
                <flux:textarea wire:model="value.{{config('app.locale')}}" wire:key="translation-value-default-lang" rows="auto" />
                <flux:error name="value.{{config('app.locale')}}" />
            </flux:field>
        @else
            <flux:input wire:model="value.{{config('app.locale')}}" wire:key="translation-value-default-lang"  label="{{$label ?? __('square-ui::square-ui.translatable_field', ['lang' => config('app.locale')])}}">
                <x-slot name="icon">
                    <img src="{{ $flagUrl($defaultLocale) }}" alt="{{ $flagAlt($defaultLocale) }}" class="w-6 h-auto rounded">
                </x-slot>
            </flux:input>
        @endif
    </x-slot:toggle>
    <x-slot:content class="w-full">
       <div class=" space-y-2 max-h-48 overflow-auto">
           @foreach($languages as $lang => $flag)
               @if($lang != config('app.locale'))
                   @if($useTextArea)

                       <flux:field class="mb-6">
                           <flux:label class="mb-0"> <img src="{{ $flagUrl($lang) }}" alt="{{ $flagAlt($lang) }}" class="w-6 h-auto rounded mr-2"> {{$label ?? __('square-ui::square-ui.translation', ['lang' => $lang])}}</flux:label>
                               <flux:textarea wire:model="value.{{$lang}}" wire:key="translation-value{{$lang}}" rows="auto" />
                           <flux:error name="value.{{$lang}}" />
                       </flux:field>
                   @else
                   <flux:input wire:model="value.{{$lang}}" wire:key="translation-value-{{$lang}}">
                       <x-slot name="icon">
                           <img src="{{ $flagUrl($lang) }}" alt="{{ $flagAlt($lang) }}" class="w-6 h-auto rounded">
                       </x-slot>
                   </flux:input>
                   @endif
               @endif
           @endforeach
       </div>
    </x-slot:content>
</x-square-ui.tooltip>
