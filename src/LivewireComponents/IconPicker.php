<?php

namespace Squareconcepts\SquareUi\LivewireComponents;

use Livewire\Attributes\Modelable;
use Livewire\Component;
use Squareconcepts\SquareUi\Helpers\ScFontAwesome;

class IconPicker extends Component
{
    public array $icons = [];

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
        $this->apiTokenIsEmpty = empty(config('square-ui.fontawesome_api_token'));
    }

    public function fetchResults(string $searchValue): array
    {
        $service = $this->service();

        if (empty($searchValue) || $service === null) {
            return [];
        }

        $data = $service->searchIcon($searchValue);

        if (!empty($data) && $data['success'] === true) {
            $data['data'] = array_map(fn($item) => $item->toLivewire(), $data['data']);
            return $data;
        }

        return [];
    }

    // Bewust geen public property: de service (en het access token) mag niet in de Livewire snapshot terechtkomen.
    protected function service(): ?ScFontAwesome
    {
        if (empty(config('square-ui.fontawesome_api_token'))) {
            return null;
        }

        return new ScFontAwesome();
    }
}
