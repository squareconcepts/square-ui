<li class="relative border-b border-gray-200"  {{$attributes}}>
    <div class="w-full px-8 text-left bg-slate-100 hover:bg-slate-200" >
        <div class="flex items-center gap-2 cursor-pointer">
            @isset($buttons)
                <div class="flex items-center gap-1">
                    {!! $buttons !!}
                </div>
            @endif
            <div class="flex-1 flex items-center gap-2 cursor-pointer  py-6" @click="selected !== {{ $index }} ? selected = {{ $index }} : selected = null">
                <span class="flex-1" >
                    {{ $title }}
                </span>
                @isset($rightButtons)
                    <div class="flex items-center gap-1">
                        {!! $rightButtons !!}
                    </div>
                @endif
                <i class="ml-auto fa" :class="selected == {{ $index }} ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </div>

        </div>
    </div>
    <div style="" x-ref="container{{ $index }}" x-cloak
         x-show="selected == {{ $index }}"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
        {{--         x-bind:style="selected == {{ $index }} ? 'max-height: 10000px': ''"--}}
    >
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</li>
