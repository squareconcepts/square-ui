<div class="relative" x-data="{
        open: false,
        search: '',
        results: {{ json_encode($items) }},
        route: '{{$asyncRoute}}',
        multiple: {{$multiple ? 1 : 0}},
        selectedKey: '',
        @if($attributes['wire:model'])
            selectedValue: $wire.entangle('{{ $attributes['wire:model'] }}').live,
            initialValue: $wire.{{ $attributes['wire:model'] }},
        @else
            selectedValue: '',
            initialValue: '',
        @endif
        optionLabel: '{{ $optionLabel }}',
        optionValue: '{{ $optionValue }}',
        placeholder: '{{ $attributes['placeholder'] ?? $attributes['label'] ?? 'Selecteer een optie' }}',
        async getResults(id = null, size = 10) {
            if(this.route == null || this.route === '' || this.route === undefined) {
                this.results = {{ json_encode($items) }};
                if(this.results.length > 0 && this.search.length > 0){
                    this.results =  this.results.filter((element) => {
                        let item = element[this.optionLabel];
                        return item != null && item.toLowerCase().indexOf(this.search.toLowerCase()) !== -1;
                    });
                }
                return this.results;
            }
            if(this.results && this.results.length > 0) {
                size = this.results.length + 10;
            }
            let url = this.route + '?size=' + size;
            if(this.search.length > 0 ) {
                url = url + '&search=' + this.search;
                if(id != null) {
                    url = url + '&selected=' + id;
                }
            } else {
                if(id != null) {
                    url = url + '&selected=' + id;
                }
            }
            let results = await (await fetch(url)).json();
            this.results = results;
            return results;
        },
        async setInitialKey () {
            let results = await this.getResults(this.initialValue, 10);
            let items;
            let item;
            if ((this.initialValue != null && this.selectedKey === '') || this.multiple) {
                if (this.multiple) {
                    items = results.filter((result) => this.initialValue.includes(result[this.optionValue]));

                    if (items !== undefined && items.length > 0) {
                        this.selectedKey = items.map((element) => element[this.optionLabel]);
                    } else {
                        this.selectedKey = [];
                    }
                } else {
                    item = results.find((result) => result[this.optionValue] === this.initialValue);
                    if (item !== undefined) {
                        this.selectedKey = item[this.optionLabel];
                        this.selectedValue = item[this.optionValue];
                    }
                }
            }

            this.enforceObjects();
        },
        selectOption(element) {
            if(this.multiple){
                if(!this.selectedValue.includes(element[`${this.optionValue}`]) ) {
                    this.selectedValue.push(element[`${this.optionValue}`]);
                    this.selectedKey.push(element[`${this.optionLabel}`]);
                } else {
                    let index = this.selectedKey.indexOf(element[`${this.optionLabel}`]);
                    this.selectedKey.splice(index, 1);
                    this.selectedValue.splice(index, 1);
                }
            } else {
                this.selectedValue = element[`${this.optionValue}`];
                this.selectedKey = element[`${this.optionLabel}`];
                this.open = false;
            }
            this.enforceObjects();
        },
        enforceObjects(){

            if(typeof this.selectedValue == 'array' || typeof this.selectedValue == 'object') {
                return;
            } else if(typeof this.selectedValue === 'string' || this.selectedValue instanceof String){
                if(this.selectedValue.indexOf(',') !== -1 || this.selectedValue.length > 0) {
                    this.selectedValue = this.selectedValue.split(',');
                } else {
                    this.selectedValue = [];
                }
            }

            if(typeof this.selectedKey == 'array' || typeof this.selectedKey == 'object') {

            } else if(typeof this.selectedKey === 'string' || this.selectedKey instanceof String){
                if(this.selectedKey.indexOf(',') !== -1 || this.selectedKey.length > 0) {
                    this.selectedKey = this.selectedKey.split(',');
                } else {
                    this.selectedKey = [];
                }
            }

        }
    }"
     x-init="$watch('search', value => getResults(null)), setInitialKey()"
>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
        {{$attributes['label']}}
    </label>
    <div class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm cursor-pointer !flex items-center" x-on:click="open = !open">
        <div x-show="!multiple">
            <p x-text="selectedKey"
               x-show="(selectedValue != null && selectedValue == initialValue) || (selectedValue != '' && selectedValue != null) "
               x-cloak></p>
            <p x-text="placeholder" x-show="!selectedValue" x-cloak></p>
        </div>
        <div x-show="multiple" class="flex flex-wrap gap-1" x-cloak>
            <template x-for="(selected, index) in selectedKey " :key="index">
                <div class="bg-primary-500 text-white p-1 rounded divide-x flex gap-1" x-show="selected.length > 0"
                     x-cloak>
                    <p x-text="selected"></p>
                    <span class="px-1"
                          x-on:click="selectedKey.splice(index, 1); selectedValue.splice(index, 1); open = true;">x</span>
                </div>
            </template>
        </div>
        <svg class="w-5 h-5 ml-auto text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
        </svg>
    </div>
    <div class="absolute left-0 right-0 mt-1 bg-white shadow rounded py-2 z-50" x-show="open" x-cloak
         @click.outside="open = false">
        <div class="bg-white p-2">
            <input right-icon="search" x-model="search" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm" placeholder="Zoeken..."/>
        </div>
        <div class="max-h-48 overflow-auto" x-show="results.length > 0" x-cloak>
            <template x-for="(result, index, collection) in results" :key="index">
                <div
                    class="px-2 py-3  hover:bg-red-500 hover:text-white cursor-pointer"
                    :class="typeof result !== 'undefined' && (multiple ? (selectedValue.includes(result[`${optionValue}`])) : (selectedValue == result[optionValue])) ? 'font-bold hover:bg-red' :'hover:bg-sc-blue'"
                    x-on:click="selectOption(result)"
                    x-intersect=" () => { if( route != null && route != '' && route != undefined && index + 1 == results.length) {  getResults(selectedValue) } }"
                >
                    <div x-show="multiple && typeof selectedValue == 'object'">
                        <p x-show="typeof selectedValue == 'object' && typeof result !== 'undefined' && selectedValue?.includes(result[optionValue])"
                           class="flex items-center justify-between">
                            <span x-text="typeof result !== 'undefined' ? result[optionLabel] : ''"></span> <i
                                class="fa fa-check"></i>
                        </p>
                        <p x-show="typeof result !== 'undefined' && typeof selectedValue == 'object' && !selectedValue?.includes(result[optionValue])">
                            <span x-text="typeof result !== 'undefined' ? result[optionLabel] : ''"></span></p>
                    </div>

                    <div x-show="!multiple">
                        <p x-show="typeof result !== 'undefined' && selectedValue == result[optionValue]"
                           class="flex items-center justify-between">
                            <span x-text="typeof result !== 'undefined' ? result[optionLabel] : ''"></span> <i
                                class="fa fa-check"></i>
                        </p>
                        <p x-show="typeof result !== 'undefined' && selectedValue !== result[optionValue]"><span
                                x-text="typeof result !== 'undefined' ? result[optionLabel]: ''"></span></p>
                    </div>
                </div>
            </template>
        </div>
        <div x-show="results.length <= 0" x-cloak>
            <p x-text="'No results for ' + search"></p>
        </div>
    </div>
</div>
