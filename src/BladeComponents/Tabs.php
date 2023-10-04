<?php

    namespace Squareconcepts\SquareUi\BladeComponents;

    use Illuminate\Contracts\View\View;
    use Illuminate\View\Component;

    class Tabs extends Component
    {
        public function __construct(public array $tabs, public ?string $activeTab = null)
        {
            if($this->activeTab == null) {
                $this->activeTab = $this->tabs[0];
            }
        }

        public function render(): View
        {
            return view('components.tabs');
        }
    }
