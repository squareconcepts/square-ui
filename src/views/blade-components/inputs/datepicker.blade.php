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
        // Alleen de weergave vullen; het (.live) model wordt bij het laden niet herschreven.
        this.syncFromModel(this.model);

        this.$watch('model', (value) => {
            this.syncFromModel(value);
            if (!this.enableTime) {
                this.$nextTick(() => { this.open = false; });
            }
        });

        this.$watch('hours', (value) => {
            if (value > 23) {
                this.$nextTick(() => { this.hours = 23 });
            }
            this.updateTime();
        });

        this.$watch('minutes', (value) => {
            if (value > 59) {
                this.$nextTick(() => { this.minutes = 59 });
            }
            this.updateTime();
        });
    },
    pad(num) {
        return String(num).padStart(2, '0');
    },
    // Parse Y-m-d[ H:i[:s]], d-m-Y[ H:i[:s]] of een ISO-string naar een lokale Date (of null).
    parseModel(value) {
        if (value === null || value === undefined || value === '') {
            return null;
        }
        if (value instanceof Date) {
            return isNaN(value) ? null : value;
        }
        value = String(value);
        if (value.indexOf('T') !== -1) {
            const iso = new Date(value);
            return isNaN(iso) ? null : iso;
        }
        const match = value.match(/^\s*(\d{1,4})\s*-\s*(\d{1,2})\s*-\s*(\d{1,4})(?:[ ]+(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?)?/);
        if (!match) {
            return null;
        }
        const [year, day] = match[1].length === 4 ? [match[1], match[3]] : [match[3], match[1]];
        // new Date(j, m, d) i.p.v. setYear/setMonth/setDate: voorkomt overflow op de 29e-31e.
        const date = new Date(Number(year), Number(match[2]) - 1, Number(day), Number(match[4] ?? 0), Number(match[5] ?? 0), Number(match[6] ?? 0));
        return isNaN(date) ? null : date;
    },
    toYmd(date) {
        return `${date.getFullYear()}-${this.pad(date.getMonth() + 1)}-${this.pad(date.getDate())}`;
    },
    format(date) {
        const dateString = `${this.pad(date.getDate())}-${this.pad(date.getMonth() + 1)}-${date.getFullYear()}`;
        return this.enableTime ? `${dateString} ${this.pad(date.getHours())}:${this.pad(date.getMinutes())}:00` : dateString;
    },
    syncFromModel(value) {
        const date = this.parseModel(value);
        if (date === null) {
            this.date = null;
            this.dateString = null;
            if (this.hours === null) {
                this.hours = new Date().getHours();
                this.minutes = new Date().getMinutes();
            }
            return;
        }
        this.date = this.toYmd(date);
        this.dateString = this.format(date);
        if (Number(this.hours) !== date.getHours()) {
            this.hours = date.getHours();
        }
        if (Number(this.minutes) !== date.getMinutes()) {
            this.minutes = date.getMinutes();
        }
    },
    // Bouwt een lokale Date uit de gekozen dag (Y-m-d) en de uren/minuten.
    selectedDate() {
        if (!this.date) {
            return null;
        }
        const [year, month, day] = String(this.date).substring(0, 10).split('-').map(Number);
        const date = new Date(year, month - 1, day, Number(this.hours ?? 0) || 0, Number(this.minutes ?? 0) || 0, 0);
        return isNaN(date) ? null : date;
    },
    updateTime() {
        if (this.model === null || this.model === undefined || this.model === '') {
            return;
        }
        const date = this.selectedDate();
        if (date !== null) {
            this.setDate(date);
        }
    },
    setDate(date) {
        this.date = this.toYmd(date);
        this.dateString = this.format(date);
        const value = this.format(date);
        if (this.model !== value) {
            this.model = value;
        }
    },
    changePeriod(period) {
        let now = new Date();
        let newDate;

        switch (period) {
            case 'today':
                newDate = now;
                break;
            case 'tomorrow':
                newDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
                break;
            case 'week':
                let dayOfWeek = now.getDay();
                let daysUntilFriday = 5 - dayOfWeek;
                if (daysUntilFriday < 0) {
                    daysUntilFriday += 7;
                }
                newDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() + daysUntilFriday);
                break;
            case 'next_week':
                let currentDay = now.getDay();
                let daysToNextFriday = (5 - currentDay + 7) % 7;
                if (daysToNextFriday === 0) {
                    daysToNextFriday = 7;
                }
                newDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() + daysToNextFriday + 7);
                break;
            case 'month':
                newDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                while (newDate.getDay() === 6 || newDate.getDay() === 0) {
                    newDate.setDate(newDate.getDate() - 1);
                }
                break;
            case 'next_month':
                newDate = new Date(now.getFullYear(), now.getMonth() + 2, 0);
                while (newDate.getDay() === 6 || newDate.getDay() === 0) {
                    newDate.setDate(newDate.getDate() - 1);
                }
                break;
            default:
                newDate = now;
        }

        newDate.setHours(17);
        newDate.setMinutes(0);
        newDate.setSeconds(0);

        this.hours = newDate.getHours();
        this.minutes = newDate.getMinutes();
        this.setDate(newDate);
    },
    resetDate() {
        this.model = null;
        this.date = null;
        this.dateString  = null;
    }
    }"
    @keydown.escape.window="open = false"
