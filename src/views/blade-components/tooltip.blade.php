@props([
    'toggle',
    'content',
])
<div
    {{$attributes}}
    x-data="{open: false}"
    @keydown.escape.window="open = false"
>
    <div class="relative">
        @if($toggle instanceof \Illuminate\View\ComponentSlot)
                <span @click="open = true">
                    {{$toggle}}
                </span>
        @else
            <flux:input as="button" label="{{$toggle}}"  placeholder="{{$toggle}}" @click="open = true" />
        @endif

            @if($content instanceof \Illuminate\View\ComponentSlot)
                <dialog
                    x-ref="dialog"
                    x-show="open"
                    x-transition
                    @click.outside="open = false"
                    wire:ignore
                    wire:cloak
                    {{$content->attributes->merge(['class' => 'max-sm:max-h-full! max-sm:fixed! max-sm:inset-0!  block mt-1 self-start mx-0 min-w-24 z-50'])}}
                >
                    <flux:card>
                        {{$content}}
                    </flux:card>
                </dialog>
            @else
                <dialog
                    x-ref="dialog"
                    x-show="open"
                    x-transition
                    @click.outside="open = false"
                    wire:ignore
                    wire:cloak
                    class="max-sm:max-h-full! max-sm:fixed! max-sm:inset-0!  block mt-1 self-start mx-0 min-w-24 z-50"
                >
                    <flux:card>
                        {{$content}}
                    </flux:card>
                </dialog>
            @endif


    </div>
</div>
