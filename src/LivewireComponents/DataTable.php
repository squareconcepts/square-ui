<?php

namespace Squareconcepts\SquareUi\LivewireComponents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Squareconcepts\SquareUi\Traits\SquareUiModals;

class DataTable extends Component
{
    use SquareUiModals, WithPagination;

    public bool $hasPagination = true;
    public bool $hideActions = false;
    public bool $hideRead = false;
    public bool $hideEdit = false;
    public bool $hideDelete = false;
    public bool $hideToolbar = false;
    public int $perPage = 10;
    public int $currentPage = 1;
    public int $lastPage = 1;
    public array $customButtons = [];
    public array $perPageOptions = [10, 25, 50];
    public array $columns = [];
    public array $selectedColumns = [];
    public array $searchColumns = [];
    public array $sortables = [];
    public Collection $results;
    public Collection $filteredResults;
    public bool $withPagination = true;
    public string $sortBy = '';
    public string $sortDirection = 'desc';
    #[Url]
    public string $searchString = '';
    public $model;
    public $customViewAction;

    public string $routePrefix = '';

    protected $listeners = [
        'deleteRecordBy',
        'refreshData'
    ];

    public function sortColumns($field)
    {
        if(in_array($field, $this->sortables)) {
            $this->sortDirection = $this->sortBy === $field
                ? $this->reverseSort()
                : 'desc';

            $this->sortBy = $field;
            $this->refreshData($this->filteredResults);
        }
    }

    public function reverseSort(): string
    {
        return $this->sortDirection === 'asc'
            ? 'desc'
            : 'asc';
    }

    public function mount()
    {
        if(!empty($_GET['page']) && $this->currentPage != $_GET['page']) {
            $this->currentPage = $_GET['page'];
        }
        $this->loadData();

        $this->filteredResults = $this->results;
    }

    protected function rules()
    {
        return [
            'sortBy' => 'string',
            'sortDirection' => 'string',
            'searchString' => 'string'
        ];
    }

    public function dehydrate()
    {
        if(!empty($_GET['page'])) {
            if($this->currentPage != $_GET['page']) {
                $this->currentPage = $_GET['page'];
            }
        } else if($this->currentPage != 1) {
            $this->currentPage = 1;
        }
    }

    public function updated()
    {
        $this->validate();
        $this->applyFilters();
    }

    private function applyFilters(): void
    {
        $this->filteredResults = collect($this->results->filter(function($result) {
            if (empty($this->searchString)) {
                return true;
            }

            $matches = false;

            foreach($this->selectedColumns as $fieldName) {
                if (is_array($result)) {
                    if (is_array($result[$fieldName])) {
                        foreach ($result[$fieldName] as $string) {
                            if (str($string)->lower()->contains(strtolower($this->searchString))) {
                                $matches = true;
                                break 2;
                            }
                        }
                    } else {
                        if (str($result[$fieldName])->lower()->contains(strtolower($this->searchString))) {
                            $matches = true;
                            break;
                        }
                    }
                } else if ($result instanceof Model) {
                    if (str($result->$fieldName)->lower()->contains(strtolower($this->searchString))) {
                        $matches = true;
                        break;
                    }
                }
            }

            return $matches;
        }))->map(function ($item) {
            return (object) $item;
        });
    }

    public function getItemsProperty()
    {
        if(!$this->withPagination) {
            $col = collect($this->filteredResults);
            if($this->sortBy != '') {
                $col = $col->sortBy($this->sortBy, SORT_REGULAR, $this->sortDirection == 'desc');
            }
            return $col;
        }

        return $this->paginate($this->filteredResults, $this->perPage, $this->currentPage);
    }

    public function paginate($items, $perPage = 10, $page = null, $options = []): LengthAwarePaginator
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

    public function handleAction($event, $row): void
    {
        if ($event == 'editRow') {
            $this->redirect( $this->routePrefix . '/edit/' . $row['id']);
        } else if ($event == 'deleteRow') {
            $this->deleteRow($row);
        } else if ($event == 'viewRow') {
            if (!empty($this->customViewAction)) {
                $this->dispatch($this->customViewAction, $row);
            } else {
                $this->redirect($this->routePrefix . '/' . $row['id']);
            }
        }
    }

    public function deleteRow($row, $routePrefix = null): void
    {
        $this->dialog()->confirm([
            'title'       => __('square-ui::square-ui.are_you_sure_title'),
            'description' => __('square-ui::square-ui.are_you_sure_description'),
            'acceptLabel' => __('square-ui::square-ui.yes'),
            'rejectLabel' => __('square-ui::square-ui.cancel'),
            'method'      => 'deleteRowCallback',
            'params'      => $row
        ]);
    }

    public function deleteRowCallback($row): void
    {
        $model = new $this->model;
        $model = $model->findOrFail($row['id']);

        try {
            if(!$model->delete()) {
                $this->error(__('square-ui::square-ui.error'), __('square-ui::square-ui.delete_failed'));
                return;
            }
        } catch (\Exception) {
            $this->error(__('square-ui::square-ui.error'), __('square-ui::square-ui.delete_failed'));
            return;
        }

        $this->success(__('square-ui::square-ui.delete_success'));
        $this->loadData();
        $this->filteredResults = $this->results;
    }

    public function deleteRecordBy($data)
    {
        $field = $data['field'];
        $value = $data['value'];

        $this->results = collect($this->results->filter(function($result) use ($field, $value){
            return $result[$field] != $value;
        }));

        $this->applyFilters();
    }

    public function refreshData($data, $routePrefix = null)
    {
        $col = collect($data);

        if($this->sortBy != '') {
            $col = $col->sortBy($this->sortBy, SORT_REGULAR, $this->sortDirection == 'desc');
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

    public function selectColumn($value)
    {
        if (in_array($value, $this->selectedColumns)) {
            array_splice($this->selectedColumns, array_search($value, $this->selectedColumns) , 1);
        } else {
            $this->selectedColumns[] = $value;
        }
    }

    public function selectAllColumns()
    {
        $this->selectedColumns = $this->searchColumns;
    }

    public function deselectAllColumns()
    {
        $this->selectedColumns = [];
    }
}
