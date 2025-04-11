<div
    x-data="{
    open: false,
    model: @entangle($attributes->wire('model')).live,
    dateString: null,
    enableTime: @js($enableTime),
    date: null,
    hours: null,
    minutes: null,
    init() {
        let date = this.model ? new Date(this.model) : new Date();
        this.date = date.toISOString();
        this.hours = date.getHours();
        this.minutes = date.getMinutes();
        this.setModelString();
        this.$watch('hours', (value) => {
            if (value > 23) {
                this.$nextTick(() => { this.hours = 23 });
            }
            date.setHours(this.hours);
            date.setMinutes(this.minutes);
            this.setDate(date);
        });

        this.$watch('minutes', (value) => {
            if (value > 59) {
                this.$nextTick(() => { this.minutes = 59 });
            }
            date.setMinutes(this.minutes);
            date.setHours(this.hours);

            this.setDate(date);
        });
        this.$watch('model', (value) => {
           if(!this.enableTime){
               this.$nextTick(() => { this.open = false;});
           }
        });
    },
     setDate(date) {
        this.model = date;
        this.setModelString();
    },
    setModelString() {
       if (this.date instanceof Date) {
            const pad = (num) => String(num).padStart(2, '0');

            const dag = pad(this.date.getDate());
            const maand = pad(this.date.getMonth() + 1);
            const jaar = this.date.getFullYear();
            const uur = pad(this.hours);
            const minuten = pad(this.minutes);

            this.dateString = `${dag} - ${maand} - ${jaar} ${uur}:${minuten}`;
            this.model = this.dateString;
        } else {
            const pad = (num) => String(num).padStart(2, '0');
            const date = new Date(this.date);
            const dag = pad(date.getDate());
            const maand = pad(date.getMonth() + 1);
            const jaar = date.getFullYear();
            const uur = pad(this.hours);
            const minuten = pad(this.minutes);

            if(this.enableTime) {
                this.dateString = `${dag}-${maand}-${jaar} ${uur}:${minuten}:00`;
            } else {
                this.dateString = `${dag}-${maand}-${jaar}`;
            }

            this.model = this.dateString;
        }
    }
    }"
    @keydown.escape.window="open = false"
>
    @php
        $wireModelAttributes = collect($attributes->getAttributes())
            ->filter(fn($v, $k) => str_starts_with($k, 'wire:model'))
            ->keys()->toArray();
    @endphp

   <div class="relative">
       <flux:input
           class="cursor-pointer"
           as="button"
           icon-trailing="calendar"
           @click="open = true"
       >
           <div x-text="dateString"></div>
       </flux:input>


       {{-- De dialog --}}
       <dialog
           x-ref="dialog"
           x-show="open"
           x-transition
           @click.outside="open = false"
           wire:ignore
           wire:cloak
           class="max-sm:max-h-full! rounded-xl shadow-xl sm:shadow-2xs max-sm:fixed! max-sm:inset-0! sm:backdrop:bg-transparent bg-white dark:bg-zinc-900 sm:border border-zinc-200 dark:border-white/10 block mt-1 self-start mx-0"
       >
           <div class="p-4">
               <flux:calendar
                   x-model="date"
                   @change="(newDate) => {
                       if (newDate?.target?.value) {
                           let parts = newDate.target.value.split('-');
                           if (parts.length === 3) {
                               let year = parseInt(parts[0], 10);
                               let month = parseInt(parts[1], 10) - 1; // Month is 0-based
                               let day = parseInt(parts[2], 10);

                               let selectedDate = new Date()
                               selectedDate.setYear(year);
                               selectedDate.setMonth(month);
                               selectedDate.setDate(day);
                               selectedDate.setHours(hours);
                               selectedDate.setMinutes(minutes);
                               setDate(selectedDate);
                           }
                       }
                   }"
               ></flux:calendar>
               @if($enableTime)
                   <flux:separator :text="__('Time')"  class="my-3"/>
                   <div class="flex gap-4 justify-evenly px-4">
                       <flux:input mask="99"  x-model="hours" min="0" max="23" type="number"/>
                       <flux:input mask="99"  x-model="minutes" min="0" max="59" type="number"/>
                   </div>
               @endif
           </div>
       </dialog>
   </div>
</div>
