<?php

    namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

    use Carbon\Carbon;
    use Illuminate\Contracts\View\View;
    use Illuminate\View\Component;

    class Date extends Component
    {

        /**
         * @throws \Exception
         */
        public function __construct( public string $model, public null|string|Carbon $value = null, public bool $withTime = false, public array $hourOptions = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23], public array $minuteOptions = [0,5,10,15,20,25,30,35,40,45,50,55])
        {
            if($this->value == null) {
                $this->value = now();
            } else {
                if(is_string($this->value)) {
                    $this->value = Carbon::parse($this->value);
                }
            }

            if($this->withTime && count($this->hourOptions) < 1) {
                throw new \Exception('Hour options must contain at least one value to use with time.');
            }

            if($this->value instanceof Carbon && $this->withTime) {
                $minutes = $this->value->minute;
                $hour = $this->value->hour;
                $this->value->setHour($this->getFirstAvailableHourOption($hour));
                if(count($this->minuteOptions) == 1){
                    $rounding = $this->minuteOptions[0];

                    if($rounding > $minutes) {
                        $this->value->setMinutes($rounding);
                    } else {
                        $this->value = $this->value->setMinutes($rounding);

                        $this->value = $this->value->addHour();

                        $this->value->setHour($this->getFirstAvailableHourOption($this->value->hour));
                    }
                } elseif (count($this->minuteOptions) > 1) {
                    // Bereken hoeveel minuten naar het volgende veelvoud van 5 minuten moeten worden afgerond
                    $remainder = $minutes % $this->minuteOptions[1];
                    $rounding = $this->minuteOptions[1] - $remainder;

                    // Als de huidige minuten al het dichtstbijzijnde veelvoud van 5 minuten zijn, retourneer het datetime-object ongewijzigd
                    if ( $remainder === 0 ) {
                        return;
                    }
                    // Anders rond naar het dichtstbijzijnde veelvoud van 5 minuten
                    $this->value = $this->value->addMinutes($rounding);
                } else {
                    $this->minuteOptions = range(0,59);
                }

                if($this->value->hour > $hour){
                    $this->value->setMinutes($this->minuteOptions[0]);
                }

            }
        }

        private function getFirstAvailableHourOption( int $hour )
        {
            if(in_array($hour, $this->hourOptions) ) {
                return $hour;
            } else {
                $max = max($this->hourOptions);
                $min = min($this->hourOptions);

                if($hour < $min ) {
                    return $min;
                } else if ( $hour > $max ) {
                    return $min;
                } else {
                    $closestGreater = $max;
                    foreach ($this->hourOptions as $option) {
                        if ($option > $hour) {
                            $closestGreater = $option;
                            break; // Stop de lus zodra de eerste waarde groter is dan $hour
                        }
                    }
                    return $closestGreater;
                }
            }
        }

        public function render(): View
        {
            return view('square-ui::blade-components.inputs.date');
        }

    }
