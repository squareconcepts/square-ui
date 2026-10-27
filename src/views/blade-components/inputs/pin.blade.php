@props([
    'length' => 4,
    'size' => 'default'
])
{{-- @deprecated 4.x, removed in 5.0: use <flux:otp>. See UPGRADE.md. --}}
@php
    $sizeClasses = match ($size){
         'sm' => 'size-9.5',
         'lg' => 'size-15',
         'default' => 'size-11',
         default => 'size-11'
    };
    $wireModel = $attributes->whereStartsWith('wire:model')->first();
@endphp
<flux:field>
    <flux:label>{{$attributes['label'] ?? 'Pin'}}</flux:label>
    {{-- Inline x-data: elke instantie heeft eigen state. wire:model/x-model wordt via x-modelable gekoppeld. --}}
    <div class="flex w-fit gap-x-3"
         {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}
         x-modelable="pinModel"
         x-data="{
            pinModel: '',
            length: @js((int) $length),
            values: Array(@js((int) $length)).fill(''),
            init() {
                this.setValues();
                this.$watch('values', () => {
                    this.pinModel = this.values.map((value) => value ?? '').join('');
                });
                this.$watch('pinModel', (value) => {
                    if ((value ?? '') !== this.values.map((v) => v ?? '').join('')) {
                        this.setValues();
                    }
                });
            },
            inputs() {
                return this.$root.querySelectorAll('input');
            },
            handleInput(e, index) {
                const input = e.target;
                if (input.value.length > 1) {
                    input.value = input.value.slice(-1);
                }
                this.values[index] = input.value;

                if (input.value && index < this.length - 1) {
                    this.inputs()[index + 1]?.focus();
                }
            },
            async handlePaste() {
                const text = await navigator.clipboard.readText();
                this.pinModel = String(text ?? '').trim().slice(0, this.length);
                this.setValues();
                this.inputs()[Math.min(this.pinModel.length, this.length - 1)]?.focus();
            },
            setValues() {
                const chars = [...String(this.pinModel ?? '')].slice(0, this.length);
                this.values = Array.from({ length: this.length }, (_, i) => chars[i] ?? '');
            },
            handleBackspace(event, index) {
                if (index > 0) {
                    this.inputs()[index - 1]?.focus();
                }
                this.values[index] = '';
            }
         }"
         @keydown.cmd.v="handlePaste()"
         @keydown.ctrl.v="handlePaste()">

        @foreach(range(0, $length - 1) as $i)

            <input
                type="text"
                maxlength="1"
                class=" {{$sizeClasses}} border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10  w-10 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5"
                x-model="values[{{ $i }}]"
                @focus="$event.target.select()"
                @input="handleInput($event, {{ $i }})"
                @keydown.backspace="handleBackspace($event, {{$i}})"
            />
        @endforeach

    </div>
    @if($wireModel)
        <flux:error name="{{ $wireModel }}" />
    @endif
</flux:field>
