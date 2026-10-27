<?php

namespace Squareconcepts\SquareUi\LivewireComponents;

use Flux\Flux;
use Illuminate\Support\Js;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Dialogs extends Component
{
    public string $title = '';
    public string $description = '';
    // Description wordt standaard ge-escaped; alleen bij expliciet 'html' => true wordt hij raw gerenderd.
    #[Locked]
    public bool $html = false;
    public string $rejectLabel = '';
    public string $acceptLabel = '';
    public string $method = '';
    public string $rejectMethod = '';
    public string $icon = '';
    public mixed $params = [];
    public mixed $rejectParams = [];
    // Component dat confirm() aanriep; bij 'self' => true gaan de events alleen daarheen.
    #[Locked]
    public ?string $sourceId = null;
    #[Locked]
    public bool $self = false;
    // Voorkomt dat accept/reject dubbel afgaan (bijv. knop + sluiten van de modal).
    #[Locked]
    public bool $pending = false;

    public function render()
    {
        return view('square-ui::livewire-components.dialogs');
    }

    public function showConfirmModal(array $data = []): void
    {
        $options = $data['options'] ?? [];

        if (empty($options)) {
            return;
        }

        $this->title = $options['title'] ?? __('square-ui::square-ui.modal.confirm');
        $this->description = $options['description'] ?? '';
        $this->html = ($options['html'] ?? false) === true;
        $this->acceptLabel = $options['accept']['label'] ?? $options['acceptLabel'] ?? __('square-ui::square-ui.yes');
        $this->rejectLabel = $options['reject']['label'] ?? $options['rejectLabel'] ?? __('square-ui::square-ui.cancel');
        $this->method = $options['accept']['method'] ?? $options['method'] ?? '';
        $this->params = $options['accept']['params'] ?? $options['params'] ?? [];
        $this->icon = $options['icon'] ?? '';
        $this->rejectMethod = $options['reject']['method'] ?? '';
        $this->rejectParams = $options['reject']['params'] ?? [];
        $this->sourceId = is_string($options['sourceId'] ?? null) ? $options['sourceId'] : null;
        $this->self = ($options['self'] ?? false) === true;
        $this->pending = true;

        Flux::modal('confirm-dialog')->show();
    }

    public function confirmClick(): void
    {
        if (!$this->pending) {
            return;
        }

        $this->dispatchCallback($this->method, $this->params);

        $this->dispatch('confirm-dialog-response', accepted: true);
        Flux::modal('confirm-dialog')->close();
        $this->reset();
    }

    public function rejectClick(): void
    {
        if (!$this->pending) {
            return;
        }

        $this->dispatchCallback($this->rejectMethod, $this->rejectParams);

        $this->dispatch('confirm-dialog-response', accepted: false);
        Flux::modal('confirm-dialog')->close();
        $this->reset();
    }

    private function dispatchCallback(string $method, mixed $params): void
    {
        if (empty($method)) {
            return;
        }

        // Params gaan als losse (positionele) argumenten naar de listener.
        $params = is_array($params) ? array_values($params) : [$params];

        if ($this->self && $this->sourceId !== null) {
            // Livewire kan server-side alleen op componentnaam targeten; via de browser
            // sturen we het event alleen naar het component dat confirm() aanriep.
            $this->js('window.Livewire.find(' . Js::from($this->sourceId) . ')?.$dispatchSelf(' . Js::from($method) . ', ' . Js::from($params) . ')');

            return;
        }

        $this->dispatch($method, ...$params);
    }

    public function showErrorDialog(array $data = []): void
    {
        $options = $data['options'] ?? [];

        if (empty($options['title']) || empty($options['description'])) {
            return;
        }

        $this->title = $options['title'];
        $this->description = $options['description'];
        $this->html = ($options['html'] ?? false) === true;
        Flux::modal('error-dialog')->show();
    }

    public function closeErrorDialog(): void
    {
        $this->dispatch('error-dialog-response');
        Flux::modal('error-dialog')->close();
        $this->reset();
    }

    public function showSuccessDialog(array $data = []): void
    {
        $options = $data['options'] ?? [];

        if (empty($options['title']) || empty($options['description'])) {
            return;
        }

        $this->title = $options['title'];
        $this->description = $options['description'];
        $this->html = ($options['html'] ?? false) === true;
        Flux::modal('success-dialog')->show();
    }

    public function closeSuccessDialog(): void
    {
        $this->dispatch('success-dialog-response');
        Flux::modal('success-dialog')->close();
        $this->reset();
    }
}
