# Square UI — Upgrade Guide

Migratie van Laravel 11 + Livewire 3 naar **Laravel 13 + Livewire 4 + Flux UI 2**.

Deze package is zwaar gesaneerd: alles wat Flux UI zelf afhandelt is verwijderd, SweetAlert2 is weg, en de Livewire-syntax is gemoderniseerd.

---

## 0. TL;DR

```bash
composer update squareconcepts/square-ui
php artisan square-ui:upgrade-check
```

De scan-command geeft je per bestand + regelnummer welke onderdelen gemigreerd moeten worden. Werk de lijst bovenaan af tot er niks meer overblijft.

---

## 1. Composer

`composer.json` in je host applicatie:

```json
"require": {
    "php": "^8.3",
    "laravel/framework": "^12.0|^13.0",
    "livewire/livewire": "^4.0",
    "livewire/flux": "^2.0",
    "livewire/flux-pro": "^2.0",
    "squareconcepts/square-ui": "^X.Y"
}
```

```bash
composer update
```

> **Flux Pro** staat als `suggest`, niet als harde dependency. Nodig voor de datum/tijd-pickers van
> square-ui (`flux:calendar`, `flux:select variant="listbox"`) en voor de Flux-vervangers in sectie 5
> (`flux:date-picker`, `flux:time-picker`, `flux:file-upload`, `flux:autocomplete`, `flux:pillbox`, `flux:popover`).
>
> De `scripts` (`post-install-cmd` etc.) zijn uit de `composer.json` van de package gehaald: Composer
> voert scripts van een dependency nooit uit. Publiceer de vlaggen zelf: `php artisan vendor:publish --tag=square-ui-images`.

---

## 2. Scan de codebase

```bash
php artisan square-ui:upgrade-check
```

Optioneel extra paden:

```bash
php artisan square-ui:upgrade-check --path=packages/jouw-package/src
```

Exit code `0` = clean. Exit code `1` = er staan nog verwijderde onderdelen in de code.

---

## 3. Verwijderde onderdelen → Flux alternatieven

### 3.1 `<x-square-ui.dropdown>` → `<flux:dropdown>`

**Voor:**
```blade
<x-square-ui.dropdown right>
    <x-slot:triggerSlot>
        <flux:button>Menu</flux:button>
    </x-slot:triggerSlot>
    <a href="/profile">Profiel</a>
    <a href="/logout">Uitloggen</a>
</x-square-ui.dropdown>
```

**Na:**
```blade
<flux:dropdown align="end">
    <flux:button>Menu</flux:button>
    <flux:menu>
        <flux:menu.item href="/profile" icon="user">Profiel</flux:menu.item>
        <flux:menu.item href="/logout" icon="arrow-right-start-on-rectangle" variant="danger">
            Uitloggen
        </flux:menu.item>
    </flux:menu>
</flux:dropdown>
```

---

### 3.2 `<x-square-ui.alerts::*>` → `<flux:callout>`

**Voor:**
```blade
<x-square-ui.alerts::error>
    <x-slot:message>Er ging iets mis</x-slot:message>
</x-square-ui.alerts::error>
```

**Na:**
```blade
<flux:callout variant="danger" inline>
    <flux:callout.heading>Er ging iets mis</flux:callout.heading>
</flux:callout>
```

**Variant mapping:**

| Oud | Flux variant |
|---|---|
| `alerts::error` | `variant="danger"` |
| `alerts::success` | `variant="success"` |
| `alerts::warning` | `variant="warning"` |
| `alerts::info` | `variant="info"` |
| `alerts::secondary` | `color="zinc"` |

---

### 3.3 `<x-square-ui.color-picker>` → native HTML of Pickr

**Voor:**
```blade
<x-square-ui.color-picker label="Kleur" wire:model="color" />
```

**Na (basis):**
```blade
<flux:field>
    <flux:label>Kleur</flux:label>
    <input type="color" wire:model="color" class="h-10 w-20 rounded border border-zinc-200">
</flux:field>
```

**Na (gevorderd met swatches/opacity):** gebruik de bestaande `<x-square-ui::pickr>` component.

---

### 3.4 `SquareUiModals` trait → `SquareUiActions`

De `SquareUiModals` trait was SweetAlert2-gebaseerd en volledig verwijderd. Alle functionaliteit zit nu in `SquareUiActions` (Flux toast + Flux modal).

**Voor:**
```php
use Squareconcepts\SquareUi\Traits\SquareUiModals;

class UserForm extends Component
{
    use SquareUiModals;

    public function save(): void
    {
        $this->success('Opgeslagen!');
        $this->error('Validatie faalt', 'Foutmelding');
        $this->confirm('Weet je het zeker?', 'Bevestig', confirmButtonCallback: 'deleteUser');
    }
}
```

