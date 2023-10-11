<button {{ $attributes }}
        type="button"
        @class([
        "outline-none inline-flex justify-center items-center group transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-2 text-sm px-4 py-2",
        "ring-emerald-500 text-white bg-emerald-500 hover:bg-emerald-600 hover:ring-emerald-600" => $type == 'positive',
        "ring-red-500 text-white bg-red-500 hover:bg-red-600 hover:ring-red-600" => $type == 'negative',
        "ring-orange-500 text-white bg-orange-500 hover:bg-orange-600 hover:ring-orange-600" => $type == 'warning',
        "ring-blue-500 text-white bg-blue-500 hover:bg-blue-600 hover:ring-blue-600" => $type == 'info',
        "text-slate-500 hover:bg-slate-100 ring-slate-200" => $type == 'flat',
        ])>
   @if($icon)
       <i class="fa fa-{{$icon}}"></i>
   @endif
    {{ $label ?? '' }}
</button>
