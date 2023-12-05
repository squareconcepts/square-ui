<div class="relative" x-data="{open: false}">
    <div x-on:click="open = !open">{{ $triggerSlot }}</div>
    <div class="absolute {{ $right ? 'right-0' : 'left-0' }} mt-1 bg-white shadow rounded p-2 z-50 {{ $slotClasses }}" x-show="open" x-cloak
         @click.outside="open = false">
        <div class="">
            {{ $slot }}
        </div>
    </div>
</div>
