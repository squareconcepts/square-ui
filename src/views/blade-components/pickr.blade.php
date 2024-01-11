@props(['value' => '#000000'])
<!-- One of the following themes -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css"/> <!-- 'classic' theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/monolith.min.css"/> <!-- 'monolith' theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css"/> <!-- 'nano' theme -->

<!-- Modern or es5 bundle -->
<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.es5.min.js"></script>
<div
    wire:ignore
    x-data="pickr('{{ $identifier }}', '{{ $value}}', '{{ $attributes['wire:model'] }}', '{{ $componentId }}')" x-init="initPickr"
    x-ref="pickr_{{$identifier}}"
>
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
                            opacity: true,
                            hue: true,
                            interaction: {
                                hex: true,
                                rgba: true,
                                hsla: false,
                                hsva: false,
                                input: true,
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
