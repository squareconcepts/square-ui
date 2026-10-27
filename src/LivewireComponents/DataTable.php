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
use Squareconcepts\SquareUi\Traits\SquareUiActions;

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
    #[Locked]
    public Collection $results;
    public Collection $filteredResults;
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
        $this->refreshData($this->filteredResults);
    }

    public function reverseSort(): string
    {
        return $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function mount(): void
    {
        $this->loadData();
        $this->filteredResults = $this->results;
    }

    public function updated(): void
    {
        $this->applyFilters();
    }

    private function applyFilters(): void
    {
        $this->filteredResults = collect($this->results->filter(function ($result) {
            if (empty($this->searchString)) {
                return true;
            }

            foreach ($this->selectedColumns as $fieldName) {
                if (is_array($result)) {
                    if (is_array($result[$fieldName])) {
                        foreach ($result[$fieldName] as $string) {
                            if (str($string)->lower()->contains(strtolower($this->searchString))) {
                                return true;
                            }
                        }
                    } elseif (str($result[$fieldName])->lower()->contains(strtolower($this->searchString))) {
                        return true;
                    }
                } elseif ($result instanceof Model && str($result->$fieldName)->lower()->contains(strtolower($this->searchString))) {
                    return true;
                }
            }

            return false;
        }))->map(fn($item) => (object) $item);
    }

    public function getItemsProperty()
    {
        if (!$this->withPagination) {
            $col = collect($this->filteredResults);
            if ($this->sortBy !== '') {
                $col = $col->sortBy($this->sortBy, SORT_REGULAR, $this->sortDirection === 'desc');
            }
            return $col;
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

    public function deleteRow($row): void
    {
        $this->confirm([
            'title' => __('square-ui::square-ui.are_you_sure'),
            'description' => __('square-ui::square-ui.are_you_sure_description'),
            'acceptLabel' => __('square-ui::square-ui.yes'),
            'rejectLabel' => __('square-ui::square-ui.cancel'),
            'method' => 'deleteRowCallback',
            'params' => $row,
        ]);
    }

    public function deleteRowCallback($row): void
    {
        // Public, so callable from the browser: only rows this table was given, of a real
        // model, and only when the model's policy (if any) allows it.
        $id = is_array($row) ? ($row['id'] ?? null) : null;

        abort_if(
            $this->hideDelete
            || $id === null
            || ! is_string($this->model)
            || ! is_subclass_of($this->model, Model::class)
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
        $this->loadData();
        $this->filteredResults = $this->results;
    }

    #[On('deleteRecordBy')]
    public function deleteRecordBy(array $data): void
    {
        $field = $data['field'];
        $value = $data['value'];

        $this->results = collect($this->results->filter(fn($result) => $result[$field] !== $value));
        $this->applyFilters();
    }

    #[On('refreshDataTable')]
    public function refreshData($data, $routePrefix = null): void
    {
        $col = collect($data);

        if ($this->sortBy !== '') {
            $col = $col->sortBy($this->sortBy, SORT_REGULAR, $this->sortDirection === 'desc');
            $col = collect($col->values()->all());
        }

        $this->filteredResults = $col;
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
    }

    public function selectAllColumns(): void
    {
        $this->selectedColumns = $this->searchColumns;
    }

    public function deselectAllColumns(): void
    {
        $this->selectedColumns = [];
    }
}
