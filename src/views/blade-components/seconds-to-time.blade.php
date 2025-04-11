<div>
    @if($compareToSeconds == null)
        @if($showAsBadge)
            <flux:badge :color="$color ?? 'default'"> {{$time}}</flux:badge>
        @else
            <span>{{ $time }}</span>
        @endif
    @else
        @if($showAsBadge)
            @if($compareToSeconds < $seconds)
                <flux:badge :color="$color ?? 'red'"> {{$time}}</flux:badge>
            @else
                <flux:badge :color="$color ?? 'green'"> {{$time}}</flux:badge>
            @endif
        @else
            <span>{{ $time }}</span>
        @endif
    @endif
</div>
