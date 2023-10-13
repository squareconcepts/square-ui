<div class="flex flex-col mb-4 {{$attributes->has('disabled') ? 'opacity-60' : ''}}" x-data="{value: $wire.entangle('{{ $attributes['wire:model'] }}').live,}">
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
        <input {{ $attributes->merge(['class' => $getClasses($errors->has($name)) ]) }}  type="{{$type}}" name="{{ $name }}" {{ $attributes }} placeholder="{{$placeholder ?? $label}}"  />
        @if($hasError || $rightIcon != null || $hasButtons)
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center justify-center
                    {{ $hasError ? 'text-red-500' : 'text-secondary-400' }}">

                @if ($rightIcon)
                    <i class="fa fa-{{$rightIcon}} mx-1"></i>
                @elseif ($hasError)
                    <i class="fa fa-exclamation-circle mx-1"></i>
                @endif
                @if($hasButtons)
                    <div class="flex divide-x" >
                        <button type="button" class="px-2 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-r-md mr-1" x-on:click="value++"> <i class="fa fa-plus"></i></button>
                        <button type="button" class="px-2 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-r-md" x-on:click="value--"> <i class="fa fa-minus"></i> </button>
                    </div>
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
