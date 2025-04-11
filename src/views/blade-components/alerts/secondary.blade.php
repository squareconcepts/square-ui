@props([
    'message'
])


<div {{$attributes }} >
    <flux:callout variant="secondary" icon="information-circle" heading="{{$message}}" />
</div>
