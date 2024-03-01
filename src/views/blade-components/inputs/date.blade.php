<div x-data="datepicker({{$attributes->get('x-model')}})" class="relative" wire:ignore x-on:click.outside="submitChanges()" @keydown.escape.window="close()" {{$attributes->except(['wire:model'])}}>
    @dump($attributes->getAttributes())
    <div class="relative">
        <label for="number" class="text-sm font-medium text-gray-700 flex items-center gap-1 relative ">
            {{$attributes->get('label')}}
        </label>
        <input type="text" readonly x-on:click="open = !open" class="input-style pr-10" x-model="value"  data-1p-ignore />
        <div class="absolute right-0 mr-5 flex top-[20px] bottom-0 items-center justify-center">
            <i class="fa-solid fa-calendar-alt text-slate-400"></i>
        </div>

    </div>

    <template x-if="open">
        <div class="absolute top-[67px] left-0 right-1 bg-white rounded w-full min-w-[400px] max-w-[400px] mb-4 shadow" x-ref="calendar" >
            <template x-if="!showingTimepicker">
                <div>
                    <div class="flex justify-between items-center p-2">
                        <div @click="prevMonth()" class="w-10 h-10 hover:bg-slate-100 flex justify-center items-center cursor-pointer">
                            <i class="fa-solid fa-chevron-left"></i>
                        </div>

                        <div class="flex-1 font-bold text-slate-700 uppercase text-center flex justify-center items-center gap-1">
                            <select x-model="monthString" class="border-0 font-bold text-slate-700 ring-0 focus-visible:!ring-0 p-0 pr-8">
                                <option value="Januari">Januari </option>
                                <option value="Februari">Februari </option>
                                <option value="Maart">Maart </option>
                                <option value="April">April </option>
                                <option value="Mei">Mei </option>
                                <option value="Juni">Juni </option>
                                <option value="Juli">Juli </option>
                                <option value="Augustus">Augustus </option>
                                <option value="September">September </option>
                                <option value="Oktober">Oktober </option>
                                <option value="November">November </option>
                                <option value="December">December </option>
                            </select>
                            <select x-model="year" class="border-0 font-bold text-slate-700 uppercase ring-0 focus-visible:!ring-0 p-0 pr-8">
                                @foreach (range((now()->year - 20), (now()->year + 50)) as $year)
                                    <option value="{{$year}}">{{$year}} </option>
                                @endforeach
                            </select>
                        </div>

                        <div @click="nextMonth()" class="w-10  h-10 hover:bg-slate-100 flex justify-center items-center cursor-pointer">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                    <div class="grid grid-cols-8 w-full">
                        <div class="min-min-w-[50px] h-[30px] flex items-center justify-center bg-slate-300 font-bold text-slate-700 uppercase text-xs">Week</div>
                        <div class="min-min-w-[50px] h-[30px] flex items-center justify-center font-bold bg-slate-300  text-slate-700 uppercase text-xs">ma</div>
                        <div class="min-w-[50px] h-[30px] flex items-center justify-center font-bold bg-slate-300  text-slate-700 uppercase text-xs">di</div>
                        <div class="min-w-[50px] h-[30px] flex items-center justify-center font-bold bg-slate-300  text-slate-700 uppercase text-xs">wo</div>
                        <div class="min-w-[50px] h-[30px] flex items-center justify-center font-bold bg-slate-300  text-slate-700 uppercase text-xs">do</div>
                        <div class="min-w-[50px] h-[30px] flex items-center justify-center font-bold bg-slate-300  text-slate-700 uppercase text-xs">vr</div>
                        <div class="min-w-[50px] h-[30px] flex items-center justify-center font-bold bg-slate-300  text-slate-700 uppercase text-xs">za</div>
                        <div class="min-w-[50px] h-[30px] flex items-center justify-center font-bold bg-slate-300  text-slate-700 uppercase text-xs">zo</div>
                        <template x-for="(week, weekIndex) in weeks" :key="'week-' + weekIndex">
                            <div class="col-span-full grid grid-cols-8">
                                <div class="w-[50px] h-[50px] flex items-center justify-center opacity-50 bg-slate-300" x-text="getWeekNumber(week[0].date)"></div>
                                <template x-for="(day, dayIndex) in week" :key="'day-' + dayIndex">
                                    <template x-if="day.showDate">
                                        <div class="w-[50px] h-[50px] flex items-center justify-center cursor-pointer group"
                                             @click="selectDate(day)"
                                        >
                                            <p class="w-[40px] h-[40px] flex items-center justify-center text-center"
                                               :class="day.isCurrent ? 'bg-positive-500 text-white font-extrabold rounded-full' : (day.monthType !== 'current' ? 'text-slate-700 opacity-50 group-hover:bg-slate-400 rounded-full' : 'group-hover:text-white group-hover:font-extrabold group-hover:bg-slate-400 group-hover:rounded-full')"
                                               x-text="day.date !== null ? day.date.format('DD') : ''"
                                            >

                                            </p>
                                        </div>
                                    </template>
                                    <template x-if="!day.showDate">
                                        <div class="w-[50px] h-[50px]"></div>
                                    </template>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
            <template x-if="showingTimepicker">
                <div class="flex flex-col items-center p-2 h-[240px]">
                    <p class="font-bold text-slate-700">Tijd kiezen</p>
                    <p class="w-full pb-2 border-b text-center" x-text="value"></p>
                    <div class="flex justify-center gap-2 items-center p-2 h-[200px]">
                        <div class="flex flex-col gap-2  w-[100px] ">
                            <select class="input-style" x-model="hours">
                                <template x-for="(hour, hourIndex) in hourOptions" :key="'hour-' + hourIndex">
                                    <option x-text="hour < 10 ? ('0' + hour) : hour" :value="hour" :selected="hour == hours" ></option>
                                </template>
                            </select>
                        </div>
                        <div> : </div>
                        <div class="flex flex-col gap-2 w-[100px]">
                            <select class="input-style" x-model="minutes">
                                <template x-for="(minute, minuteIndex) in minuteOptions" :key="'minute-' + minuteIndex">
                                    <option x-text="minute < 10 ? ('0' + minute) : minute" :value="minute" :selected="minute == minutes"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>
            </template>
            <div class="bg-slate-200 p-4 flex justify-between items-center">
                <div>
                    <x-button positive icon="check" @click="submitChanges()" :label="__('Save')"/>
                    <x-button negative icon="x" @click="close()" label="Sluiten"/>
                </div>
                <template x-if="withTime">
                    <div class="flex items-center gap-2 p-2">
                        <template x-if="!showingTimepicker">
                            <div >
                                <span @click="showingTimepicker = true" class="cursor-pointer text-slate-400 hover:text-primary-950">
                                <i class="fa-solid fa-clock"></i>
                            </span>
                                /
                                <span @click="showingTimepicker = false" class="cursor-pointer text-primary-950">
                                <i class="fa-solid fa-calendar-alt"></i>
                            </span>
                            </div>
                        </template>
                        <template x-if="showingTimepicker">
                            <div>
                                <span @click="showingTimepicker = true" class="cursor-pointer text-primary-950">
                                <i class="fa-solid fa-clock"></i>
                            </span>
                                /
                                <span @click="showingTimepicker = false" class="cursor-pointer text-slate-400 hover:text-primary-950" >
                                <i class="fa-solid fa-calendar-alt"></i>
                            </span>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </template>

    @push('scripts')
        @script
        <script>
            Alpine.data('datepicker', (date) => ({
                model: @js($model),
                withTime: @js($withTime),
                showingTimepicker: false,
                hourOptions: @js($hourOptions),
                minuteOptions: @js($minuteOptions),
                hours: {{$value->hour}},
                minutes: {{$value->minute}},
                usingLivewire: @js($attributes->has('wire:model')),
                value: '',
                xModelValue: date,
                valueDate: '',
                open: false,
                date: '',
                year: '',
                monthString: '',
                weeks: [],
                init() {
                    if(this.xModelValue != null || this.xModelValue !== undefined) {
                        this.value = moment(this.xModelValue).format('DD-MM-YYYY HH:mm');
                        this.valueDate = moment(this.xModelValue);
                        this.date = moment(this.xModelValue);
                        this.year = this.date.year();
                    } else {
                        this.valueDate = moment('{{$value->toDateTimeString()}}');
                        this.value = moment('{{$value->toDateTimeString()}}').format('DD-MM-YYYY HH:mm');
                        this.date = moment('{{$value->toDateTimeString()}}');
                        this.year = moment('{{$value->toDateTimeString()}}').year();
                    }

                    if(this.withTime) {
                        this.hours = this.valueDate.format('HH');
                        this.minutes = this.valueDate.format('mm');
                    }

                    this.weeks = this.getDaysOfMonth();
                    this.monthString = this.getMonthName();
                    this.$watch('hours', (value) => {
                        this.valueDate.hour(value);
                        this.value = this.valueDate.format('DD-MM-YYYY HH:mm');

                    });
                    this.$watch('minutes', (value) => {
                        this.valueDate.minutes(value);
                        this.value = this.valueDate.format('DD-MM-YYYY HH:mm');
                    })
                    this.$watch('year', (value) => {
                        this.date.year(value);
                        this.$nextTick(() => {
                            this.updateCalendar();
                        });

                    });
                    this.$watch('monthString', (value) => {
                        this.date.month(this.getMonthIndex());
                        this.$nextTick(() => {
                            this.updateCalendar();
                        });

                    });
                    this.$watch('open', (value) => {
                        if(value){
                            this.updatePosition();
                        }

                    });



                },
                updatePosition() {
                    if (this.open) {
                        const inputRect = this.$el.getBoundingClientRect();
                        const calendarRect = this.$refs.calendar.getBoundingClientRect();

                        if (inputRect.bottom + calendarRect.height > window.innerHeight) {
                            this.$refs.calendar.style.top = '-'+calendarRect.height + 'px';
                        } else {
                            this.$refs.calendar.style.top = '63px';
                        }

                        this.open = true;
                    }
                },
                getDaysOfMonth() {
                    const date = this.date;
                    const year = date.year();
                    const month = date.month();
                    const daysInMonth = date.daysInMonth();
                    const firstDayOfMonth = moment([year, month, 1]);
                    let startDay = firstDayOfMonth.day(); // Day of the week (0 = Sunday, 1 = Monday, etc.)

                    // Als startDay gelijk is aan 0 (zondag), moeten we het aanpassen naar 7 (zondag wordt de zevende dag)
                    if (startDay === 0) {
                        startDay = 7;
                    }

                    const days = [];
                    let week = [];

                    // Vul de eerste week met lege cellen totdat de eerste dag van de maand bereikt is
                    for (let i = 1; i < startDay; i++) {
                        const prevMonthDate = firstDayOfMonth.clone().subtract(startDay - i, 'days');
                        week.push({ date: prevMonthDate, isCurrent: false, showDate: true, monthType: 'previous' });
                    }

                    // Loop door elke dag van de maand en vul de weken dienovereenkomstig
                    for (let i = 1; i <= daysInMonth; i++) {
                        const dayDate = moment([year, month, i]);
                        week.push({ date: dayDate, isCurrent: dayDate.isSame(this.valueDate, 'day'), showDate: true, monthType: 'current' });

                        // Als het einde van de week is bereikt, voeg de week toe aan de dagen en reset de week array
                        if (week.length === 7) {
                            days.push(week);
                            week = [];
                        }
                    }

                    // Controleer of de laatste week volledig is
                    if (week.length > 0) {
                        // Vul de resterende dagen van de week met dagen van de volgende maand
                        const nextMonth = moment([year, month]).add(1, 'month');
                        for (let i = 1; week.length < 7; i++) {
                            const nextMonthDate = nextMonth.clone().date(i);
                            week.push({ date: nextMonthDate, isCurrent: false, showDate: true, monthType: 'next' });
                        }
                        days.push(week);
                    }

                    return days;
                },
                getWeekNumber(date) {
                    return moment(date).week();
                },
                getMonthName() {
                    return ['Januari', 'Februari',  'Maart',  'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'][this.date.month()];
                },
                getMonthIndex() {
                    return ['Januari', 'Februari',  'Maart',  'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'].indexOf(this.monthString);
                },
                isSameDate(date){
                    return  moment(date).isSame(this.valueDate, 'day');
                },
                prevMonth() {
                    this.date = this.date.subtract(1, 'M');
                    this.$nextTick(() => {
                        this.updateCalendar();
                    });
                },
                nextMonth() {
                    this.date = this.date.add(1, 'M');
                    this.$nextTick(() => {
                        this.updateCalendar();
                    });
                },
                updateCalendar() {
                    this.weeks = this.getDaysOfMonth();
                    this.monthString = this.getMonthName();
                    this.year = this.date.year();
                },
                selectDate(date) {
                    this.valueDate = date.date;
                    this.valueDate.minutes(this.minutes);
                    this.valueDate.hours(this.hours);
                    this.value = this.valueDate.format('DD-MM-YYYY HH:mm');
                    if (date.monthType === 'previous') {
                        this.prevMonth();
                    } else if (date.monthType === 'next') {
                        this.nextMonth();
                    } else {
                        this.$nextTick(() => {
                            this.updateCalendar();
                        });
                    }

                    if(this.withTime) {
                        this.showingTimepicker = true;
                    }


                },
                decreaseHour(){
                    this.hours = this.hours - 1;
                },
                increaseHour(){
                    this.hours = this.hours + 1;
                },
                submitChanges(){
                    if(this.open){
                        if(this.usingLivewire) {
                            @this.set(this.model, this.value);
                        } else {
                            this.$dispatch('datechanged',  this.value);
                        }

                        this.close();
                    }
                },
                increaseMinute(){
                    this.minutes = this.minutes + 1;
                },
                decreaseMinute(){
                    this.minutes = this.minutes - 1;
                },
                close(){
                    if(this.open) {
                        this.open = false;
                    }
                }
            }));
        </script>
        @endscript
    @endpush
</div>
