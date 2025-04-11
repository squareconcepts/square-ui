<flux:card class="flex items-center gap-4 overflow-x-auto">
       <div class="w-10 h-full">
           @if($icon instanceof \Illuminate\View\ComponentSlot)
               {{$icon}}
           @else
               <flux:icon :icon="$icon"  class="size-10 text-primary-500 {{$iconColor ?? 'text-primary-500'}}" variant="solid"/>
           @endif
       </div>
        <div class="flex-1 flex-col justify-center h-full">
            @if($title instanceof \Illuminate\View\ComponentSlot)
                {{$title}}
            @else
                <flux:heading level="3" size="xl" class="{{$textColor ?? 'text-primary-500'}}">{{$title}}</flux:heading>
            @endif
                @if($text instanceof \Illuminate\View\ComponentSlot)
                    {{$text}}
                @else
                    <flux:text level="3" size="xl" class="{{$textColor ?? 'text-primary-500'}}">{{$title}}</flux:text>
                @endif
        </div>
    @if($shoutOut)
        <div class="h-full">
            @if($shoutOut instanceof \Illuminate\View\ComponentSlot)
                {{$shoutOut}}
            @else
                <flux:text  class="text-4xl {{$textColor ?? 'text-primary-500'}}">{{$shoutOut}}</flux:text>
            @endif
        </div>
    @endif
</flux:card>
