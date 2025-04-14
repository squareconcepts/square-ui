<div wire:init="init" wire:ignore>
        @if($apiTokenIsEmpty)
            <flux:callout icon="exclamation-triangle" variant="warning">
                <flux:callout.heading>Font Awesome Api key Leeg</flux:callout.heading>
                <flux:callout.text>Om font awesome te gebruiken hebben we een API Key nodig.  Ga naar <flux:link variant="ghost" href="https://fontawesome.com/account/general" class="text-amber-700"> FontAwesome -> Account -> General</flux:link>  Onder API Tokesn staat de key die we nodig hebben. </flux:callout.text>
                <flux:separator />
                <x-slot name="actions">
                    <flux:input.group>
                        <flux:input wire:model="api_token" placeholder="Api Key" />
                        <flux:button icon="check" variant="positive" wire:click="storeApiKey()">Opslaan</flux:button>
                    </flux:input.group>
                </x-slot>
            </flux:callout>
        @else
           <div x-data="scFontAwesome" wire:ignore>
               <x-square-ui.tooltip>
                   <x-slot:toggle>
                       <flux:field>
                           <flux:input.group label="FontAwesome icoon">
                               <flux:input.group.prefix>
                                   <template x-if="icon != null">
                                       <i :class="icon"></i>
                                   </template>
                               </flux:input.group.prefix>
                               <flux:input as="button" label="FontAwesome icoon"  x-model="icon" class="rounded-s-none"  icon-trailing="magnifying-glass" >
                                   <flux:text x-text="icon"></flux:text>
                               </flux:input>

                           </flux:input.group>
                       </flux:field>


                   </x-slot:toggle>
                   <x-slot:content class="w-full">
                       <div class="w-full " x-intersect="focusInput()">
                           <flux:input icon-trailing="magnifying-glass" x-model="searchValue" @input.debounce.500ms="fetchResults"  placeholder="Zoeken op Icoon"  x-ref="searchBar" />
                           <flux:separator class="my-3" text="Zoekresultaten" x-ref="resultsCount" />
                           <template x-if="hasResults">
                               <div class="flex flex-wrap gap-1 mt-2 max-h-96 overflow-scroll" >

                                   <template x-for="result in results">
                                               <template x-for="(style, name) in result.styleValues" $id="style + name">
                                                   <div>
                                                       <flux:button variant="ghost" @click="selectIcon(style)" class="!text-2xl">
                                                           <i :class="style"> </i>
                                                       </flux:button>
                                                   </div>
                                               </template>
                                   </template>
                               </div>
                           </template>
                           <template x-if="!hasResults">
                               <div class="rounded-xl flex items-center justify-center p-4 bg-gray-100 mt-2">
                                   <flux:text>
                                       Geen resultaten
                                   </flux:text>
                               </div>
                           </template>
                       </div>

                   </x-slot:content>
               </x-square-ui.tooltip>
           </div>
        @endif
    </div>
@script
    <script>
        Alpine.data('scFontAwesome', () => ({
            icon: @entangle('value').live,
            searchValue: '',
            results: null,
            hasResults:  false,
            endCursor: null,
            hasNextPage: false,
            init(){
                this.results = new Array();
            },
            get iconValue() {
                console.log(this.icon, typeof  this.icon);
            },
            async fetchResults() {
                const response = await $wire.fetchResults(this.searchValue, this.endCursor);

                this.results = response.data;

                this.hasResults = this.results.length > 0;
                this.$nextTick(() => {
                    const span = this.$refs.resultsCount.previousElementSibling;
                    if (span) {
                        span.textContent = `${this.results.length} resultaten gevonden`;
                    }
                });

            },
            selectIcon(icon) {
                this.icon = icon;
                $wire.value = icon;
                this.open = false;
            },
            focusInput() {
                this.$refs.searchBar?.focus();
            }

        }))

    </script>
@endscript
