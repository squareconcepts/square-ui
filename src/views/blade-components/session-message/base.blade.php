@props([
    'name',
    'title'=> null,
    'timeoutInSeconds' => null
])
{{-- @deprecated 4.x, removed in 5.0: use Flux::toast() or <flux:callout>. See UPGRADE.md. --}}
@php
    if (!is_null($timeoutInSeconds) && !is_int($timeoutInSeconds) ) {
        throw new \InvalidArgumentException('The "timeoutInSeconds" prop must be an integer or null.');
    }

    $title ??= __('square-ui::square-ui.session.success');
@endphp
@session($name)
<div x-data="{
        visible: true,
        progress: 0,
        timeout: @js($timeoutInSeconds),
        hovered: false,
        init() {
            if(this.timeout != null) {
                let interval = setInterval(() => {
                   if (!this.hovered) {
                    this.progress += 100 / (this.timeout * 10);

                    if (this.progress >= 100) {
                        clearInterval(interval);
                        this.visible = false;
                    }
                }
                }, 100);
            }
        }
    }"
     x-show="visible"
     x-collapse
     @mouseenter="hovered = true"
     @mouseleave="hovered = false"
     class="my-3"
>
        {{$slot}}
</div>
@endsession