**Na:**
```php
use Squareconcepts\SquareUi\Traits\SquareUiActions;

class UserForm extends Component
{
    use SquareUiActions;

    public function save(): void
    {
        $this->successNotification('Opgeslagen!');
        $this->errorNotification('Foutmelding', 'Validatie faalt');
        $this->confirm([
            'title' => 'Bevestig',
            'description' => 'Weet je het zeker?',
            'method' => 'deleteUser',
        ]);
    }
}
```

**Method mapping:**

| Oud (`SquareUiModals`, SweetAlert2) | Nieuw (`SquareUiActions`, Flux) |
|---|---|
| `success($message, $title)` | `successNotification($heading, $text)` |
| `error($message, $title)` | `errorNotification($heading, $text)` |
| `info($message, $title)` | `notification($heading, $text, variant: null)` |
| `warning($message, $title)` | `notification($heading, $text, variant: 'warning')` |
| `successToast($message)` | `successNotification($message)` |
| `errorToast($message)` | `errorNotification($message)` |
| `confirm($message, $title, ..., confirmButtonCallback: 'foo')` | `confirm(['title' => $title, 'description' => $message, 'method' => 'foo'])` |

> ⚠️ **Let op:** parameter volgorde is omgedraaid. In Flux is `heading` eerst, `text` tweede. In SweetAlert was `message` eerst, `title` tweede.

Voor `confirm()` heb je ook de `<livewire:square-ui::dialogs />` component nodig ergens in je layout (meestal in de root `<body>`):

```blade
<livewire:square-ui::dialogs />
```

> Let op: gebruik `::` (namespace), niet `square-ui.dialogs` — die naam resolvet niet.
>
> `params` worden als losse argumenten aan de `method`-listener (`#[On('foo')]`) doorgegeven: `'params' => [$id]` geeft `foo($id)`. Met `'self' => true` gaat het event alleen naar het component dat `confirm()` aanriep, in plaats van naar elke listener op de pagina.

---

## 4. Livewire 3/4 syntax

### 4.1 `emit()` → `dispatch()`

**Voor:**
```php
$this->emit('userUpdated', $user->id);
$this->emitSelf('refresh');
$this->emitTo('user-list', 'refresh');
$this->emitUp('parent-event');
$this->dispatchBrowserEvent('open-modal', ['name' => 'foo']);
```

**Na:**
```php
$this->dispatch('userUpdated', userId: $user->id);
$this->dispatch('refresh');
$this->dispatch('refresh')->to('user-list');
$this->dispatch('parent-event');
$this->dispatch('open-modal', name: 'foo');
```

### 4.2 `wire:model.defer` → `wire:model`

In Livewire 3/4 is `wire:model` **standaard deferred**. Je hoeft `.defer` dus niet meer te schrijven.

```diff
- <input wire:model.defer="name">
+ <input wire:model="name">
```

Heb je ergens `wire:model` gebruikt en vertrouwde je op het oude **live** gedrag? Maak dat dan expliciet:

```diff
- <input wire:model="search">   {{-- was live in Livewire 2 --}}
+ <input wire:model.live="search">
```

### 4.3 `protected $listeners` → `#[On]` attribute

**Voor:**
```php
class UserList extends Component
{
    protected $listeners = [
        'userUpdated' => 'refreshUsers',
        'userDeleted',
    ];

    public function refreshUsers() { ... }
    public function userDeleted($id) { ... }
}
```

**Na:**
```php
use Livewire\Attributes\On;

class UserList extends Component
{
    #[On('userUpdated')]
    public function refreshUsers() { ... }

    #[On('userDeleted')]
    public function userDeleted($id) { ... }
}
```

---

## 5. Deprecated in 4.x, removed in 5.0

Deze onderdelen werken in 4.x nog gewoon, maar hebben een Flux-equivalent en verdwijnen in **5.0**.
Er komen geen runtime-meldingen (te veel ruis); `php artisan square-ui:upgrade-check` rapporteert ze als
🟡 *deprecated* (exit code blijft `0`).

> **Component-namen.** De class-componenten resolven alleen met `::`: `<x-square-ui::card>`,
> `<x-square-ui::inputs.datepicker>` of `<x-square-ui.inputs::datepicker>`. De punt-notatie
> (`<x-square-ui.card>`) bestaat alleen voor `tooltip`, `inputs.pin` en `session::*`.

