<?php

namespace Squareconcepts\SquareUi\Traits;

use Flux\Flux;

trait SquareUiActions
{
    public function notification(string $heading, ?string $text = null, int $duration = 5000, ?string $variant = null, string $position = 'top right'): void
    {
        Flux::toast($text ?? $heading, $text ? $heading : null, $duration, $variant, $position);
    }

    public function successNotification(string $heading, ?string $text = null, int $duration = 5000, string $position = 'top right'): void
    {
        Flux::toast($text ?? $heading, $text ? $heading : null, $duration, 'success', $position);
    }

    public function errorNotification(string $heading, ?string $text = null, int $duration = 5000, string $position = 'top right'): void
    {
        Flux::toast($text ?? $heading, $text ? $heading : null, $duration, 'danger', $position);
    }

    public function dialog(string $name): void
    {
        Flux::modal($name)->show();
    }

    public function closeDialog(string $name): void
    {
        Flux::modal($name)->close();
    }

    public function confirm(array $options = []): void
    {
        $this->dispatch('show-confirm-dialog', options: $options);
    }

    public function closeConfirm(): void
    {
        Flux::modal('confirm-dialog')->close();
    }

    public function errorDialog(string $title, string $message): void
    {
        $this->dispatch('show-error-dialog', options: ['title' => $title, 'description' => $message]);
    }

    public function successDialog(string $title, string $message): void
    {
        $this->dispatch('show-success-dialog', options: ['title' => $title, 'description' => $message]);
    }
}
