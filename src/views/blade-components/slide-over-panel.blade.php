<div>
    @teleport('body')
        <div
            x-data="slideOverPanel('{{ $identifier }}')"
            x-on:open-{{$identifier}}.window="open"
            x-on:close-{{$identifier}}.window="close"
            @keyup.escape="close"
            id="slideover-{{ $identifier }}-container"
            class="w-full h-full fixed inset-0 invisible z-[101]">
            <div id="slideover-{{ $identifier }}-bg" class="w-full h-full duration-500 ease-out transition-all inset-0 absolute bg-gray-900 opacity-0"></div>
            <div x-on:click.outside="clickOutside(showing)" id="slideover-{{ $identifier }}"
                 @class(['bg-white h-full absolute right-0 duration-300 ease-out transition-all translate-x-full rounded-l-lg'])
                 style="width: {{ $width }}px"
            >
                <div x-on:click="close" class="absolute cursor-pointer text-gray-600 top-0 w-8 h-8 flex items-center justify-center right-0 mt-5 mr-5 z-[101]]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div class="p-6 border-b font-bold text-lg">
                    {{ $title  }}
                </div>
                <div class="p-5">
                    {{ $slot }}
                </div>
            </div>
        </div>
    @endteleport
</div>