| Deprecated (square-ui) | Vervanger (Flux) | Flux Pro? |
|---|---|---|
| `<x-square-ui.tooltip>` | `<flux:dropdown>` + `<flux:popover>`, of `<flux:tooltip>` voor een korte hint | popover: ja |
| `<x-square-ui.inputs.pin>` | `<flux:otp>` | nee |
| `<x-square-ui::inputs.datepicker>` | `<flux:date-picker>` (`with-presets` voor de periode-knoppen) | ja |
| `<x-square-ui::inputs.datetime-picker>` | `<flux:date-picker>` + `<flux:time-picker>` | ja |
| `<x-square-ui::inputs.time-picker>` | `<flux:time-picker>` | ja |
| `<x-square-ui::select-dropdown>` | `<flux:select variant="combobox">`, `<flux:autocomplete>`, `<flux:pillbox>` (meerdere waarden) | ja |
| `<x-square-ui::dropzone>` | `<flux:file-upload>` + `<flux:file-upload.dropzone>` | ja |
| `<x-square-ui::file-upload>` | `<flux:input type="file">` of `<flux:file-upload>` | file-upload: ja |
| `<x-square-ui::inputs.password>` | `<flux:input type="password" viewable>` + `Password::defaults()` | nee |
| `<x-square-ui.session::success\|error\|info\|warning>` | `Flux::toast()` of `<flux:callout>` | nee |
| `<x-square-ui::card>` | `<flux:card>` | nee |
| `<livewire:square-ui::data-table>` | `<flux:table>` of `LivewireTable` (squareconcepts/laravel-square-core) | nee |
| `PasswordChecker` (helper) | `Password::min(8)->uncompromised()` | n.v.t. |
| `TailwindHelper::tailwindToHex()` | CSS-variabele, bijv. `var(--color-primary-500)` | n.v.t. |

`PasswordChecker` en `TailwindHelper::tailwindToHex()` geven wél een `E_USER_DEPRECATED` (het zijn helpers,
geen componenten).

### 5.1 Tooltip → dropdown + popover

```blade
{{-- Voor --}}
<x-square-ui.tooltip>
    <x-slot:toggle><flux:button>Info</flux:button></x-slot:toggle>
    <x-slot:content>Uitgebreide uitleg…</x-slot:content>
</x-square-ui.tooltip>

{{-- Na --}}
<flux:dropdown>
    <flux:button>Info</flux:button>
    <flux:popover>Uitgebreide uitleg…</flux:popover>
</flux:dropdown>

{{-- Alleen een korte hint --}}
<flux:tooltip content="Uitgebreide uitleg">
    <flux:button icon="information-circle" />
</flux:tooltip>
```

### 5.2 Pin → OTP

```blade
<x-square-ui.inputs.pin wire:model="pin" length="6" label="Code" />
{{-- wordt --}}
<flux:otp wire:model="pin" length="6" label="Code" />
```

### 5.3 Datum- en tijdpickers

```blade
<x-square-ui::inputs.datepicker wire:model="deadline" label="Deadline" :enable-time="false" show-periods />
{{-- wordt --}}
<flux:date-picker wire:model="deadline" label="Deadline" with-presets />

<x-square-ui::inputs.datetime-picker wire:model="startsAt" label="Start" />
{{-- wordt --}}
<div class="flex gap-2">
    <flux:date-picker wire:model="startDate" label="Start" />
    <flux:time-picker wire:model="startTime" label="Tijd" />
</div>
```

> Flux bindt `Y-m-d` (datum) en `H:i` (tijd); de square-ui pickers bonden `d-m-Y H:i:s`. Combineer
> server-side, bijv. `Carbon::parse("{$this->startDate} {$this->startTime}")`.

### 5.4 Select-dropdown → combobox / autocomplete / pillbox

```blade
<x-square-ui::select-dropdown wire:model="userId" :items="$users" option-label="name" label="Gebruiker" />
{{-- wordt --}}
<flux:select variant="combobox" wire:model="userId" label="Gebruiker">
    @foreach ($users as $user)
        <flux:select.option :value="$user->id">{{ $user->name }}</flux:select.option>
    @endforeach
</flux:select>

{{-- multiple --}}
<flux:pillbox wire:model="userIds" label="Gebruikers">
    @foreach ($users as $user)
        <flux:pillbox.option :value="$user->id">{{ $user->name }}</flux:pillbox.option>
    @endforeach
</flux:pillbox>
```

`async-route` heeft geen 1-op-1 vervanger: laat het Livewire-component zelf filteren
(`<flux:select variant="combobox" :filter="false">` met `<flux:select.input wire:model.live="search" />`).

### 5.5 Dropzone / file-upload → Flux file-upload

```blade
<x-square-ui::dropzone identifier="docs" model="files" wire:model="files" multiple />
{{-- wordt (component gebruikt Livewire\WithFileUploads) --}}
<flux:file-upload wire:model="files" multiple label="Documenten">
    <flux:file-upload.dropzone heading="Sleep bestanden hierheen of klik om te bladeren" />
</flux:file-upload>
```

