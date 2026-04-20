<?php

namespace Squareconcepts\SquareUi\LivewireComponents;

use Livewire\Attributes\Modelable;
use Livewire\Component;
use Squareconcepts\SquareUi\Helpers\ScFontAwesome;
use Squareconcepts\SquareUi\SquareUi;

class IconPicker extends Component
{
    public ScFontAwesome $service;
    public array $icons = [];
    public string $api_token = '';

    #[Modelable]
    public mixed $value = null;

    public bool $apiTokenIsEmpty = false;
    public string $identifier = '';

    public function render()
    {
        return view('square-ui::livewire-components.icon-picker');
    }

    public function init(): void
    {
        $api_token = config('square-ui.fontawesome_api_token');

        if (empty($api_token)) {
            $this->apiTokenIsEmpty = true;
        } else {
            $this->service = new ScFontAwesome();
        }
    }

    public function storeApiKey(): void
    {
        $this->validate(['api_token' => 'required']);
        SquareUi::handleFontawesome($this->api_token);
        $this->init();
    }

    public function fetchResults(string $searchValue): array
    {
        if (empty($searchValue)) {
            return [];
        }

        $data = $this->service->searchIcon($searchValue);

        if (!empty($data) && $data['success'] === true) {
            $data['data'] = array_map(fn($item) => $item->toLivewire(), $data['data']);
            return $data;
        }

        return [];
    }
}
