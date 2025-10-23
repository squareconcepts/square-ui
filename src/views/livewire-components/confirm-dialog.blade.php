<div x-data x-on:show-confirm-dialog.window="$wire.showConfirmModal($event.detail)">
    <flux:modal name="confirm-dialog" class="min-w-[22rem]" :dismissible="false" @close="rejectClick">
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
                <flux:button type="submit" wire:click="confirmClick" x-on:keyup.enter.window="$wire.confirmClick()" variant="positive">{{$acceptLabel}}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