>
    <div class="relative">
        <flux:field>
            @if($label)
                <flux:label>{{ $label }}</flux:label>
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

        <dialog
            x-ref="dialog"
            x-show="open"
            x-transition
            @click.outside="open = false"
            wire:ignore
            wire:cloak
            class="max-sm:max-h-full! rounded-xl shadow-xl sm:shadow-2xs max-sm:fixed! max-sm:inset-0! sm:backdrop:bg-transparent bg-white dark:bg-zinc-900 sm:border border-zinc-200 dark:border-white/10 block mt-1 self-start mx-0 z-10"
        >
            <div class="p-4 flex">
                @if($showPeriods)
                    <div class="flex flex-col gap-2 items-start">
                        @foreach($periodOption as $period)
                            <flux:button size="xs" variant="ghost" x-on:click="changePeriod({{ \Illuminate\Support\Js::from($period) }})">@lang('report.period.'.$period)</flux:button>
                        @endforeach
                    </div>
                    <flux:separator vertical class="mx-3" />
                @endif
                <div>
                    <flux:calendar
                        x-model="date"
                        @change="(newDate) => {
                           if (newDate?.target?.value) {
                               let parts = newDate.target.value.split('-');
                               if (parts.length === 3) {
                                   {{-- new Date(j, m, d): setYear/setMonth/setDate op 'vandaag' liep over op de 29e-31e. --}}
                                   let selectedDate = new Date(
                                       parseInt(parts[0], 10),
                                       parseInt(parts[1], 10) - 1,
                                       parseInt(parts[2], 10),
                                       Number(hours ?? 0) || 0,
                                       Number(minutes ?? 0) || 0,
                                   );
                                   setDate(selectedDate);
                               }
                           }
                        }"
                    ></flux:calendar>
                    @if($enableTime)
                        <flux:separator :text="__('Time')" class="my-3" />
                        <div class="flex gap-4 justify-evenly px-4">
                            @if($asDropdown)
                                <flux:select variant="listbox" x-model="hours" placeholder="Kies uren...">
                                    @foreach(range(0,23) as $hour)
                                        <flux:select.option.variants.custom>{{ $hour }}</flux:select.option.variants.custom>
                                    @endforeach
                                </flux:select>
                                <flux:select variant="listbox" x-model="minutes" placeholder="Kies minuten...">
                                    @foreach(range(0,59) as $minute)
                                        <flux:select.option.variants.custom>{{ $minute }}</flux:select.option.variants.custom>
                                    @endforeach
                                </flux:select>
                            @else
                                <flux:input mask="99" x-model="hours" min="0" max="23" type="number" />
                                <flux:input mask="99" x-model="minutes" min="0" max="59" type="number" />
                            @endif
                        </div>
                    @endif
                    <flux:separator class="my-3" />
                    <flux:button variant="danger" @click="resetDate()" class="w-full">Wissen</flux:button>
                </div>
            </div>
        </dialog>
    </div>
</div>
