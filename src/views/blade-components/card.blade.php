<div class="box intro-y {{ ($fullHeight) ? 'h-full' : '' }} {{ $classes }}">
    @if(!empty($title))
        <div @class(['bg-slate-200 rounded-t flex items-center border-b border-slate-200/60 dark:border-darkmode-400 p-5', '!py-3 px-5' => $smallHeader ])>
            <strong class="font-medium text-small mr-auto">{{ $title }}</strong>
            {{ $header_slot }}
        </div>
    @endif
    <div @class(['p-5', '!p-2' => $small, 'border rounded-b' => $bordered, $slotClasses ])>
        {{ $slot }}
    </div>
</div>
