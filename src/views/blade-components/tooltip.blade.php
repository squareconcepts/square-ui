<span
    x-data="{ tooltip: false }"
    x-on:mouseover="tooltip = true"
    x-on:mouseleave="tooltip = false"
    class="ml-2 h-5 w-5 cursor-pointer relative">
    {{$slot}}
  <div x-show="tooltip"
       x-cloak
       @class(array_merge([
        'tooltip-content',
        'rounded-lg p-2 transform z-50 absolute',
        'text-sm text-white bg-black' => $style == 'dark',
        'text-sm bg-white shadow-lg' => $style == 'light',
        '-translate-y-8 left-[100%] ml-3' => $placement == 'right',
        'left-0 top-[100%] mt-3' => $placement == 'bottom',
        ], $classes))
  >
    <i @class([
                "fa-sharp fa-solid fa-caret-left fa-2xl absolute shadow-lg",
                'text-black' => $style == 'dark',
                'text-white' => $style == 'light',
                'top-[-2px] left-[10px] fa-rotate-90' => $placement == 'bottom',
                'top-[20px] left-[-8px]' => $placement == 'right',
      ])></i>
     {!! $message !!}
  </div>
</span>
