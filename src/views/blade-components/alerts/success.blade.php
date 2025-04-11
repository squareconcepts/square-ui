@props([
    'message',
])


<div {{$attributes }} >
    <flux:callout color="green">
        <div class="flex items-center gap-2">
            <flux:icon.check-circle variant="solid" class="text-green-700" />
            <flux:callout.heading>{{$message}}</flux:callout.heading>
        </div>
    </flux:callout>
</div>
