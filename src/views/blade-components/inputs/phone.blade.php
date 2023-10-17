<div class="flex flex-col mb-4 {{$attributes->has('disabled') ? 'opacity-60' : ''}}" x-data="{value: $wire.entangle('{{ $attributes['wire:model'] }}').live}">
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
                <i class="fa fa-{{$icon}} mx-1"></i>
            </div>
        <input {{ $attributes->merge(['class' => $getClasses($errors->has($name)) ]) }}  type="{{$type}}" name="{{ $name }}" {{ $attributes->except('wire:model') }} placeholder="{{$placeholder ?? $label}}"  @if(!$show1Password) data-1p-ignore @endif x-model="value" x-on:input="value = value.replace(/[^0-9\s\-\(\)\+]/g, '')"/>
    </div>

    @error($name)
    <p {{ $attributes->merge(['class' => 'mt-1 text-sm text-red-600']) }}>
        {{ $message }}
    </p>
    @enderror
</div>
