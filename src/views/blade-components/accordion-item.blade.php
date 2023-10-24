<li class="relative border-b border-gray-200" >
    <button type="button" class="w-full px-8 py-6 text-left bg-slate-100" @click="selected !== {{ $index }} ? selected = {{ $index }} : selected = null">
        <div class="flex items-center justify-between">
            <span>
                {{ $title }}
            </span>
            <i class="fa" :class="selected == {{ $index }} ? 'fa-chevron-down' : 'fa-chevron-up'"></i>
        </div>
    </button>
    <div class="relative overflow-hidden transition-all duration-700 max-h-0" style="" x-ref="container{{ $index }}"
         x-bind:style="selected == {{ $index }} ? 'max-height: ' + ($refs.container{{ $index }}.scrollHeight == 0 ? '1000px' : $refs.container{{ $index }}.scrollHeight + 'px') : ''"
    >
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</li>
