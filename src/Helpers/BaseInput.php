<?php

    namespace Squareconcepts\SquareUi\Helpers;

    use Illuminate\View\Component;

    class BaseInput extends Component
    {


        public function __construct(
            public string $label,
            public ?string $placeholder = null,
            public string $type = 'text',
            public ?string $icon = null,
            public ?string $rightIcon = null,
            public bool $show1Password = false,
            public ?string $prefix = null,
        )
        {

        }

        public function render()
        {
            return view('square-ui::blade-components.inputs.base-input');
        }

        public function getClasses( $hasError = false): string
        {
            $baseClass = 'border-secondary-300 input-style';
            $errorClass = 'border-red-500 !border-2 text-red-500 !pr-10';
            $rightIconClass = '!pr-10';
            $iconClass = '!pl-10';

            $class = $baseClass;
            if ($hasError) {
               $class .= ' ' . $errorClass;
            }
            if ($this->rightIcon != null) {
                $class .=  ' ' . $rightIconClass;
            }
            if ($this->icon != null) {
                $class .=  ' ' . $iconClass;
            }
            return $class;
        }
    }
