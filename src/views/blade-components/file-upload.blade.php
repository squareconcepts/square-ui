<div class="file_upload">
    @if(!empty($label))
        <label for="file_upload_input" class="file_upload_label">
            {{ $label }}
        </label>
    @endif
    <input type="file" class="input-style" {{ $attributes }} {{ $multiple ? 'multiple' : '' }}>
</div>

