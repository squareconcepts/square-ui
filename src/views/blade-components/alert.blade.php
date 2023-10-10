<div>
    <div @class([
        'alert p-2 show flex items-center my-2 border rounded shadow hover:shadow-md',
        'bg-blue-300 border-blue-300' => $color == 'blue',
        'bg-red-300 border-red-300' => $color == 'red',
        'bg-orange-300 border-orange-300' => $color == 'orange',
        'bg-black border-black text-white' => $color == 'black',
        'bg-green-300 border-green-300' => $color == 'green',
        ]) role="alert">
        @if($color == 'red')
            <i class="fa-thin fa-{{$icon}} fa-2x mr-4"></i><span>{{ $message ?? $slot}}</span>
        @elseif($color == 'orange')
            <i class="fa-thin fa-{{$icon}} fa-2x mr-4"></i><span>{{ $message ?? $slot}}</span>
        @elseif($color == 'blue')
            <i class="fa-thin fa-{{$icon}} fa-2x mr-4"></i><span>{{ $message ?? $slot}}</span>
        @elseif($color == 'black')
            <i class="fa-thin fa-{{$icon}} fa-2x mr-4"></i><span>{{ $message ?? $slot}}</span>
        @elseif($color == 'green')
            <i class="fa-thin fa-{{$icon}} fa-2x mr-4"></i><span>{{ $message ?? $slot }}</span>
        @endif
    </div>
</div>
