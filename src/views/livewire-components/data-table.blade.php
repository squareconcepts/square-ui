<div>
    <div class="space-y-4">
        @if(!$hideToolbar)
            <div class="flex justify-between">
                <div class="mx-2">
                    <flux:input icon="magnifying-glass" clearable wire:model.live="searchString" placeholder="{{__('square-ui::square-ui.tables.search_for')}}" />
                </div>
                <div class="md:flex md:items-center space-y-4 md:space-y-0 md:space-x-2">
                    <div>
                        <div x-data="{
                            open: false,
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }

                                this.$refs.button.focus()

                                this.open = true
                            },
                            close(focusAfter) {
                                if (! this.open) return

                                this.open = false

                                focusAfter && focusAfter.focus()
                            }
                        }"
                             x-on:keydown.escape.prevent.stop="close($refs.button)"
                             x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                             x-id="['dropdown-button']"
                             class="relative">

                            <button
                                x-ref="button"
                                x-on:click="toggle()"
                                :aria-expanded="open"
                                :aria-controls="$id('dropdown-button')"
                                type="button"
                                class="flex items-center gap-2 bg-white px-5 py-2.5 rounded-md shadow text-gray-700 font-medium text-sm">
                                {{__('square-ui::square-ui.tables.columns')}}

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <div
                                x-ref="panel"
                                x-show="open"
                                x-transition.origin.top.left
                                x-on:click.outside="close($refs.button)"
                                :id="$id('dropdown-button')"
                                style="display: none;"
                                class="absolute left-0 mt-2 w-40 rounded-md bg-white shadow-md">
                                <div
                                    x-cloak
                                    x-show="open"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 z-50 mt-2 w-full bg-white rounded-md divide-y divide-gray-100 ring-1 ring-black ring-opacity-5 shadow-lg origin-top-right md:w-48 focus:outline-none">
                                    <div class="bg-white rounded-md shadow-xs dark:bg-gray-700 dark:text-white">
                                        <div class="p-2" role="menu" aria-orientation="vertical"
                                             aria-labelledby="column-select-menu">
                                            <div>
                                                <label
                                                    wire:loading.attr="disabled"
                                                    class="inline-flex items-center px-2 py-1 disabled:opacity-50 disabled:cursor-wait">
                                                    <input
                                                        class="text-indigo-600 transition duration-150 ease-in-out border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:bg-gray-600 disabled:opacity-50 disabled:cursor-wait"
                                                        wire:loading.attr="disabled"
                                                        type="checkbox"
                                                        @checked(sizeof($selectedColumns) == sizeof($searchColumns))
                                                        @if(sizeof($selectedColumns) == sizeof($searchColumns)) wire:click="deselectAllColumns" @else wire:click="selectAllColumns" @endif>
                                                    <span class="ml-2">{{ __('square-ui::square-ui.tables.all_columns') }}</span>
                                                </label>
                                            </div>

                                            @foreach($columns as $fieldName => $column)
                                                <div wire:key="data-table-columnSelect-{{ $loop->index }}">
                                                    <label
                                                        wire:loading.attr="disabled"
                                                        wire:target="selectedColumns"
                                                        class="inline-flex items-center px-2 py-1 disabled:opacity-50 disabled:cursor-wait">
                                                        <input
                                                            class="text-indigo-600 rounded border-gray-300 shadow-sm transition duration-150 ease-in-out focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:bg-gray-600 disabled:opacity-50 disabled:cursor-wait"
                                                            @if(in_array($fieldName, $selectedColumns)) checked @endif
                                                            wire:change="selectColumn('{{$fieldName}}')"
                                                            wire:loading.attr="disabled" type="checkbox" />
                                                        <span class="ml-2">{{ $column['label'] }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label>
                            <select wire:model.lazy="perPage" class="input-style">
                                @foreach($perPageOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </div>
            </div>
        @endif
        <div class="align-middle min-w-full overflow-x-auto overflow-hidden rounded-none md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-white">
                <tr>
                    @foreach($columns as $key => $column)
                        @if(in_array($key, $selectedColumns))
                            <th class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:bg-gray-800 dark:text-gray-400">
                                @if(in_array($key, $sortables))
                                    <span class="block tracking-wider text-left cursor-pointer"
                                          wire:click="sortColumns('{{$key}}')">
                                            {{ $column['label'] }}
                                        @if($sortBy == $key)
                                            @if($sortDirection == '' || $sortDirection == 'asc')
                                                <i class="fa fa-chevron-down cursor-pointer"
                                                   wire:click="sortColumns({{$key}})"></i>
                                            @else
                                                <i class="fa fa-chevron-up cursor-pointer"
                                                   wire:click="sortColumns({{$key}})"></i>
                                            @endif
                                        @elseif($key == 'ID' && $sortBy == '')
                                            <i class="fa fa-chevron-down cursor-pointer" wire:click="sortColumns({{$key}})"></i>
                                        @endif
                                        </span>
                                @else
                                    <span class="block tracking-wider text-left">
                                        {{ $column['label'] }}
                                    </span>
                                @endif
                            </th>
                        @endif
                    @endforeach

                    @if(!$hideActions)
                        <th class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:bg-gray-800">
                            <span class="block tracking-wider text-left">
                                {{ __('square-ui::square-ui.tables.actions') }}
                            </span>
                        </th>
                    @endif
                </tr>
                </thead>
                <tbody wire:sortable class="bg-white divide-y divide-gray-200">
                @if(!empty($this->items) && $this->items->count() > 0)
                    @foreach($this->items->map(function ($item) {
                            return (object) $item;
                        }) as $key => $result)
                        <tr class="box p-3 hover:bg-gray-50">
                            @foreach($columns as $key => $column)

                                @if(in_array($key, $selectedColumns))
                                    <td class="whitespace-nowrap leading-5 text-gray-900 md:table-cell">
                                        <div class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                            @if(!empty($result->$key))
                                                @if($column['type'] == 'currency')
                                                    @currency((float) $result->$key)
                                                @elseif($column['type'] == 'datetime')
                                                    {{ Carbon\Carbon::parse($result->$key)->format('d-m-Y H:i:s') }}
                                                @elseif($column['type'] == 'date')
                                                    {{ Carbon\Carbon::parse($result->$key)->format('d-m-Y') }}
                                                @elseif($column['type'] == 'text' || $column['type'] == 'number')
                                                    {{ $result->$key }}
                                                @elseif($column['type'] == 'textarea')
                                                    {{ substr($result->$key, 0, 100) }}
                                                @elseif($column['type'] == 'email')
                                                    @email($result->$key)
                                                @elseif($column['type'] == 'phone_number')
                                                    @phone($result->$key)
                                                @elseif($column['type'] == 'url')
                                                    @url($result->$key)
                                                @elseif($column['type'] == 'boolean')
                                                    {{ __('square-ui::square-ui.app.yes') }}
                                                @elseif($column['type'] == 'dropdown_list')
                                                    {{implode(', ', $result->$key)}}
                                                @elseif($column['type'] == 'relation')
                                                    {{implode(', ', $result->$key)}}
                                                @elseif($column['type'] == 'array')
                                                    {{implode(', ', $result->$key)}}
                                                @endif
                                            @else
                                                @if($column['type'] == 'boolean')
                                                    {{ __('square-ui::square-ui.app.no') }}
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            @endforeach
                            @if(!$hideActions)
                                <td class="whitespace-nowrap px-3 py-2 md:px-6 md:py-4 leading-5 text-gray-900 w-0">
                                    @foreach($customButtons as $button)
                                        @if(!empty($button['label']))
                                            <flux:button icon="{{$button['icon']}}" wire:click="{{$button['method']}}({{$row->id}})" outline :variant="$button['color']">
                                                {{$button['label']}}
                                            </flux:button>

                                        @else
                                            <flux:button icon="{{$button['icon']}}" wire:click="{{$button['method']}}({{$row->id}})" outline :variant="$button['color']" />
                                        @endif
                                    @endforeach
                                    @if(!$hideRead)
                                            <flux:button icon="eye" wire:click="handleAction('viewRow', {{json_encode($result)}})" outline variant="primary" />
                                    @endif
                                    @if(!$hideEdit)
                                            <flux:button icon="pencil" wire:click="handleAction('editRow', {{json_encode($result)}})" outline variant="warning" />
                                    @endif
                                    @endif
                                    @if(!$hideDelete)
                                            <flux:button icon="trash" wire:click="handleAction('deleteRow', {{json_encode($result)}})" outline variant="danger" />
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr class="box p-3hover:bg-gray-50 ">
                        <td class="whitespace-nowrap px-3 py-2 md:px-6 md:py-4 leading-5 text-gray-900"
                            colspan="{{ count($columns) + ((!$hideActions) ? 1 : 0) }}">
                            {{ __('square-ui::square-ui.tables.no_results') }}
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        @if($withPagination)
            <div class="px-6 py-2 md:p-0">
                <div class="hidden md:flex-1 md:flex md:items-center md:justify-between">
                    <div class="flex-1">
                        {{ $this->items->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
