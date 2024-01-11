@props(['value' => '#000000'])
<div wire:ignore>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css"/> <!-- 'classic' theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/monolith.min.css"/> <!-- 'monolith' theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css"/> <!-- 'nano' theme -->
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.es5.min.js"></script>
    <div class="flex justify-between items-end mb-1">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{$label}}
        </label>
    </div>
    <div

        x-data="pickr('{{ $identifier }}', '{{ $value}}', '{{ $attributes['wire:model'] }}', '{{ $componentId }}')" x-init="initPickr"
        x-ref="pickr_{{$identifier}}"
    >
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            initPickr()
        });
        document.addEventListener('livewire:navigated', () => {
            initPickr()
        });

        function initPickr() {
            window.Alpine.data('pickr', (identifier, value, model, componentId) => ({
                identifier: identifier,
                value: value,
                model: model,
                componentId: componentId,
                initPickr() {
                    let  pickr = Pickr.create({
                        el: this.$refs['pickr_' + this.identifier],
                        default: this.value,
                        comparison: false,
                        swatches: @js($colorOptions),
                        components: {
                            preview: true,
                            palette: true,
                            opacity: true,
                            hue: true,
                            interaction: {
                                hex: true,
                                rgba: true,
                                hsla: false,
                                hsva: false,
                                input: true,
                                cancel: true,
                                clear: true,
                                save: true
                            }
                        },
                        i18n: {
                            'ui:dialog': 'color picker dialog',
                            'btn:toggle': 'toggle color picker dialog',
                            'btn:swatch': 'color swatch',
                            'btn:last-color': 'use previous color',
                            'btn:save': 'Opslaan',
                            'btn:cancel': 'Annuleren',
                            'btn:clear': 'Wissen',

                        }});

                    pickr.on('change', (color, instance) => {
                        this.value =  color.toHEXA().toString();

                    }).on('save', (color, instance) => {
                        this.value = color.toHEXA().toString();
                        this.setData(color.toHEXA().toString())
                        instance.hide();
                    }).on('clear', (color, instance) => {
                        this.value = color.toHEXA().toString();
                        this.setData(color.toHEXA().toString())
                        instance.hide();
                    }).on('cancel', (instance) => {
                        this.value = @js($value);
                        instance.hide();
                    });


                },
                setData(value) {
                    if(this.componentId.length > 0) {
                        Livewire.find(this.componentId).set(this.model, value);
                    } else {
                        @this.set(this.model, value);
                    }
                }
            }));
        }
    </script>
</div>
