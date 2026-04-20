<div>
    <div class="space-y-4">
        @if(!$hideToolbar)
            <div class="flex justify-between items-center">
                <div>
                    <flux:input icon="magnifying-glass" clearable wire:model.live="searchString" placeholder="{{ __('square-ui::square-ui.tables.search_for') }}" />
                </div>
                <div class="flex items-center gap-2">
                    <flux:dropdown>
                        <flux:button icon-trailing="chevron-down">{{ __('square-ui::square-ui.tables.columns') }}</flux:button>
                        <flux:menu>
                            <div class="p-2 space-y-1">
                                <label class="flex items-center gap-2 px-2 py-1 cursor-pointer select-none" wire:loading.attr="disabled">
                                    <input type="checkbox"
                                        @checked(sizeof($selectedColumns) == sizeof($searchColumns))
                                        @if(sizeof($selectedColumns) == sizeof($searchColumns)) wire:click="deselectAllColumns" @else wire:click="selectAllColumns" @endif
                                        wire:loading.attr="disabled" />
                                    <span class="text-sm">{{ __('square-ui::square-ui.tables.all_columns') }}</span>
                                </label>
                                @foreach($columns as $fieldName => $column)
                                    <label class="flex items-center gap-2 px-2 py-1 cursor-pointer select-none" wire:key="data-table-columnSelect-{{ $loop->index }}" wire:loading.attr="disabled" wire:target="selectedColumns">
                                        <input type="checkbox"
                                            @if(in_array($fieldName, $selectedColumns)) checked @endif
                                            wire:change="selectColumn('{{ $fieldName }}')"
                                            wire:loading.attr="disabled" />
                                        <span class="text-sm">{{ $column['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </flux:menu>
                    </flux:dropdown>
                    <flux:select wire:model.lazy="perPage">
                        @foreach($perPageOptions as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </flux:select>
                </div>
            </div>
        @endif
        <div class="align-middle min-w-full overflow-x-auto overflow-hidden rounded-none md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    @foreach($columns as $key => $column)
                        @if(in_array($key, $selectedColumns))
                            <th class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:bg-gray-800 dark:text-gray-400">
                                @if(in_array($key, $sortables))
                                    <span class="flex items-center gap-1 tracking-wider text-left cursor-pointer" wire:click="sortColumns('{{ $key }}')">
                                        {{ $column['label'] }}
                                        @if($sortBy == $key)
                                            <flux:icon :icon="$sortDirection === 'asc' ? 'chevron-down' : 'chevron-up'" variant="micro" />
                                        @elseif($key == 'ID' && $sortBy == '')
                                            <flux:icon icon="chevron-down" variant="micro" />
                                        @endif
                                    </span>
                                @else
                                    <span class="block tracking-wider text-left">{{ $column['label'] }}</span>
                                @endif
                            </th>
                        @endif
                    @endforeach
                    @if(!$hideActions)
                        <th class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:bg-gray-800">
                            <span class="block tracking-wider text-left">{{ __('square-ui::square-ui.tables.actions') }}</span>
                        </th>
                    @endif
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @if(!empty($this->items) && $this->items->count() > 0)
                    @foreach($this->items->map(fn($item) => (object) $item) as $result)
                        <tr class="hover:bg-gray-50">
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
                                                @elseif($column['type'] == 'dropdown_list' || $column['type'] == 'relation' || $column['type'] == 'array')
                                                    {{ implode(', ', $result->$key) }}
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
                                            <flux:button icon="{{ $button['icon'] }}" wire:click="handleAction('{{ $button['method'] }}', {{ json_encode($result) }})" outline :variant="$button['color']">
                                                {{ $button['label'] }}
                                            </flux:button>
                                        @else
                                            <flux:button icon="{{ $button['icon'] }}" wire:click="handleAction('{{ $button['method'] }}', {{ json_encode($result) }})" outline :variant="$button['color']" />
                                        @endif
                                    @endforeach
                                    @if(!$hideRead)
                                        <flux:button icon="eye" wire:click="handleAction('viewRow', {{ json_encode($result) }})" outline variant="primary" />
                                    @endif
                                    @if(!$hideEdit)
                                        <flux:button icon="pencil" wire:click="handleAction('editRow', {{ json_encode($result) }})" outline variant="warning" />
                                    @endif
                                    @if(!$hideDelete)
                                        <flux:button icon="trash" wire:click="handleAction('deleteRow', {{ json_encode($result) }})" outline variant="danger" />
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="whitespace-nowrap px-3 py-2 md:px-6 md:py-4 leading-5 text-gray-900" colspan="{{ count($columns) + ((!$hideActions) ? 1 : 0) }}">
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
