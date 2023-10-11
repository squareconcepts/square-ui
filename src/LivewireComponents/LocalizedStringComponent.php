<?php
namespace Squareconcepts\SquareUi\LivewireComponents;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\Rule;
use Livewire\Component;

class LocalizedStringComponent extends Component
{
    #[Modelable] #[Rule('nullable')]
    public array $value;

    protected $rules = [
        'value.*' => 'nullable|array'
    ];

    public function render()
    {
        return view('square-ui::livewire-components.localized-string-component');
    }
}
