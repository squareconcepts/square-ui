<?php
    namespace Squareconcepts\SquareUi\BladeComponents;

    use Illuminate\Contracts\View\View;
    use Illuminate\View\Component;

    class Pickr extends Component {

        public function __construct(public string $value, public string $componentId, public string $identifier, public array $colorOptions = ['#cf3c25', '#d66e20', '#52b268', '#52b268', '#51b47c', '#2c6f2f', '#465c5f', '#2c3a3d', '#1d2626', '#1eb9d7', '#1c9cc4'])
        {
        }

        public function render()
        {
            return view('square-ui::blade-components.pickr');
        }
    }
