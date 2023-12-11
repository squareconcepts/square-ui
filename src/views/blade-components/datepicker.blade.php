<div wire:ignore>
    <div
        x-data="{
            value: $wire.get('{{$model}}'),
            enableTime: {{ $enableTime ? 1 : 0}} == 1 ? true : false,
            timeOnly: {{$timeOnly ? 1 : 0}} == 1 ? true : false,
            timeFormat24: {{$timeFormat24 ? 1 : 0}} == 1 ? true : false,
            init() {
                let picker = flatpickr(document.getElementById('date-picker-{{$id}}'), {
                    dateFormat: this.enableTime ? (this.timeOnly ? 'H:i' : 'd-m-Y H:i') : 'd-m-Y',
                    enableTime: this.enableTime,
                    time_24hr: this.timeFormat24,
                    minDate: {{empty($minDate) ? 'null' : $minDate}},
                    maxDate: {{empty($maxDate) ? 'null' : $maxDate}},
                    minTime: {{empty($minTime) ? 'null' : $minTime}},
                    maxTime: {{empty($maxTime) ? 'null' : $maxTime}},
                    weekNumbers: true,
                    noCalendar: this.timeOnly,
                    locale: {
                        firstDayOfWeek: 1
                    },
                    defaultDate: this.value,
                    onChange: (date, dateString) => {
                        if (!dateString) {
                            $wire.set('{{$model}}', null)
                        } else {
                            $wire.set('{{$model}}', dateString)
                        }
                    }
                });
            }
        }" class="w-full">
        <x-square-ui.inputs::text class="w-full rounded-md border border-gray-200 px-3 py-2.5 cursor-pointer" id="date-picker-{{$id}}" :label="$label" :placeholder="$placeholder" {{$attributes}} x-ref="picker" right-icon="{{$timeOnly ? 'clock' : 'calendar'}}" readonly="readonly"/>
    </div>
</div>
