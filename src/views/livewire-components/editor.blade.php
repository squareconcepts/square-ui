<div >
    @if(!empty($label))
        <div class="flex justify-between mb-1">
            <label class="block font-bold text-black-600">
                {{ $label }}
            </label>
        </div>
    @endif
    <div class="html_content" x-data="editor('{{ $content }}', '{{ $identifier }}', '{{ $uploadUrl }}')" document.addEventListener('updated-editor-' + '{{ $identifier }}', function (e) { $wire.set('content', e.detail);});" x-cloak wire:ignore>
        <textarea id="editor-{{ $identifier }}">{{ $content }}</textarea>
    </div>
</div>
