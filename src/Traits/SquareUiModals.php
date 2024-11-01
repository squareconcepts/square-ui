<?php

    namespace Squareconcepts\SquareUi\Traits;

    trait SquareUiModals
    {

        public function getListeners(  ): array
        {
            if( !isset($this->listeners) || (is_array($this->listeners) && count($this->listeners) == 0) ) {
                return [ 'confirmCalled'];
            } else {
                return array_merge( $this->listeners, [ 'confirmCalled'] );
            }
        }
        public function confirm( string $message, ?string $title = null, $icon = 'question', $confirmButtonText = 'Ok', $cancelButtonText = 'Cancel' , $cancelButtonCallback = null, $params = null, $cancelButtonsColor =   '#ef4444', $confirmButtonColor =   "#10b981", $allowClickOutside = false, $allowEscape = false)
        {
            $title = $title ?? __('square-ui::square-ui.modal.confirm');
            $serialized = serialize($params);

            $this->js(<<<JS
                Swal.fire({
                    title: '$title',
                    text: '$message',
                    icon: '$icon',
                    showCancelButton: 1,
                    cancelButtonColor: '$cancelButtonsColor',
                    confirmButtonColor:  '$confirmButtonColor',
                    inputValue: '$params',
                    confirmButtonText: '$confirmButtonText',
                    cancelButtonText: '$cancelButtonText',
                    allowOutsideClick:'$allowClickOutside',
                    allowEscapeKey: '$allowEscape',
                }).then((result) => {
                   if(result.isConfirmed ){
                        Livewire.dispatch('confirmCalled', {params: '$serialized'});
                    } else if('$cancelButtonCallback' != null && event.key !== 'Escape') {
                        Livewire.dispatch('$cancelButtonCallback',  {params: '$serialized'});
                    }
                });
            JS);

            return $this;
        }

        public function success( string $message, ?string $title = null, $confirmButtonText = 'Ok', $confirmButtonColor = '#3085d6' ): static
        {
            $title = $title ?? __('square-ui::square-ui.modal.success');
            $this->showSimpleModal($message, $title, icon: 'success', confirmButtonText: $confirmButtonText, confirmButtonColor: $confirmButtonColor);
            return $this;
        }
        public function error( string $message, ?string $title = null, $confirmButtonText = 'Ok', $confirmButtonColor = '#3085d6' ): void
        {
            $title = $title ?? __('square-ui::square-ui.modal.error');
            $this->showSimpleModal($message, $title, icon: 'error', confirmButtonText: $confirmButtonText, confirmButtonColor: $confirmButtonColor);
        }
        public function info( string $message, ?string $title = null, $confirmButtonText = 'Ok', $confirmButtonColor = '#3085d6' ): void
        {
            $title = $title ?? __('square-ui::square-ui.modal.info');
            $this->showSimpleModal($message, $title, icon: 'info', confirmButtonText: $confirmButtonText, confirmButtonColor: $confirmButtonColor);
        }
        public function warning( string $message, ?string $title = null, $confirmButtonText = 'Ok', $confirmButtonColor = '#3085d6' ): void
        {
            $title = $title ?? __('square-ui::square-ui.modal.warning');
            $this->showSimpleModal($message, $title, icon: 'warning', confirmButtonText: $confirmButtonText, confirmButtonColor: $confirmButtonColor);
        }
        public function question( string $message, ?string $title = null, $confirmButtonText = 'Ok', $confirmButtonColor = '#3085d6' ): void
        {
            $title = $title ?? __('square-ui::square-ui.modal.question');
            $this->showSimpleModal($message, $title, icon: 'question', confirmButtonText: $confirmButtonText, confirmButtonColor: $confirmButtonColor);
        }

        public function showSimpleModal( string $message, ?string $title = null, string $icon = 'success', string $confirmButtonText = 'Ok', string $confirmButtonColor = '#3085d6' ): void
        {
            $title = $title ?? __('square-ui::square-ui.modal.confirm');

            $this->js('Swal.fire({
                title: "' . $title . '",
                text: "' . $message . '",
                icon: "' . $icon . '",
                confirmButtonColor: "' . $confirmButtonColor . '",
                confirmButtonText: "' . $confirmButtonText . '"
            })');

        }

        public function handleConfirmed($params)
        {
            if(!blank($params)){
                info('Confirm modal returned params but are ignored. Please add the handleConfirmed method to your component. This method should accept $params as a parameter.');
            }
        }

        public function confirmCalled($params)
        {
            $params = unserialize($params);

            $this->handleConfirmed($params);

        }

    }
