## Installation

You can install the package via composer:

```bash
composer require squareconcepts/square-ui
php artisan vendor:publish --tag=square-ui-images   # flag SVGs used by <x-square-ui::flag>
```

Requirements: PHP 8.3+, Laravel 12 or 13, Livewire 4, Flux 2.

### Flux Pro

`livewire/flux-pro` is a *suggested* dependency, not a required one. You need it for:

- the square-ui date/time pickers (`<x-square-ui::inputs.datepicker>`, `…datetime-picker>`, `…time-picker>`),
  which use `flux:calendar` and `flux:select variant="listbox"`;
- most Flux replacements of the deprecated components (`flux:date-picker`, `flux:time-picker`,
  `flux:file-upload`, `flux:autocomplete`, `flux:pillbox`, `flux:popover`).

Everything else (dialogs, data table, flag, icon picker, localized string, …) only needs the free `livewire/flux`.

### Add these to the page

```html
<flux:toast.group position="top end"><flux:toast/></flux:toast.group>
<livewire:square-ui::dialogs />
```

### Translations

All strings live under `square-ui::square-ui.*` (`nl` and `en`). To change them:

```bash
php artisan vendor:publish --tag=square-ui-lang
```

### Deprecated components

Several components duplicate Flux and will be removed in 5.0 (tooltip, pin, date/time pickers,
select-dropdown, dropzone, file-upload, password, session messages, card and the Livewire DataTable).
See [UPGRADE.md](UPGRADE.md#5-deprecated-in-4x-removed-in-50) and run:

```bash
php artisan square-ui:upgrade-check
```

## Testing

```bash
composer install
vendor/bin/phpunit
```

The test suite runs on Orchestra Testbench with the free `livewire/flux`; it does not need a Flux Pro license.

## Credits

-   [Square Concepts](https://github.com/squareconcepts)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
