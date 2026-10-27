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

    /**
     * De description wordt ge-escaped, tenzij $options['html'] === true (alleen voor vertrouwde HTML).
     */
    public function confirm(array $options = []): void
    {
        $this->dispatch('show-confirm-dialog', options: $options);
    }

    public function closeConfirm(): void
    {
        Flux::modal('confirm-dialog')->close();
    }

    /**
     * Zet $html alleen op true als $message vertrouwde HTML is (nooit gebruikersinvoer of exception-messages).
     */
    public function errorDialog(string $title, string $message, bool $html = false): void
    {
        $this->dispatch('show-error-dialog', options: ['title' => $title, 'description' => $message, 'html' => $html]);
    }

    /**
     * Zet $html alleen op true als $message vertrouwde HTML is (nooit gebruikersinvoer of exception-messages).
     */
    public function successDialog(string $title, string $message, bool $html = false): void
    {
        $this->dispatch('show-success-dialog', options: ['title' => $title, 'description' => $message, 'html' => $html]);
    }
}
