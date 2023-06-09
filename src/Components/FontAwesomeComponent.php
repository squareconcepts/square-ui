<?php

namespace Squareconcepts\SquareUi\Components;

use Livewire\Component;
use Squareconcepts\SquareUi\Helpers\ScFontAwesome;
use Squareconcepts\SquareUi\SquareUi;
use WireUi\Traits\Actions;

class FontAwesomeComponent extends Component
{
    use Actions;

    public ScFontAwesome $service;
    public $style;
    public $name;
    public $icons = [];
    public $field;
    public $value;
    public $event;

    public $api_token = '';
    public bool $apiTokeIsEmpty = false;


    public string $identifier = '';

    protected array $rules = [
        'style' => 'nullable',
        'name' => 'nullable',
        'icons' => 'nullable',
        'icon' => 'nullable',
        'api_token' => 'nullable'
    ];

    public function mount()
    {

    }

    public function render()
    {
        return view('square-ui::sc-fontawesome-component', ['styles' => ScFontAwesome::getStyles()]);
    }

    public function emitValue(): void
    {
        $this->emit($this->event, $this->field, $this->value, $this->identifier);
    }

    public function updatedStyle(): void
    {
        if (empty($this->style) || empty($this->name)) {
            $this->value = null;
        } else {
            $this->value = $this->style . ' fa-' . $this->name;
        }

        $this->emitValue();
    }

    public function searchName(): void
    {
        $data = $this->service->searchIcon($this->name);

        if (!empty($data) && $data['success'] === true) {
            $this->icons = $data['data'];
        }

        $this->updatedStyle();
    }

    public function instantiateService(): void
    {
        $this->service = new ScFontAwesome();

        if (!empty($this->value)) {
            $values = explode(' ', $this->value);

            if (!empty($values[0])) {
                $this->style = $values[0];
            }

            if (!empty($values[1])) {
                $name = explode('fa-', $values[1]);

                if (!empty($name[1])) {
                    $this->name = $name[1];
                } else {
                    $this->name = $values[1];
                }
            }
        }
    }
    public function init(): void
    {
        $api_token = config('square-ui.fontawesome_api_token');
        if($api_token == null) {
            $this->apiTokeIsEmpty = true;
        } else {
            $this->instantiateService();
        }

    }

    public function storeApiKey(): void
    {
        $this->validate(['api_token' => 'required']);
        SquareUi::handleFontawesome($this->api_token);

        $this->init();
    }

}