### 5.6 Password

```blade
<x-square-ui::inputs.password wire:model="password" label="Wachtwoord" />
{{-- wordt --}}
<flux:input type="password" wire:model="password" label="Wachtwoord" viewable />
```

```php
use Illuminate\Validation\Rules\Password;

'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
```

### 5.7 Session-messages → toast of callout

```php
// Voor: redirect()->with('success', 'Opgeslagen') + <x-square-ui.session::success name="success" />
Flux::toast(variant: 'success', text: 'Opgeslagen');
return redirect()->route('users.index');
```

```blade
@session('success')
    <flux:callout variant="success" icon="check-circle" :heading="$value" />
@endsession
```

| Oud | Flux |
|---|---|
| `session::success` | `variant="success"` |
| `session::error` | `variant="danger"` |
| `session::warning` | `variant="warning"` |
| `session::info` | `color="sky"` |

### 5.8 Card

```blade
<x-square-ui::card title="Klant">…</x-square-ui::card>
{{-- wordt --}}
<flux:card class="space-y-3">
    <flux:heading size="xl">Klant</flux:heading>
    <flux:separator />
    …
</flux:card>
```

### 5.9 DataTable

Voor CRUD-overzichten: maak een tabel op basis van `Squareconcepts\SquareCore\Helpers\LivewireTable`
(zie de README van laravel-square-core). Voor een eenvoudige lijst: `<flux:table>` in je eigen component:

```blade
<flux:table :paginate="$this->users">
    <flux:table.columns>
        <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Naam</flux:table.column>
    </flux:table.columns>
    <flux:table.rows>
        @foreach ($this->users as $user)
            <flux:table.row :key="$user->id">
                <flux:table.cell>{{ $user->name }}</flux:table.cell>
            </flux:table.row>
        @endforeach
    </flux:table.rows>
</flux:table>
```

### 5.10 Overige wijzigingen in 4.x

- **Vertalingen:** square-ui gebruikt geen app-keys meer (`buttons.close`, `forms.success`, `forms.error`,
  `report.period.*`) maar eigen keys onder `square-ui::square-ui.*` (nl + en). Eigen teksten? Publiceer met
  `php artisan vendor:publish --tag=square-ui-lang` en pas `lang/vendor/square-ui/{nl,en}/square-ui.php` aan.
- **View-namespace:** de views van square-ui werden ook onder `square-core::` geregistreerd. Dat is weg;
  gebruik `square-ui::`.
- **Config:** `upload_disk` en `ck_editor_license` zijn verwijderd (werden nergens gebruikt).
- **`ScFontAwesome::getStyles()`** is verwijderd (ongebruikt).
- **`PasswordChecker`** zet het wachtwoord niet meer in de Livewire-snapshot; na een request is `$password` leeg.
- **`ComponentAttributeBag::addClass()`** geeft nu daadwerkelijk de bag met de class terug (deed eerder niets).
- **Props die nu werken:** `<x-square-ui::file-upload label="…">` (+ `id`), `<x-square-ui::statistic-tile text-color="…">`,
  `<x-square-ui::card>` zonder `title` als je een `header_slot` meegeeft, `<x-square-ui::inputs.number>` met
  `wire:model="form.amount"` en een negatieve `min`.

---

## 6. Verificatie

1. Re-run de scan:
   ```bash
   php artisan square-ui:upgrade-check
   ```
   Je wil zien: `✓ Geen issues gevonden.`

2. Re-publish de assets (flag images etc.):
   ```bash
   php artisan vendor:publish --tag=square-ui-images --force
   ```

3. Clear caches:
   ```bash
   php artisan view:clear && php artisan cache:clear
   ```

4. Test de kritieke flows handmatig:
   - [ ] Livewire `DataTable` (zoek/sort/paginatie/delete)
   - [ ] Confirm-dialog bij delete actie
   - [ ] Flux toast bij success/error
   - [ ] Custom date/time/datetime pickers
   - [ ] Icon picker (als FontAwesome token staat)
   - [ ] Localized string component
   - [ ] Session-flash messages

---

## 7. Edge cases

- **Custom views die de verwijderde alerts hergebruiken:** de scanner detecteert `<x-square-ui.alerts::*>` in `resources/views/` en `app/`. Custom paden voeg je toe met `--path=...`.
- **JavaScript met `Swal.fire(...)`:** de scanner vindt deze in Blade-files. Ruwe `.js` bestanden worden niet gescand — check die handmatig.
- **Anonymous components binnen andere packages:** voeg hun pad toe via `--path=packages/...`.

---

## 8. Support

Problemen? Draai de scanner met de exacte output:

```bash
php artisan square-ui:upgrade-check -v
```

…en stuur de uitvoer door.
