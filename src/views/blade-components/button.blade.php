<button {{ $attributes }}
        type="button"
        @class([
        "outline-none inline-flex justify-center items-center group transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-2 text-sm px-4 py-2",
        "ring-emerald-500 text-white bg-emerald-500 hover:bg-emerald-600 hover:ring-emerald-600" => $type == 'positive',
        "ring-red-500 text-white bg-red-500 hover:bg-red-600 hover:ring-red-600" => $type == 'negative',
        "text-slate-500 hover:bg-slate-100 ring-slate-200" => $type == 'flat',
        ])>
    <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
    {{ $label }}
</button>
