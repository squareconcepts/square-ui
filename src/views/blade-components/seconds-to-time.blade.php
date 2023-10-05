<div>
    @if($compareToSeconds == null)
        @if($showAsBadge)
            <x-badge flat secondary label="{{$time}}"/>
        @else
            <span>{{ $time }}</span>
        @endif
    @else
        @if($showAsBadge)
            @if($compareToSeconds < $seconds)
                <x-badge flat negative label="{{$time}}"/>
            @else
                <x-badge flat positive label="{{$time}}"/>
            @endif
        @else
            <span>{{ $time }}</span>
        @endif
    @endif
</div>
