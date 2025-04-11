@props([
    'message'
])


<div {{$attributes }} >
    <flux:callout color="amber" inline>
        <div class="flex items-center gap-2">
            <flux:icon.exclamation-circle variant="solid" class="text-amber-700" />
            <flux:callout.heading>{{$message}}</flux:callout.heading>
        </div>
    </flux:callout>
</div>
