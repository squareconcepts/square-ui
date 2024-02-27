<div class="flex flex-col mb-4">
    <?php
        $name = $attributes->get('name') ?? $attributes->get('wire:model');
        $hasError = $errors->has($name);
     ?>
    <label for="{{ $name }}" class="text-sm font-medium text-gray-700 flex items-center gap-1 {{$errors->has($name) ? 'text-red-500 !font-bold' : ''}}">
        {{ $label }}
        @if ($attributes->get('required'))
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="relative rounded-md">
        <input {{ $attributes->merge(['class' => 'input-style' . ($errors->has($name) ? ' border-red-500 !border-2 text-red-500 !pl-10 !pr-10' : '') ]) }}  type="{{$type}}" name="{{ $name }}" {{ $attributes }} placeholder="{{$placeholder ?? $label}}" @if(!$show1Password) data-1p-ignore @endif/>
    @error($name)
        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none justify-center
                {{ $hasError ? 'text-red-500' : 'text-secondary-400' }}">

            @if ($rightIcon)
                <i class="fa fa-{{$rightIcon}}"></i>
            @elseif ($hasError)
                <i class="fa fa-exclamation"></i>
            @endif
        </div>
        @enderror
    </div>

    @error($name)
        <p {{ $attributes->merge(['class' => 'mt-1 text-sm text-red-600']) }}>
            {{ $message }}
        </p>
    @enderror
</div>
