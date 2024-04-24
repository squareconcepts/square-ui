<div x-data="datepicker('{{ $attributes->has('x-model') ? 'true' : 'false' }}', {{$attributes->get('x-model')}})" class="relative"  x-on:click.outside="submitChanges()" @keydown.escape.window="close()" {{$attributes->except(['wire:model'])}}>
    <div class="relative">
        <label for="number" class="text-sm font-medium text-gray-700 flex items-center gap-1 relative @error($model) !text-red-500 @enderror">
            {{$attributes->get('label')}}
        </label>

        <div class="relative flex items-center">
            <input type="text" readonly x-on:click="open = !open" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm input-style pr-10 @error($model) !border-red-500 @enderror" x-model="value"  data-1p-ignore />
            <i class="fa-solid fa-calendar-alt text-slate-400 absolute right-2 @error($model) !text-red-500 @enderror"></i>
        </div>
        @error($model)
        <p class="mt-1 text-sm text-red-600" wire:model="{{$model}}">
            {{$message}}
        </p>
        @enderror

    </div>

    <template x-if="open">
        <div class="absolute top-[67px] left-0 right-1 bg-white rounded w-full min-w-[400px] max-w-[400px] mb-4 shadow z-[100]" x-ref="calendar" >
            <div class="grid grid-cols-3 gap-1 p-1">
                <div @click="selectYesterday()" class="outline-none inline-flex justify-center items-center group transition-all ease-in duration-150  disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-2 text-sm px-4 py-2 border text-slate-500 hover:bg-slate-100 bg-slate-200 border-slate-200 hover:bg-slate-100 hover:border-slate-100 shadow-none cursor-pointer">
                    Gister
                </div>
                <div @click="selectToday()" class="outline-none inline-flex justify-center items-center group transition-all ease-in duration-150  disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-2 text-sm px-4 py-2 border text-slate-500 hover:bg-slate-100 bg-slate-200 border-slate-200 hover:bg-slate-100 hover:border-slate-100 shadow-none cursor-pointer">
                    Vandaag
                </div>
                <div @click="selectTomorrow()"  class="outline-none inline-flex justify-center items-center group transition-all ease-in duration-150  disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-2 text-sm px-4 py-2 border text-slate-500 hover:bg-slate-100 bg-slate-200 border-slate-200 hover:bg-slate-100 hover:border-slate-100 shadow-none cursor-pointer">
                    Morgen
                </div>
            </div>
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
                            <input type="number" x-model="year" class="border-0 font-bold text-slate-700 uppercase ring-0 focus-visible:!ring-0 p-0 w-[80px]"/>
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
                                            <p class="w-[40px] h-[40px] flex items-center justify-center text-center "
                                               {{--                                               :class="--}}
                                               {{--                                               day.isCurrent ? 'bg-primary-500 text-white font-extrabold rounded-full' :--}}
                                               {{--                                               (day.monthType !== 'current' ? (day.isToday ? 'bg-slate-100 text-slate-700 opacity-50 group-hover:bg-slate-200 rounded-full' : 'text-slate-700 opacity-50 group-hover:bg-slate-400 rounded-full')--}}
                                               {{--                                               : 'group-hover:text-white group-hover:font-extrabold group-hover:bg-slate-200 group-hover:rounded-full')--}}
                                               {{--                                               "--}}

                                               :class="{
                                                'bg-primary-500 text-white font-extrabold rounded-full' : day.isCurrent,
                                                'group-hover:bg-slate-200 group-hover:rounded-full' : !day.isToday && !day.isCurrent,
                                                'border border-primary-500 rounded-full' : day.isToday,
                                                'text-slate-700 opacity-50 group-hover:bg-slate-400 rounded-full' : day.monthType !== 'current' && !day.isToday,

                                               }"
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
                <div class="flex flex-wrap gap-1">
                    <x-button sm primary icon="check" @click="submitChanges()" :label="__('Save')"/>
                    <x-button sm icon="x" @click="close()" label="Sluiten"/>
                    <x-button sm icon="trash" @click="clearPicker()" label="Wissen"/>
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
            Alpine.data('datepicker', (useXModel, date) => ({
                model: @js($model),
                withTime: @js($withTime),
                showingTimepicker: false,
                hourOptions: @js($hourOptions),
                minuteOptions: @js($minuteOptions),
                hours: {{$value->hour}},
                minutes: {{$value->minute}},
                usingLivewire: @js($attributes->has('wire:model')),
                value: '',
                prevValue: '',
                useXModel: useXModel,
                xModelValue: date,
                valueDate: '',
                open: false,
                date: '',
                year: '',
                monthString: '',
                weeks: [],
                init() {
                    if(this.useXModel == 'true') {
                        if(this.xModelValue != null && this.xModelValue !== undefined) {
                            if (this.withTime) {
                                this.value = moment(this.xModelValue).format('DD-MM-YYYY HH:mm');
                            } else {
                                this.value = moment(this.xModelValue).format('DD-MM-YYYY');
                            }
                            this.valueDate = moment(this.xModelValue);
                            this.date = moment(this.xModelValue);
                        } else {
                            this.date = moment();
                        }
                        this.year = this.date.year();
                    } else {
                        if(this.usingLivewire) {
                            let modelDate = this.createDateFromString($wire.get(this.model));
                            if(modelDate != null) {
                                this.valueDate = moment(modelDate);
                            } else {
                                this.valueDate = moment('{{$value->toDateTimeString()}}');
                            }

                        } else {
                            this.valueDate = moment('{{$value->toDateTimeString()}}');
                        }
                        this.updateInternalDate();
                        this.date =  this.valueDate;
                        this.year = this.valueDate.year();
                    }

                    if(this.withTime && this.valueDate != null && this.valueDate != '') {
                        this.hours = this.valueDate.format('HH');
                        this.minutes = this.valueDate.format('mm');
                    }

                    this.weeks = this.getDaysOfMonth();
                    this.monthString = this.getMonthName();

                    this.setPrevValue();

                    document.addEventListener('updateDatepicker', event => {
                        this.resetDate(event.detail);
                    });

                    this.$watch('hours', (value) => {
                        if (this.valueDate != null && this.valueDate != '') {
                            this.valueDate.hour(value);
                        }

                        this.updateInternalDate();
                    });
                    this.$watch('minutes', (value) => {
                        if (this.valueDate != null && this.valueDate != '') {
                            this.valueDate.minutes(value);
                        }
                        this.updateInternalDate();
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

                    this.submitInitialValue();
                },
                createDateFromString(dateString) {
                    if(dateString == null) {
                        return null;
                    }
                    // Probeer het te parsen als een ISO-8601 formaat met tijd
                    let date = new Date(dateString);
                    if (!isNaN(date.getTime())) {
                        return date;
                    }

                    // Probeer het te parsen als een Amerikaanse datumnotatie (MM-DD-YYYY)
                    const parts = dateString.split('-');
                    if (parts.length === 3) {
                        date = new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
                        if (!isNaN(date.getTime())) {
                            return date;
                        }
                    }

                    // Probeer het te parsen als een Europese datumnotatie (DD-MM-YYYY)
                    date = new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
                    if (!isNaN(date.getTime())) {
                        return date;
                    }

                    // Als geen enkel formaat werkt, retourneer null of een standaarddatum
                    return null;
                },
                updateInternalDate(){
                    if(this.valueDate != null && this.valueDate != '') {
                        if (this.withTime) {
                            this.value = this.valueDate.format('DD-MM-YYYY HH:mm');
                        } else {
                            this.value = this.valueDate.format('DD-MM-YYYY');
                        }
                    } else {
                        this.value = '';
                    }
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
                        week.push({ date: prevMonthDate, isCurrent: false, isToday: false, showDate: true, monthType: 'previous' });
                    }

                    // Loop door elke dag van de maand en vul de weken dienovereenkomstig
                    for (let i = 1; i <= daysInMonth; i++) {
                        const dayDate = moment([year, month, i]);
                        week.push({ date: dayDate, isCurrent: dayDate.isSame(this.valueDate, 'day'), isToday: dayDate.isSame(moment(), 'day'), showDate: true, monthType: 'current' });

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
                            week.push({ date: nextMonthDate, isCurrent: false, isToday: false, showDate: true, monthType: 'next' });
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
                resetDate(date) {
                    if(date != null && date != '') {
                        var isoPattern = /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}).(\d{6})Z$/;
                        const isIso = isoPattern.test(date);
                        if (this.withTime) {
                            if(isIso) {
                                this.value = moment(date).format('DD-MM-YYYY HH:mm');
                                this.prevValue = moment(date).format('DD-MM-YYYY HH:mm');
                            } else {
                                this.value = moment(date, 'DD-MM-YYYY HH:mm').format('DD-MM-YYYY HH:mm');
                                this.prevValue = moment(date, 'DD-MM-YYYY HH:mm').format('DD-MM-YYYY HH:mm');
                            }
                        } else {
                            if(isIso) {
                                this.value = moment(date).format('DD-MM-YYYY');
                                this.prevValue = moment(date).format('DD-MM-YYYY');
                            } else {
                                this.value = moment(date, 'DD-MM-YYYY HH:mm').format('DD-MM-YYYY');
                                this.prevValue = moment(date, 'DD-MM-YYYY HH:mm').format('DD-MM-YYYY');
                            }
                        }

                        if(isIso) {
                            this.valueDate = moment(date);
                            this.date = moment(date);
                        } else {
                            this.valueDate = moment(date, 'DD-MM-YYYY HH:mm');
                            this.date = moment(date, 'DD-MM-YYYY HH:mm');
                        }
                    } else {
                        this.date = moment();
                        this.value = '';
                        this.prevValue = '';
                        this.valueDate = '';
                    }

                    this.year = this.date.year();

                    if(this.withTime && this.valueDate != null && this.valueDate != '') {
                        this.hours = this.valueDate.format('HH');
                        this.minutes = this.valueDate.format('mm');
                    } else {
                        this.hours = 0;
                        this.minutes = 0;
                    }

                    this.weeks = this.getDaysOfMonth();
                    this.monthString = this.getMonthName();
                    this.setPrevValue();
                    this.$nextTick(() => {
                        this.updateCalendar();
                    });
                },
                selectYesterday(){
                    let date = moment().subtract(1, 'day');
                    this.selectDate({date:date});
                },
                selectToday(){
                    let date = moment();
                    this.selectDate({date:date});
                },
                selectTomorrow(){
                    let date = moment().add(1, 'day');
                    this.selectDate({date:date});
                },
                selectDate(date) {
                    this.valueDate = date.date;
                    this.valueDate.minutes(this.minutes);
                    this.valueDate.hours(this.hours);
                    this.updateInternalDate();
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
                        this.setPrevValue();
                        this.close();
                    }
                },
                submitInitialValue(){
                    this.selectDate({date:this.valueDate});
                    if(this.usingLivewire) {
                        @this.set(this.model, this.value);
                    } else {
                        this.$dispatch('datechanged',  this.value);
                    }
                    this.setPrevValue();
                    this.close();
                },
                increaseMinute(){
                    this.minutes = this.minutes + 1;
                },
                decreaseMinute(){
                    this.minutes = this.minutes - 1;
                },
                clearPicker() {
                    this.value = '';
                    this.valueDate = '';
                    this.$nextTick(() => {
                        this.updateCalendar();
                    });
                    this.submitChanges();
                },
                setPrevValue() {
                    this.prevValue = JSON.parse(JSON.stringify(this.value));
                },
                close(){
                    if(this.open) {
                        this.open = false;
                        this.showingTimepicker = false;
                        this.value = this.prevValue
                    }
                }
            }));
        </script>
        @endscript
    @endpush
</div>
