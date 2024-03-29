<?php

    namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

    use Carbon\Carbon;
    use Illuminate\Contracts\View\View;
    use Illuminate\View\Component;

    class Date extends Component
    {

        public function __construct(public string $model, public null|string|Carbon $value = null, public bool $withTime = false, public array $hourOptions = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23], public array $minuteOptions = [0,5,10,15,20,25,30,35,40,45,50,55])
        {
            if($this->value == null) {
                $this->value = now();
            } else {
                if(is_string($this->value)) {
                    $this->value = Carbon::parse($this->value);
                }
            }
        }

        public function render(): View
        {
            return view('square-ui::blade-components.inputs.date');
        }

    }
