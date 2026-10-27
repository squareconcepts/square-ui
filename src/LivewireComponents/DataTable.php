<?php

namespace Squareconcepts\SquareUi\LivewireComponents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Squareconcepts\SquareUi\Helpers\ButtonStyle;
use Squareconcepts\SquareUi\Traits\SquareUiActions;

/**
 * <livewire:square-ui::data-table>
 *
 * @deprecated 4.x Wordt verwijderd in 5.0. Gebruik <flux:table> (met paginatie/sortering in je eigen component) of de LivewireTable uit squareconcepts/laravel-square-core.
 *             Zie UPGRADE.md, "Deprecated in 4.x".
 */
class DataTable extends Component
{
    use SquareUiActions, WithPagination;

    public bool $hideActions = false;
    #[Locked]
    public bool $hideRead = false;
    #[Locked]
    public bool $hideEdit = false;
    #[Locked]
    public bool $hideDelete = false;
    public bool $hideToolbar = false;
    public int $perPage = 10;
    public int $currentPage = 1;
    public array $customButtons = [];
    public array $perPageOptions = [10, 25, 50];
    public array $columns = [];
    public array $selectedColumns = [];
    public array $searchColumns = [];
    public array $sortables = [];
    // Default collect() wordt in mount() gezet (property defaults mogen geen object zijn).
    #[Locked]
    public Collection $results;
    public Collection $filteredResults;
    // Ids die de parent bij mount() heeft meegegeven: alleen die mogen via deleteRowCallback weg.
    // refreshData() is een (vanuit de browser aan te roepen) listener en mag deze set dus niet uitbreiden.
    #[Locked]
    public array $deletableIds = [];
    public bool $withPagination = true;
    public string $sortBy = '';
    public string $sortDirection = 'desc';

    #[Url]
    public string $searchString = '';

    // Set by the parent; locked so the browser can't point the table at another model or URL.
    #[Locked]
    public $model;
    #[Locked]
    public $customViewAction;
    #[Locked]
    public string $routePrefix = '';

    public function sortColumns(string $field): void
    {
        if (!in_array($field, $this->sortables)) {
            return;
        }

        $this->sortDirection = $this->sortBy === $field ? $this->reverseSort() : 'desc';
        $this->sortBy = $field;
        $this->applyFilters();
    }

