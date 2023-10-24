<div class="" x-data="{selected: null}" x-init="() => selected = $refs.initialSelected.value">
    <input type="hidden" name="initialSelected" x-ref="initialSelected" value="{{ $selected }}" />
    <ul class="shadow-box flex flex-col gap-2">
        {{ $slot }}
    </ul>
</div>
