@props(['value' => '#000000'])
{{--
    Pickr wordt één keer per pagina geladen (vaste versie) door de eerste instantie die hem
    nodig heeft, ook als de picker pas na een Livewire-update verschijnt. Eerder laadde elke
    instantie drie thema's en twee keer het script, en registreerde ze een globale Alpine.data.
--}}
<div wire:ignore
    x-data="{
        value: @js($value),
        model: @js($attributes['wire:model']),
        componentId: @js($componentId),
        init() {
            window.scPickrLoader ??= new Promise((resolve, reject) => {
                const css = document.createElement('link');
                css.rel = 'stylesheet';
                css.href = 'https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.10.2/dist/themes/classic.min.css';
                const js = document.createElement('script');
                js.src = 'https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.10.2/dist/pickr.min.js';
                js.onload = resolve;
                js.onerror = reject;
                document.head.append(css, js);
            });
            window.scPickrLoader.then(() => this.createPickr());
        },
        createPickr() {
            const pickr = Pickr.create({
                el: this.$refs.pickr,
                default: this.value,
                comparison: false,
                swatches: @js($colorOptions),
                components: {
                    preview: true,
                    palette: true,
                    opacity: true,
                    hue: true,
                    interaction: { hex: true, rgba: true, hsla: false, hsva: false, input: true, cancel: true, clear: true, save: true },
                },
                i18n: {
                    'ui:dialog': 'color picker dialog',
                    'btn:toggle': 'toggle color picker dialog',
                    'btn:swatch': 'color swatch',
                    'btn:last-color': 'use previous color',
                    'btn:save': 'Opslaan',
                    'btn:cancel': 'Annuleren',
                    'btn:clear': 'Wissen',
                },
            });

            const initial = this.value;
            pickr.on('change', (color) => {
                this.value = color.toHEXA().toString();
            }).on('save', (color, instance) => {
                this.value = color.toHEXA().toString();
                this.setData(this.value);
                instance.hide();
            }).on('clear', (color, instance) => {
                this.value = color ? color.toHEXA().toString() : null;
                this.setData(this.value);
                instance.hide();
            }).on('cancel', (instance) => {
                this.value = initial;
                instance.hide();
            });
        },
        setData(value) {
            if (this.componentId && this.componentId.length > 0) {
                Livewire.find(this.componentId).set(this.model, value);
            } else {
                this.$wire.set(this.model, value);
            }
        },
    }"
>
    <div class="flex justify-between items-end mb-1">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{$label}}
        </label>
    </div>
    <div x-ref="pickr"></div>
</div>
