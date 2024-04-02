<div class="flex flex-col mb-4 {{$attributes->has('disabled') ? 'opacity-60' : ''}}" x-data="{value:  $wire.entangle('{{ $attributes['wire:model'] }}').live,type: 'password'}">
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
            <i class="fa fa-lock mx-1"></i>
        </div>
        <input {{ $attributes->merge(['class' => 'border-secondary-300 focus:ring-primary focus:border-primary placeholder:primary-700 ' . $getClasses($errors->has($name)) ]) }}  type="{{$type}}" name="{{ $name }}" {{ $attributes->except('wire:model') }} placeholder="{{$placeholder ?? $label}}"   data-1p-ignore x-bind:type="type" x-model="value"/>
        <div class="absolute inset-y-0 right-0 flex items-center justify-center">
            <i x-show="type == 'password'" class="fa fa-eye mx-1 cursor-pointer" x-on:click="type = 'text'" x-cloak></i>
            <i x-show="type == 'text'" class="fa fa-eye-slash mx-1 cursor-pointer" x-on:click="type = 'password'" x-cloak></i>
            <button type="button"
                    class="h-full px-2 py-1 text-sm  rounded-r-md text-primary-900 bg-primary-300"
                    x-on:click="value = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);"
            > @lang('square-ui::square-ui.generate')</button>
        </div>
    </div>

    @error($name)
    <p {{ $attributes->merge(['class' => 'mt-1 text-sm text-red-600']) }}>
        {{ $message }}
    </p>
    @enderror
</div>
