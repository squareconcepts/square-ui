<div class="relative" x-data="{open: false}">
    <div x-on:click="open = !open">{{ $triggerSlot }}</div>
    <div class="absolute left-0 right-0 mt-1 bg-white shadow rounded p-2 z-50" x-show="open" x-cloak
         @click.outside="open = false">
        <div class="">
            {{ $slot }}
        </div>
    </div>
</div>
