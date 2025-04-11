<flux:card {{$attributes}}>
    @if(!isset($header_slot))
        <flux:heading level="3" size="xl"> {{$title}}</flux:heading>
    @else
        {{$header_slot}}
    @endif
    <flux:separator class="my-3" />
    {{$slot}}
</flux:card>
