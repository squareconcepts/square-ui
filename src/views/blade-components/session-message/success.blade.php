@props([
    'name',
    'title'=> null,
    'timeoutInSeconds' => null
])
@php
    if (!is_null($timeoutInSeconds) && !is_int($timeoutInSeconds) ) {
        throw new \InvalidArgumentException('The "timeoutInSeconds" prop must be an integer or null.');
    }
      $title ??= __('forms.success');
@endphp
<x-square-ui.session::base name="{{$name}}" :timeout-in-seconds="$timeoutInSeconds">
    <flux:callout icon="check" variant="success" class="relative overflow-hidden">
        <flux:callout.heading>{{$title}}</flux:callout.heading>
        <flux:callout.text>  {{ session($name) }}</flux:callout.text>
        <x-slot name="controls">
            <flux:button icon="x-mark" variant="ghost" x-on:click="visible = false" />
        </x-slot>
        <div class="w-full absolute left-0 right-0 bottom-0 h-1 rounded">
            <div class="h-1 bg-green-800 transition-all rounded" :style="{ width: progress + '%' }"></div>
        </div>
    </flux:callout>
</x-square-ui.session::base>

