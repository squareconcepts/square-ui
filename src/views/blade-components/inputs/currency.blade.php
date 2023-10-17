<div class="flex flex-col mb-4 {{$attributes->has('disabled') ? 'opacity-60' : ''}}" x-data="{value: $wire.entangle('{{ $attributes['wire:model'] }}').live, formattedPrice: '' }">
    <?php
        $name = $attributes->get('name') ?? $attributes->get('wire:model');
        $hasError = $errors->has($name);
    ?>

    <label for="{{ $name }}" class="text-sm font-medium text-gray-700 flex items-center gap-1 {{$errors->has($name) ? 'text-red-500 !font-bold' : ''}}">
        {{ $label }}
        @if ($attributes->get('required'))
            <span class="text-red-500 font-bold">*</span>
        @endif
    </label>
    <div class="relative rounded-md">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none" >
                @if($dollar)
                    <i class="fa fa-dollar-sign mx-1"></i>
                @else
                    <i class="fa fa-{{$icon}} mx-1"></i>
                @endif
            </div>
        <input {{ $attributes->merge(['class' => $getClasses($errors->has($name)) ]) }}
               type="{{$type}}"
               name="{{ $name }}" {{ $attributes->except('wire:model') }}
               placeholder="{{$placeholder ?? $label}}"
                data-1p-ignore
               x-model="value"
               x-on:input="
            // Verwijder alle tekens behalve cijfers, punten en komma's
            value = value.replace(/[^0-9,.]/g, '');

            // Vervang komma's door punten om een uniform formaat te krijgen
            value = value.replace(/,/g, '.');

            // Als er meerdere punten of komma's zijn, behoud alleen de eerste
            value = value.replace(/(\.|,).*[\.,]/, '$1');

            // Zorg dat er slechts één decimaalteken is
            value = value.replace(/(\d*)\.(.*)\./, '$1.$2');
            formattedPrice = value; // Laat de prijs in onbewerkte vorm zien
        "
               x-bind:value="formattedPrice"
        />
    </div>

    @error($name)
    <p {{ $attributes->merge(['class' => 'mt-1 text-sm text-red-600']) }}>
        {{ $message }}
    </p>
    @enderror
</div>

