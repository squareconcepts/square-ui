<div class="box intro-y {{ ($fullHeight) ? 'h-full' : '' }} {{ $classes }}" x-data="{titleBackgroundColor: @js($titleBackgroundColor), titleTextColor: @js($titleTextColor)}">
    @if(!empty($title))
        <div @class([' rounded-t flex items-center border-b  p-5', '!py-3 px-5' => $smallHeader ]) x-bind:class="'bg-' +titleBackgroundColor + ' ' + 'border-' + titleBackgroundColor + '/60' + ' text-' + titleTextColor ">
            <strong class="font-medium text-small mr-auto">{{ $title }}</strong>
            {{ $header_slot }}
        </div>
    @endif
    <div @class(['p-5 bg-white', '!p-2' => $small, 'border rounded-b' => $bordered, $slotClasses ])>
        {{ $slot }}
    </div>
</div>
