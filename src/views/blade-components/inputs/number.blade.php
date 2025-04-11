@php
    $wireModel = $attributes->wire('model')->value();

    if($wireModel){

        $value = $this->{$attributes->wire('model')->value()};
         if(isset($value) && !is_numeric($value)){
            throw new InvalidArgumentException(' SquareUI input Exception: [Number input] wire:model has to be a integer');
        }
    } else {
        throw new InvalidArgumentException(' SquareUI input Exception: [Number input] wire:model is required');
    }
    $min = null;
    $max = null;
    if($attributes->has('min')) {
        $min = $attributes->get('min');
    }

    if($attributes->has('max')) {
        $max = $attributes->get('max');
    }


@endphp
<div x-data="{
    value: $wire.entangle('{{$attributes->wire('model')?->value()}}').live ?? 0,
    minValue: @js($min),
    maxValue: @js($max),
    init() {
        this.value = Number(this.value);
        this.value = this.validateValue();
    },
    validateValue() {
        if(this.minValue != null) {
            if(this.value < this.minValue) {
                return this.minValue;
            }
        }
        if(this.maxValue != null) {
            return Math.min(this.maxValue, this.value);
        }
        return this.value;
    },
   plus() {
    this.value++;
     this.value = this.validateValue();
   },
   min() {
      this.value = Math.max(0, (this.value - 1));
       this.value = this.validateValue();
   }

}"
     class="flex flex-col"
     wire:ignore
>

    <div class="w-full relative block group/input !text-center" data-flux-input="">
        <div class="z-10 absolute top-0 bottom-0 flex items-center justify-center text-xs text-zinc-400/75 ps-3 start-0">
            <flux:button size="sm" variant="danger" icon="minus" class="-ml-2" @click="min()" />
        </div>

        <input type="tel" class=" text-center w-full border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-[1.375rem] ps-10 pe-10 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5"
               x-model="value" x-mask="99999999999999999999999" data-flux-control="" data-flux-group-target="">

        <div class="absolute top-0 bottom-0 flex items-center gap-x-1.5 pe-3 end-0 text-xs text-zinc-400">
            <flux:button size="sm" variant="positive" icon="plus" class="-mr-2" @click="plus()" />
        </div>
    </div>

</div>
