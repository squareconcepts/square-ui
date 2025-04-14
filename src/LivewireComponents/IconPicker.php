<?php

namespace Squareconcepts\SquareUi\LivewireComponents;

use Livewire\Attributes\Modelable;
use Livewire\Component;
use Squareconcepts\SquareUi\Helpers\ScFontAwesome;
use Squareconcepts\SquareUi\SquareUi;

class IconPicker extends Component
{

    public ScFontAwesome $service;
    public $icons = [];
    public $api_token = '';

    #[Modelable]
    public  $value;
    public bool $apiTokenIsEmpty = false;

    public string $identifier = '';

    protected array $rules = [
        'icons' => 'nullable',
        'icon' => 'value',
        'api_token' => 'nullable'
    ];

    public function render()
    {
        return view('square-ui::livewire-components.icon-picker');
    }


    public function instantiateService(): void
    {
        $this->service = new ScFontAwesome();

    }
    public function init(): void
    {
        $api_token = config('square-ui.fontawesome_api_token');

        if($api_token == null) {
            $this->apiTokenIsEmpty = true;
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

    public function fetchResults($searchValue  ): array
    {
        if(!empty($searchValue)) {
            $data = $this->service->searchIcon($searchValue);
            if (!empty($data) && $data['success'] === true) {
                $data['data'] = array_map(function ( $item) {
                      return $item->toLivewire();
                }, $data['data']);
                return $data;
            }
        }
       return [];
    }
}
