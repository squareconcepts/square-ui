<?php

    namespace Squareconcepts\SquareUi\Traits;


    trait SquareUiModals
    {
        public function confirm( string $message, string $title = 'Confirm', $icon = 'question', $confirmButtonText = 'Ok', $cancelButtonText = 'Cancel' , $confirmButtonCallback = null, $cancelButtonCallback = null )
        {

            $this->js(<<<JS
                Swal.fire({
                    title: '$title',
                    text: '$message',
                    icon: '$icon',
                    showCancelButton: 1,
                    cancelButtonColor: '#ef4444',
                    confirmButtonColor: "#10b981",
                    confirmButtonText: '$confirmButtonText',
                    cancelButtonText: '$cancelButtonText'
                }).then((result) => {
                    if(result.isConfirmed && '$confirmButtonCallback' != null){
                        Livewire.dispatch('$confirmButtonCallback');
                    } else if('$cancelButtonCallback' != null) {
                        Livewire.dispatch('$cancelButtonCallback');
                    }
                });
            JS);
        }

        public function success( string $message, string $title, $confirmButtonText = 'Ok', $confirmButtonColor = '#3085d6' ): void
        {
            $this->showSimpleModal($message, $title, icon: 'success', confirmButtonText: $confirmButtonText, confirmButtonColor: $confirmButtonColor);
        }
        public function error( string $message, string $title, $confirmButtonText = 'Ok', $confirmButtonColor = '#3085d6' ): void
        {
            $this->showSimpleModal($message, $title, icon: 'error', confirmButtonText: $confirmButtonText, confirmButtonColor: $confirmButtonColor);
        }
        public function info( string $message, string $title, $confirmButtonText = 'Ok', $confirmButtonColor = '#3085d6' ): void
        {
            $this->showSimpleModal($message, $title, icon: 'info', confirmButtonText: $confirmButtonText, confirmButtonColor: $confirmButtonColor);
        }
        public function waring( string $message, string $title, $confirmButtonText = 'Ok', $confirmButtonColor = '#3085d6' ): void
        {
            $this->showSimpleModal($message, $title, icon: 'warning', confirmButtonText: $confirmButtonText, confirmButtonColor: $confirmButtonColor);
        }
        public function question( string $message, string $title, $confirmButtonText = 'Ok', $confirmButtonColor = '#3085d6' ): void
        {
            $this->showSimpleModal($message, $title, icon: 'question', confirmButtonText: $confirmButtonText, confirmButtonColor: $confirmButtonColor);
        }

        public function showSimpleModal( string $message, string $title = 'Confirm', string $icon = 'warning', $confirmButtonText = 'Ok', $confirmButtonColor = '#3085d6' ): void
        {

                $this->js('Swal.fire({
                title: "' . $title . '",
                text: "' . $message . '",
                icon: "' . $icon . '",
                confirmButtonColor: "' . $confirmButtonColor . '",
                confirmButtonText: "' . $confirmButtonText . '"
            })');

        }

    }
