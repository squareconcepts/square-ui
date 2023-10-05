<div wire:ignore>
    <x-input id="date-picker-{{$id}}" {{$attributes}} right-icon="calendar"/>
</div>
<script type="module">
    if(!window.flatpickr ) {
        throw new Error('Flatpickr is nodig voor het gebruik van dit component. Laad Flatpickr in in je pagina')
    } else {
        document.addEventListener('livewire:init', function () {
            let options = @js($options);
            let disabledWeekends = @js($disableWeekends);
            let id = @js($id);

            if (disabledWeekends) {
                options.disable = [function (date) {
                    // Zaterdag (6) en zondag (0) zijn weekenddagen
                    return (date.getDay() === 0 || date.getDay() === 6);
                }]
            }

            if(!window.jQuery){
                flatpickr("#date-picker-" + id, options);
            } else {
                jQuery("#date-picker-" + id).flatpickr(options);
            }

        });
        document.addEventListener('livewire:navigated', function () {
            let options = @js($options);
            let disabledWeekends = @js($disableWeekends);
            let id = @js($id);

            if (disabledWeekends) {
                options.disable = [function (date) {
                    // Zaterdag (6) en zondag (0) zijn weekenddagen
                    return (date.getDay() === 0 || date.getDay() === 6);
                }]
            }
            if(!window.jQuery){
                const flatpickr = require("flatpickr");
                flatpickr("#date-picker-" + id, options);
            } else {
                jQuery("#date-picker-" + id).flatpickr(options);
            }

        });

    }

</script>
