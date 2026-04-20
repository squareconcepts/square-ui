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
    "laravel/framework": "^13.0",
    "livewire/livewire": "^4.0",
    "livewire/flux": "^2.0",
    "squareconcepts/square-ui": "^X.Y"
}
```

```bash
composer update
```

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

**Na (gevorderd met swatches/opacity):** gebruik de bestaande `<x-square-ui.pickr>` component.

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

## 5. Verificatie

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

## 6. Edge cases

- **Custom views die de verwijderde alerts hergebruiken:** de scanner detecteert `<x-square-ui.alerts::*>` in `resources/views/` en `app/`. Custom paden voeg je toe met `--path=...`.
- **JavaScript met `Swal.fire(...)`:** de scanner vindt deze in Blade-files. Ruwe `.js` bestanden worden niet gescand — check die handmatig.
- **Anonymous components binnen andere packages:** voeg hun pad toe via `--path=packages/...`.

---

## 7. Support

Problemen? Draai de scanner met de exacte output:

```bash
php artisan square-ui:upgrade-check -v
```

…en stuur de uitvoer door.
