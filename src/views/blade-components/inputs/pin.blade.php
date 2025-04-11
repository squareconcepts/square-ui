@props([
    'length' => 4,
    'size' => 'default'
])
@php
    $sizeClasses = match ($size){
         'sm' => 'size-9.5',
         'lg' => 'size-15',
         'default' => 'size-11',
         default => 'size-11'
    }
@endphp
<div class="flex w-fit gap-x-3" x-data="scPinInput">
    @foreach(range(0, $length - 1) as $i)
        <input
            type="text"
            maxlength="1"
            class=" {{$sizeClasses}} border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5"
            x-model="values[{{ $i }}]"
            @focus="event.target.select()"
            @input="handleInput($event, {{ $i }})"
        />
    @endforeach

</div>
@script
<script>
    Alpine.data('scPinInput', () => ({
        pinModel:  $wire.entangle('{{ $attributes['wire:model'] }}').live,
        length: @js($length),
        values: Array(@js($length)).fill(''),
        init() {
            this.$watch('values', () => {
                console.log(this.values.length == this.length);
              if(this.values.length == this.length) {
                  this.pinModel = this.values.join('');
              }
            });
        },
        handleInput(e, index) {
            const input = e.target;
            if (input.value.length > 1) {
                input.value = input.value.slice(-1);
            }
            this.values[index] = input.value;

            if (input.value && index < this.length - 1) {
                input.nextElementSibling?.focus();
            }
        }
    }))
</script>
@endscript
