@props([
    'message'
])


<div {{$attributes }} >
    <flux:callout color="blue" inline>
        <div class="flex items-center gap-2">
            <flux:icon.information-circle variant="solid" class="text-blue-700" />
            <flux:callout.heading>{{$message}}</flux:callout.heading>
        </div>
    </flux:callout>
</div>
