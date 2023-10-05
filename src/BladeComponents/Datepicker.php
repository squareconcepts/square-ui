<?php

    namespace Squareconcepts\SquareUi\BladeComponents;

    use Illuminate\Contracts\View\View;
    use Illuminate\Support\Str;
    use Illuminate\View\Component;

    class Datepicker extends Component
    {
        public array $options = [];
        public string $id;
        public function __construct(public bool $disableWeekends = false, public bool $startAtMonday = true, public bool $enableTime = true, public bool $timeFormat24 = true, public ?string $minDate = null, public ?string $maxDate = null, public bool $timeOnly = false, public ?string $minTime = null, public ?string $maxTime = null)
        {
            $this->setOptions();
            $this->id = Str::uuid();
            if($this->timeOnly) {
                $this->enableTime = true;
            }
        }

        public function render(): View
        {
            return view('square-ui::blade-components.datepicker');
        }

        public function setOptions(  ): void
        {

            $this->options['minDate'] = $this->minDate;
            $this->options['maxDate'] = $this->maxDate;
            $this->options['enableTime'] = $this->enableTime;
            $this->options['time_24hr'] = $this->timeFormat24;
            if($this->timeOnly) {
                $this->options['noCalendar'] = true;
                $this->options['enableSeconds'] = true;
                $this->options['minuteIncrement'] = 5;
                $this->options['minTime'] = '07:00';
                $this->options['maxTime'] = '18:00';
                $this->options['dateFormat'] =  'H:i:S';

            } else {
                $this->options['dateFormat'] = $this->enableTime ? 'd-m-Y H:i' : 'd-m-Y';
            }
            $this->options['weekNumbers'] = true;
            if ($this->startAtMonday) {
                $this->options['locale']['firstDayOfWeek'] = 1;
            }
        }
    }

