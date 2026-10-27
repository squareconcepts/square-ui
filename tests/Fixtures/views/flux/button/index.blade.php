{{--
    Test-stand-in voor flux:button. square-ui gebruikt de button-varianten 'positive' en 'warning'
    (en via DataTable::$customButtons willekeurige varianten). Die bestaan alleen in de flux:button-override
    van squareconcepts/laravel-square-core; de standaard Flux-button gooit "Unhandled match case".
--}}
@props([
    'variant' => null,
    'icon' => null,
    'iconTrailing' => null,
    'iconVariant' => null,
    'size' => null,
    'square' => null,
    'color' => null,
    'href' => null,
    'loading' => null,
    'kbd' => null,
    'tooltip' => null,
    'inset' => null,
    'as' => null,
    'type' => 'button',
])
<button type="{{ $type }}" data-variant="{{ $variant }}" {{ $attributes }}>{{ $slot }}</button>
