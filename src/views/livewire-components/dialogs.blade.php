<div>
    <div x-data="{ isOpen: false }" x-on:show-confirm-dialog.window="isOpen = true; $wire.showConfirmModal($event.detail)">
        <flux:modal name="confirm-dialog" class="min-w-[22rem]" :dismissible="false" @close="isOpen = false; rejectClick">
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
                            <p>{{$description}}</p>
                        </flux:text>
                    @endif
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button type="submit" wire:click="rejectClick" variant="danger">{{$rejectLabel}}</flux:button>
                    <flux:button type="submit" wire:click="confirmClick" x-on:keyup.enter.window="if(isOpen) { $wire.confirmClick(); }" variant="positive">
                        {{$acceptLabel}}
                    </flux:button>
                    @foreach($extraButtons as $i => $extraButton)
                        <flux:button type="submit" wire:click="extraButtonClick('{{$i}}')" variant="{{$extraButton['variant']}}">
                            {{$extraButton['label']}}
                        </flux:button>
                    @endforeach

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
                            <p>{!! $description !!}</p>
                        </flux:text>
                    @endif
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button type="submit" wire:click="closeErrorDialog" variant="danger">{{ __('buttons.close') }}</flux:button>
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
                            <p>{!! $description !!}</p>
                        </flux:text>
                    @endif
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button type="submit" wire:click="closeSuccessDialog" variant="primary">{{ __('buttons.close') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</div>
