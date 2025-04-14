<?php
namespace Squareconcepts\SquareUi\LivewireComponents;

use Livewire\Component;

class LocalizedStringComponent extends Component
{
    public string $field;
    public array $value;
    public string $event;
    public string $identifier = '';
    public string $rerenderValueEvent = 'unknown';

    protected function getListeners()
    {
        return [
            $this->rerenderValueEvent => 'rerenderValue'
        ];
    }

    protected $rules = [
        'value.*' => 'nullable|array'
    ];

    public function render()
    {
        return view('square-ui::livewire-components.localized-string-component');
    }

//    public function emitValue(): void
//    {
//        $this->dispatch($this->event, $this->field, $this->value, $this->identifier);
//    }

    public function rerenderValue($data): void
    {
        $this->value = $data;
    }
}
