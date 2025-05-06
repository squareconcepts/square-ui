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
        if(this.model !== null){
            if(this.model.indexOf('T') == -1){
               let dateValues = this.model.split('-');
               const getTime  = (string) => {
                    let splitString = string.split('+');
                    let timeValues = splitString[0].split(':');
                    let date = new Date();
                    date.setHours(timeValues[0].slice(-2));
                    date.setMinutes(timeValues[1]);
                    date.setSeconds(timeValues[2] ?? '0');
                    return date;

               };
                if(dateValues[0].length == 4 ){
                    //us date format
                    let date = new Date();
                    date.setYear(dateValues[0]);
                    date.setMonth(dateValues[1] - 1);
                    date.setDate(dateValues[2].substring(0,2));
                    if(this.enableTime) {
                        let timeDate = getTime(dateValues[2])
                        date.setHours(timeDate.getHours());
                        date.setMinutes(timeDate.getMinutes());
                        date.setSeconds(timeDate.getSeconds());
                    }
                     this.model = date.toISOString();
                } else {
                     let date = new Date();
                    date.setDate(dateValues[0]);
                    date.setMonth(dateValues[1] - 1);
                    date.setYear(dateValues[2].substring(0,4));
                     if(this.enableTime) {
                        let timeDate = getTime(dateValues[2])
                        date.setHours(timeDate.getHours());
                        date.setMinutes(timeDate.getMinutes());
                        date.setSeconds(timeDate.getSeconds());
                    }

                    this.model = date.toISOString();
                }
            }

        }
        let date = this.model ? new Date(this.model) : null;
        if(date !== null) {

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
        } else {
            this.hours = new Date().getHours();
            this.minutes = new Date().getMinutes();
        }
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
    },
    resetDate() {
        this.model = null;
        this.date = null;
        this.dateString  = null;
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
       <flux:field>
           @if($label)
           <flux:label>{{$label}}</flux:label>
           @endif

       <flux:input
           class="cursor-pointer"
           as="button"
           icon="calendar"
           @click="open = true"
           clearable
       >

           <div x-text="dateString"></div>

       </flux:input>

       </flux:field>


       {{-- De dialog --}}
       <dialog
           x-ref="dialog"
           x-show="open"
           x-transition
           @click.outside="open = false"
           wire:ignore
           wire:cloak
           class="max-sm:max-h-full! rounded-xl shadow-xl sm:shadow-2xs max-sm:fixed! max-sm:inset-0! sm:backdrop:bg-transparent bg-white dark:bg-zinc-900 sm:border border-zinc-200 dark:border-white/10 block mt-1 self-start mx-0 z-10"
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
                       @if($asDropdown)
                           <flux:select variant="listbox"   x-model="hours"  placeholder="Choose hours...">
                               @foreach(range(0,23) as $hour)
                                   <flux:select.option>{{$hour}}</flux:select.option>
                               @endforeach
                           </flux:select>
                           <flux:select variant="listbox"   x-model="minutes"  placeholder="Choose minutes...">
                               @foreach(range(0,59) as $minute)
                                   <flux:select.option>{{$minute}}</flux:select.option>
                               @endforeach
                           </flux:select>
                       @else
                           <flux:input mask="99"  x-model="hours" min="0" max="23" type="number"/>
                           <flux:input mask="99"  x-model="minutes" min="0" max="59" type="number"/>
                       @endif

                   </div>
               @endif
               <flux:separator  class="my-3"/>
               <flux:button variant="danger" @click="resetDate()" class="w-full" >Wissen</flux:button>
           </div>
       </dialog>
   </div>
</div>
