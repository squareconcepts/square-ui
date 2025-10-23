<?php

namespace Squareconcepts\SquareUi\LivewireComponents;

use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class Dialogs extends Component
{
    public string $title;
    public string $description;
    public string $rejectLabel;
    public string $acceptLabel;
    public string $method;
    public string $rejectMethod;
    public string $icon;
    public $params = [];
    public $rejectParams = [];

    public function render()
    {
        return view('square-ui::livewire-components.dialogs');
    }

    public function showConfirmModal($data = [])
    {
        $options = $data['options'] ?? [];
        if(count($options) == 0) {
            return;
        }
        if (!empty($options['title'])) {
            $this->title = $options['title'];
        } else {
            $this->title = __('square-ui::square-ui.modal.confirm');
        }

        if (!empty($options['description'])) {
            $this->description = $options['description'];
        } else {
            $this->description = '';
        }

        if (!empty($options['acceptLabel'])) {
            $this->acceptLabel = $options['acceptLabel'];
        } else {
            $this->acceptLabel = __('square-ui::square-ui.yes');
        }

        if (!empty($options['rejectLabel'])) {
            $this->rejectLabel = $options['rejectLabel'];
        } else {
            $this->rejectLabel = __('square-ui::square-ui.cancel');
        }

        if (!empty($options['method'])) {
            $this->method = $options['method'];
        }

        if (!empty($options['params'])) {
            $this->params = $options['params'];
        }

        if (!empty($options['icon'])) {
            $this->icon = $options['icon'];
        }

        if (!empty($options['accept'])) {
            $accept = $options['accept'];

            if (!empty($accept['label'])) {
                $this->acceptLabel = $accept['label'];
            }

            if (!empty($accept['method'])) {
                $this->method = $accept['method'];
            }

            if (!empty($accept['params'])) {
                $this->params = $accept['params'];
            }
        }

        if (!empty($options['reject'])) {
            $reject = $options['reject'];

            if (!empty($reject['label'])) {
                $this->rejectLabel = $reject['label'];
            }

            if (!empty($reject['method'])) {
                $this->rejectMethod = $reject['method'];
            }

            if (!empty($reject['params'])) {
                $this->rejectParams = $reject['params'];
            }
        }

        Flux::modal('confirm-dialog')->show();
    }

    public function confirmClick()
    {
        if (!empty($this->method)) {
            if (isset($this->params)) {
                if (is_array($this->params)) {
                    $this->dispatch($this->method, ...array_values($this->params));
                } else {
                    $this->dispatch($this->method, $this->params);
                }
            } else {
                $this->dispatch($this->method);
            }
        }
        $this->dispatch('confirm-dialog-response', accepted: true);
        Flux::modal('confirm-dialog')->close();
        $this->reset();
    }

    public function rejectClick() {
        if (!empty($this->rejectMethod)) {
            if (isset($this->rejectParams)) {
                if (is_array($this->rejectParams)) {
                    $this->dispatch($this->rejectMethod, ...array_values($this->rejectParams));
                } else {
                    $this->dispatch($this->rejectMethod, $this->rejectParams);
                }
            } else {
                $this->dispatch($this->rejectMethod);
            }
        }
        $this->dispatch('confirm-dialog-response', accepted: false);
        Flux::modal('confirm-dialog')->close();
        $this->reset();
    }


    public function showErrorDialog($data = [])
    {
        $options = $data['options'] ?? [];
        if(count($options) == 0 || empty($options['title']) || empty($options['description'])) {
            return;
        }
        $this->title = $options['title'];
        $this->description = $options['description'];
        Flux::modal('error-dialog')->show();
    }

    public function closeErrorDialog()
    {
        $this->dispatch('error-dialog-response');
        Flux::modal('error-dialog')->close();
        $this->reset();
    }


    public function showSuccessDialog($data = [])
    {
        $options = $data['options'] ?? [];
        if(count($options) == 0 || empty($options['title']) || empty($options['description'])) {
            return;
        }
        $this->title = $options['title'];
        $this->description = $options['description'];
        Flux::modal('success-dialog')->show();
    }

    public function closeSuccessDialog()
    {
        $this->dispatch('success-dialog-response');
        Flux::modal('success-dialog')->close();
        $this->reset();
    }
}
