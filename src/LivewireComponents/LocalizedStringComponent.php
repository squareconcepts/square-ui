<?php
namespace Squareconcepts\SquareUi\LivewireComponents;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class LocalizedStringComponent extends Component
{
    #[Modelable]
    public array $value;

    public ?string $label = null;

    public bool $useTextArea = false;


    protected $rules = [
        'value.*' => 'nullable|array',
    ];

    public function render()
    {
        return view('square-ui::livewire-components.localized-string-component');
    }
}
