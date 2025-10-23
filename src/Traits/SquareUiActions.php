<?php

namespace Squareconcepts\SquareUi\Traits;

use Flux\Flux;

trait SquareUiActions
{
    public function notification($heading, $text = null, $duration = 5000, $variant = null, $position = "top right")
    {
        if (!empty($text)) {
            Flux::toast($text, $heading, $duration, $variant, $position);
        } else {
            Flux::toast($heading, null, $duration, $variant, $position);
        }
    }

    public function successNotification($heading, $text = null, $duration = 5000, $variant = "success", $position = "top right")
    {
        if (!empty($text)) {
            Flux::toast($text, $heading, $duration, $variant, $position);
        } else {
            Flux::toast($heading, null, $duration, $variant, $position);
        }
    }

    public function errorNotification($heading, $text = null, $duration = 5000, $variant = "danger", $position = "top right")
    {
        if (!empty($text)) {
            Flux::toast($text, $heading, $duration, $variant, $position);
        } else {
            Flux::toast($heading, null, $duration, $variant, $position);
        }
    }

    public function dialog($name)
    {
        Flux::modal($name)->show();
    }

    public function closeDialog($name)
    {
        Flux::modal($name)->close();
    }

    public function confirm(array $options = [])
    {
        $this->dispatch('show-confirm-dialog', options: $options);
    }

    public function closeConfirm()
    {
        Flux::modal('confirm-dialog')->close();
    }

    public function errorDialog($title, $message)
    {
        $this->dispatch('show-error-dialog', options: ['title' => $title, 'description' => $message]);
    }

    public function successDialog($title, $message)
    {
        $this->dispatch('show-success-dialog', options: ['title' => $title, 'description' => $message]);
    }
}
