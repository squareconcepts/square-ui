<x-square-ui.tooltip>
    <x-slot:toggle>
        @if($useTextArea)
            <flux:field>
                <flux:label> <img src="{{ asset('vendor/squareconcepts/square-ui/flags/'. config('app.locale') .'.svg') }}" alt="{{config('app.locale') }} flag" class="w-6 h-auto rounded mr-2"> {{$label ?? __('square-ui::square-ui.translatable_field', ['lang' => config('app.locale')])}}</flux:label>
                <flux:textarea wire:model="value.{{config('app.locale')}}" wire:key="translation-value-default-lang" rows="auto" />
                <flux:error name="value.{{config('app.locale')}}" />
            </flux:field>
        @else
            <flux:input wire:model="value.{{config('app.locale')}}" wire:key="translation-value-default-lang"  label="{{$label ?? __('square-ui::square-ui.translatable_field', ['lang' => config('app.locale')])}}">
                <x-slot name="icon">
                    <img src="{{ asset('vendor/squareconcepts/square-ui/flags/'.config('app.locale').'.svg') }}" alt="Nederlandse vlag" class="w-6 h-auto rounded">
                </x-slot>
            </flux:input>
        @endif
    </x-slot:toggle>
    <x-slot:content class="w-full">
       <div class=" space-y-2 max-h-48 overflow-auto">
           @foreach(config('square-ui.languages') as $lang => $flag)
               @if($lang != config('app.locale'))
                   @if($useTextArea)

                       <flux:field class="mb-6">
                           <flux:label class="mb-0"> <img src="{{ asset('vendor/squareconcepts/square-ui/flags/'.$flag.'.svg') }}" alt="{{$lang }} flag" class="w-6 h-auto rounded mr-2"> {{$label ?? __('square-ui::square-ui.translation', ['lang' => $lang])}}</flux:label>
                               <flux:textarea wire:model="value.{{$lang}}" wire:key="translation-value{{$lang}}" rows="auto" />
                           <flux:error name="value.{{$lang}}" />
                       </flux:field>
                   @else
                   <flux:input wire:model="value.{{$lang}}" wire:key="translation-value-{{$lang}}">
                       <x-slot name="icon">
                           <img src="{{ asset('vendor/squareconcepts/square-ui/flags/'.$flag.'.svg') }}" alt="{{$lang }} flag" class="w-6 h-auto rounded">
                       </x-slot>
                   </flux:input>
                   @endif
               @endif
           @endforeach
       </div>
    </x-slot:content>
</x-square-ui.tooltip>
