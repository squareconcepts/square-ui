<div
    x-data="{
    open: false,
    model: @entangle($attributes->wire('model')).live,
    timeString: null,
    hours: null,
    minutes: null,
    dialogPosition: 'bottom', // Standaard positie
    init() {
        if(this.model == null){
            this.model = @js(\Illuminate\Support\Carbon::now()->format('h:i'))
        }
        const time = this.model.split(':');
        this.hours = time[0];
        this.minutes = time[1];
        this.setModelString();
        this.$watch('hours', (value) => {
            if (value > 23) {
                this.$nextTick(() => { this.hours = 23 });
            }
            this.setModelString();
        });

        this.$watch('minutes', (value) => {
            if (value > 59) {
                this.$nextTick(() => { this.minutes = 59 });
            }
             this.setModelString();
        });
    },
    setModelString() {
        const pad = (num) => String(num).padStart(2, '0');
        const uur = pad(this.hours);
        const minuten = pad(this.minutes);
        this.timeString = `${uur}:${minuten}`;
        this.model = `${uur}:${minuten}:00`;
    },
    toggleDialog() {
        this.open = !this.open;
        if (this.open) {
            this.$nextTick(() => {
                this.adjustDialogPosition();
            });
        }
    },
    adjustDialogPosition() {
        const dialog = this.$refs.dialog;
        const inputRect = this.$el.getBoundingClientRect();
        const dialogHeight = dialog.offsetHeight;
        const viewportHeight = window.innerHeight;
        const spaceBelow = viewportHeight - inputRect.bottom;
        const spaceAbove = inputRect.top;

        if (spaceBelow < dialogHeight && spaceAbove > dialogHeight) {
            this.dialogPosition = 'top';
        } else {
            this.dialogPosition = 'bottom';
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
        <flux:field>
            @if($label)
                <flux:label>{{$label}}</flux:label>
            @endif
            <flux:input
                class="cursor-pointer"
                as="button"
                icon-trailing="clock"
                @click="toggleDialog()"
            >
                <div x-text="timeString"></div>
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
            :class="{
               'absolute !top-full mt-1 self-start': dialogPosition === 'bottom',
               'absolute !bottom-full mb-1 self-start': dialogPosition === 'top',
               'max-sm:max-h-full! rounded-xl shadow-xl sm:shadow-2xs max-sm:fixed! max-sm:inset-0! sm:backdrop:bg-transparent bg-white dark:bg-zinc-900 sm:border border-zinc-200 dark:border-white/10 block mx-0 z-10': true
           }"
        >
            <div class="p-4">

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
            </div>
        </dialog>
    </div>
</div>