    public function reverseSort(): string
    {
        return $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function mount(): void
    {
        $this->results ??= collect();
        $this->deletableIds = $this->idsOf($this->results);
        $this->loadData();
        $this->applyFilters();
    }

    public function updated(string $property): void
    {
        // Nieuwe zoekterm of paginagrootte: terug naar pagina 1, anders kan je op een lege pagina belanden.
        if (in_array($property, ['searchString', 'perPage'])) {
            $this->currentPage = 1;
        }

        $this->applyFilters();
    }

    private function applyFilters(): void
    {
        $search = mb_strtolower(trim($this->searchString));

        $filtered = $this->results->filter(function ($result) use ($search) {
            if ($search === '') {
                return true;
            }

            foreach ($this->selectedColumns as $fieldName) {
                $value = data_get($result, $fieldName);

                foreach (is_iterable($value) ? $value : [$value] as $string) {
                    if ((is_scalar($string) || $string instanceof \Stringable)
                        && str_contains(mb_strtolower((string) $string), $search)) {
                        return true;
                    }
                }
            }

            return false;
        });

        $this->filteredResults = $this->sortCollection($filtered)->map(fn($item) => (object) $item);

        // Na verwijderen/filteren niet op een pagina blijven staan die niet meer bestaat.
        $lastPage = max(1, (int) ceil($this->filteredResults->count() / max(1, $this->perPage)));
        $this->currentPage = min(max(1, $this->currentPage), $lastPage);
    }

    private function sortCollection(Collection $collection): Collection
    {
        if ($this->sortBy !== '') {
            $collection = $collection->sortBy(fn ($item) => data_get($item, $this->sortBy), SORT_REGULAR, $this->sortDirection === 'desc');
        }

        return $collection->values();
    }

    private function idsOf(Collection $rows): array
    {
        return $rows
            ->map(fn ($row) => data_get($row, 'id'))
            ->filter(fn ($id) => is_scalar($id) && $id !== '')
            ->map(fn ($id) => (string) $id)
            ->values()
            ->all();
    }

    public function getItemsProperty()
    {
        if (!$this->withPagination) {
            // filteredResults is al gesorteerd in applyFilters().
            return collect($this->filteredResults);
        }

        return $this->paginate($this->filteredResults, $this->perPage, $this->currentPage);
    }

    public function paginate($items, int $perPage = 10, ?int $page = null, array $options = []): LengthAwarePaginator
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function render()
    {
        return view('square-ui::livewire-components.data-table');
    }

    public function previousPage(): void
    {
        $this->dispatch('pageChanged');
        $this->setPage(max($this->currentPage - 1, 1));
    }

    public function nextPage(): void
    {
        $this->dispatch('pageChanged');
        $this->setPage($this->currentPage + 1);
    }

    public function setPage($page): void
    {
        $this->dispatch('pageChanged');
        $this->currentPage = $page;
    }

    public function handleAction(string $event, $row): void
    {
        match ($event) {
            'editRow' => $this->redirect($this->routePrefix . '/edit/' . $row['id']),
            'deleteRow' => $this->deleteRow($row),
            'viewRow' => !empty($this->customViewAction)
                ? $this->dispatch($this->customViewAction, $row)
                : $this->redirect($this->routePrefix . '/' . $row['id']),
            default => $this->dispatch($event, $row),
        };
    }

    /**
     * Flux 2 button style for a custom button's 'color'. Older tables passed a variant name
     * ('positive', 'warning', 'info'); Flux 2 colours the primary variant instead
     * (variant="primary" color="green").
     *
     * @return array{variant: string, color: ?string}
     */
    public function buttonStyle(?string $color): array
    {
        return ButtonStyle::from($color);
    }

    public function deleteRow($row): void
    {
        $id = is_array($row) ? ($row['id'] ?? null) : null;

        if ($this->hideDelete || !is_scalar($id)) {
            return;
        }

        $this->confirm([
            'title' => __('square-ui::square-ui.are_you_sure'),
            'description' => __('square-ui::square-ui.are_you_sure_description'),
            'acceptLabel' => __('square-ui::square-ui.yes'),
            'rejectLabel' => __('square-ui::square-ui.cancel'),
            'method' => 'deleteRowCallback',
            // Params worden door Dialogs als losse argumenten doorgegeven, dus de id in een lijst.
            'params' => [$id],
            // Alleen deze tabel krijgt het antwoord, niet elke DataTable op de pagina.
            'self' => true,
        ]);
    }

    #[On('deleteRowCallback')]
    public function deleteRowCallback($row): void
    {
        // Public, so callable from the browser: only rows this table was given, of a real
        // model, and only when the model's policy (if any) allows it.
        // Accepteert de id (nieuw) of de hele rij als array (oude aanroepen).
        $id = is_array($row) ? ($row['id'] ?? null) : $row;

        abort_if(
            $this->hideDelete
            || ! is_scalar($id)
            || $id === ''
            || ! is_string($this->model)
            || ! is_subclass_of($this->model, Model::class)
            || ! in_array((string) $id, $this->deletableIds, true)
            || ! $this->results->contains(fn ($result) => (string) data_get($result, 'id') === (string) $id),
            403,
        );

        $model = $this->model::query()->findOrFail($id);

        if (Gate::getPolicyFor($model)) {
            Gate::authorize('delete', $model);
        }

        try {
            if (!$model->delete()) {
                $this->errorNotification(__('square-ui::square-ui.delete_failed'));
                return;
            }
        } catch (\Exception) {
            $this->errorNotification(__('square-ui::square-ui.delete_failed'));
            return;
        }

        $this->successNotification(__('square-ui::square-ui.delete_success'));
        $this->removeRows(fn ($result) => (string) data_get($result, 'id') === (string) $id);
    }

    private function removeRows(callable $matches): void
    {
        $this->results = $this->results->reject($matches)->values();
        $this->deletableIds = array_values(array_intersect($this->deletableIds, $this->idsOf($this->results)));
        $this->applyFilters();
    }

    #[On('deleteRecordBy')]
    public function deleteRecordBy(array $data): void
    {
        $field = $data['field'];
        $value = $data['value'];

        $this->removeRows(fn ($result) => data_get($result, $field) === $value);
    }

    #[On('refreshDataTable')]
    public function refreshData($data, $routePrefix = null): void
    {
        // $results bijwerken zodat een volgende zoekactie niet terugvalt op oude data. deletableIds
        // wordt bewust niet uitgebreid: deze listener is vanuit de browser aan te roepen.
        $this->results = collect($data)->values();
        $this->deletableIds = array_values(array_intersect($this->deletableIds, $this->idsOf($this->results)));
        $this->applyFilters();
    }

    public function loadData(): void
    {
        $this->searchColumns = array_keys($this->columns);
        $this->sortables = array_keys($this->columns);
        $this->selectedColumns = array_keys($this->columns);
    }

    public function selectColumn(string $value): void
    {
        $key = array_search($value, $this->selectedColumns);
        if ($key !== false) {
            array_splice($this->selectedColumns, $key, 1);
        } else {
            $this->selectedColumns[] = $value;
        }

        $this->applyFilters();
    }

    public function selectAllColumns(): void
    {
        $this->selectedColumns = $this->searchColumns;
        $this->applyFilters();
    }

    public function deselectAllColumns(): void
    {
        $this->selectedColumns = [];
        $this->applyFilters();
    }
}
