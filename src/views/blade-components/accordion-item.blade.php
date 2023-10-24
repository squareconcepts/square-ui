<li class="relative border-b border-gray-200" >
    <div class="w-full px-8 text-left bg-slate-100 hover:bg-slate-200" >
        <div class="flex items-center gap-2 cursor-pointer">
           @isset($buttons)
                <div class="flex items-center gap-2">
                    {!! $buttons !!}
                </div>
           @endif
            <div class="flex-1 flex items-center gap-2 cursor-pointer  py-6" @click="selected !== {{ $index }} ? selected = {{ $index }} : selected = null">
                <span >
                    {{ $title }}
                </span>
                <i class="ml-auto fa" :class="selected == {{ $index }} ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </div>

        </div>
    </div>
    <div class="relative overflow-hidden transition-all duration-700 max-h-0" style="" x-ref="container{{ $index }}"
         x-bind:style="selected == {{ $index }} ? 'max-height: ' + ($refs.container{{ $index }}.scrollHeight == 0 ? '1000px' : $refs.container{{ $index }}.scrollHeight + 'px') : ''"
    >
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</li>
