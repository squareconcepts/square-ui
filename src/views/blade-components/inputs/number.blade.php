<div class="flex flex-col mb-4 {{$attributes->has('disabled') ? 'opacity-60' : ''}}" x-data="{
    value: $wire.entangle('{{ $attributes['wire:model'] }}').live,
    step: {{$step}},
    minus() {
        if (this.value - this.step >= {{$min}}) {
            this.value -= this.step;
        }
    }
}">
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
    <div class="relative w-fit rounded-md {{$hasButtons ? 'has-buttons' : ''}}">
        @if ($prefix || $icon)
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none
                {{ $hasError ? 'text-red-500' : 'text-secondary-400' }}">
                @if ($icon)
                    <i class="fa fa-{{$icon}} mx-1"></i>
                @elseif($prefix)
                    <span class="flex items-center self-center pl-1">
                        {{ $prefix }}
                    </span>
                @endif
            </div>
        @endif
        @if($hasButtons && !$attributes->has('disabled'))
            <button type="button" class="h-full px-2 py-1 aspect-square text-sm text-white absolute bg-sc-blue rounded-l-md left-0" x-on:click="minus"> <i class="fa fa-minus"></i> </button>
        @endif
        <input {{ $attributes->merge(['class' => 'square-ui-input ' . $getClasses($errors->has($name)) ]) }} min="{{$min}}" step="{{$step}}" type="{{$type}}" name="{{ $name }}" {{ $attributes }} placeholder="{{$placeholder ?? $label}}" @if(!$show1Password) data-1p-ignore @endif />
        @if($hasError || $rightIcon != null || $hasButtons)
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center justify-center
                    {{ $hasError ? 'text-red-500' : 'text-secondary-400' }}">

                @if ($rightIcon)
                    <i class="fa fa-{{$rightIcon}} mx-1"></i>
                @elseif ($hasError)
                    <i class="fa fa-exclamation-circle mx-1"></i>
                @endif
                @if($hasButtons && !$attributes->has('disabled'))
                        <button type="button" class="h-full px-2 py-1 aspect-square text-sm text-white absolute bg-sc-blue rounded-r-md right-0" x-on:click="value = Number(value) + step"> <i class="fa fa-plus"></i></button>
                @endif
            </div>
        @endif
    </div>

    @error($name)
    <p {{ $attributes->merge(['class' => 'mt-1 text-sm text-red-600']) }}>
        {{ $message }}
    </p>
    @enderror
</div>
