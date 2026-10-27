<div>
    {{-- confirmPending: true zolang er nog niet gekozen is. Sluiten via Escape of het kruisje telt dan als annuleren;
         sluit de modal na een keuze (server-side close), dan gebeurt er niets meer. --}}
    <div x-data="{ confirmPending: false }" x-on:show-confirm-dialog.window="confirmPending = true; $wire.showConfirmModal($event.detail)">
        <flux:modal name="confirm-dialog" class="min-w-[22rem]" :dismissible="false" x-on:close="if (confirmPending) { confirmPending = false; $wire.rejectClick(); }">
            <div class="space-y-6">
                <div>
                    <div class="py-2 flex items-center justify-center">
                        @if($icon === 'success')
                            <flux:icon.check-circle variant="solid" class="text-sc-green size-12" />
                        @elseif($icon === 'error')
                            <flux:icon.exclamation-circle variant="solid" class="text-sc-red size-12" />
                        @elseif($icon === 'info')
                            <flux:icon.information-circle variant="solid" class="text-sc-blue size-12" />
                        @elseif($icon === 'question')
                            <flux:icon.question-mark-circle variant="solid" class="text-gray-500 size-12" />
                        @endif
                    </div>
                    <flux:heading size="xl">{{$title}}</flux:heading>
                    @if(!empty($description))
                        <flux:text class="mt-2">
                            @if($html)
                                <p>{!! $description !!}</p>
                            @else
                                <p>{{ $description }}</p>
                            @endif
                        </flux:text>
                    @endif
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button x-on:click="confirmPending = false; $wire.rejectClick()" variant="danger">{{$rejectLabel}}</flux:button>
                    {{-- autofocus: Enter bevestigt (native button-gedrag), zonder window-brede Enter-listener. --}}
                    <flux:button x-on:click="confirmPending = false; $wire.confirmClick()" autofocus variant="positive">
                        {{$acceptLabel}}
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
    <div  x-data x-on:show-error-dialog.window="$wire.showErrorDialog($event.detail);" >
        <flux:modal name="error-dialog" class="min-w-[22rem]" :dismissible="false" >
            <div class="space-y-6">
                <div>
                    <div class="py-2 flex items-center justify-center">
                        <flux:icon.exclamation-circle variant="solid" class="text-red-500 size-12" />
                    </div>
                    <flux:heading size="xl">{{$title}}</flux:heading>
                    @if(!empty($description))
                        <flux:text class="mt-2">
                            @if($html)
                                <p>{!! $description !!}</p>
                            @else
                                <p>{{ $description }}</p>
                            @endif
                        </flux:text>
                    @endif
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button type="submit" wire:click="closeErrorDialog" variant="danger">{{ __('square-ui::square-ui.close') }}</flux:button>
                    </flux:modal.close>

                </div>
            </div>
        </flux:modal>
    </div>
    <div x-data x-on:show-success-dialog.window="$wire.showSuccessDialog($event.detail)">
        <flux:modal name="success-dialog" class="min-w-[22rem]" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <div class="py-2 flex items-center justify-center">
                        <flux:icon.check-circle variant="solid" class="text-green-500 size-12" />
                    </div>
                    <flux:heading size="xl">{{$title}}</flux:heading>
                    @if(!empty($description))
                        <flux:text class="mt-2">
                            @if($html)
                                <p>{!! $description !!}</p>
                            @else
                                <p>{{ $description }}</p>
                            @endif
                        </flux:text>
                    @endif
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button type="submit" wire:click="closeSuccessDialog" variant="primary">{{ __('square-ui::square-ui.close') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</div>
