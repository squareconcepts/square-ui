<?php

namespace Squareconcepts\SquareUi\LivewireComponents;

use Flux\Flux;
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

        Flux::modal('confirm-dialog')->show();
    }

    public function confirmClick(): void
    {
        if (!empty($this->method)) {
            is_array($this->params)
                ? $this->dispatch($this->method, ...array_values($this->params))
                : $this->dispatch($this->method, $this->params);
        }

        $this->dispatch('confirm-dialog-response', accepted: true);
        Flux::modal('confirm-dialog')->close();
        $this->reset();
    }

    public function rejectClick(): void
    {
        if (!empty($this->rejectMethod)) {
            is_array($this->rejectParams)
                ? $this->dispatch($this->rejectMethod, ...array_values($this->rejectParams))
                : $this->dispatch($this->rejectMethod, $this->rejectParams);
        }

        $this->dispatch('confirm-dialog-response', accepted: false);
        Flux::modal('confirm-dialog')->close();
        $this->reset();
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
