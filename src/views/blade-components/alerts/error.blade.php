@props([
    'message'
])


<div {{$attributes}} >
    <flux:callout color="red" inline>
        <div class="flex items-center gap-2">
            <flux:icon.x-circle variant="solid" class="text-red-700" />
            <flux:callout.heading>{{$message}}</flux:callout.heading>
        </div>
    </flux:callout>
</div>
