<div class="bg-white rounded shadow-md hover:shadow-lg">
    <div class="p-5 h-full relative flex gap-4 justify-between items-center">
        <div>
            <div class="text-base text-slate-500 mt-1">{{ $label }}</div>
            <div class="text-3xl font-medium" style="color: {{ $valueColor }}">
                @if($isCurrency)
                    &euro; {{ number_format($value, 2, ',', '.') }}
                @else
                    {{ $value }}
                @endif
            </div>
        </div>
        <span class="bg-primary-500">
                <i class="{{ $icon }} fa-3x" style="color: {{ $iconColor }}"></i>
            </span>
    </div>
</div>
