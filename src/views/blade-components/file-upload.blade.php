{{-- @deprecated 4.x, removed in 5.0: use <flux:input type="file"> or (Flux Pro) <flux:file-upload>. See UPGRADE.md. --}}
<div class="file_upload">
    @if(!empty($label))
        <label for="{{ $inputId }}" class="file_upload_label">
            {{ $label }}
        </label>
    @endif
    <input type="file" id="{{ $inputId }}" class="input-style"
           @if(empty($label)) aria-label="{{ $placeholder }}" title="{{ $placeholder }}" @endif
           {{ $attributes }} @if($multiple) multiple @endif>
</div>
