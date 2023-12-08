<div wire:ignore>
    @if(!empty($label) && !empty($tooltip))
        <div class="flex justify-between mb-1">
            <label class="block font-medium text-gray-700 dark:text-gray-400">
                {{ $label }}
                @if(!empty($tooltip))
                    <a href="javascript:;" class="tooltip" title="{{ $tooltip }}"><i
                            class="fa-duotone fa-circle-info ml-2"></i></a>
                @endif
            </label>
        </div>
    @endif
    <div x-data="ckEditor('{{ $identifier }}', '{{ $value }}', '{{ $model }}')" x-init="initEditor" >
        <textarea x-ref="ckeditor_{{ $identifier }}" {{ $attributes }}></textarea>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ckEditor', (identifier, value, model) => ({
                identifier: identifier,
                value: value,
                model: model,
                isInitialized: false,
                initEditor() {
                    if(!this.isInitialized) {
                        ClassicEditor
                            .create(this.$refs['ckeditor_' + this.identifier], {
                                mediaEmbed: {
                                    previewsInData: true
                                },
                                removePlugins: ["MediaEmbedToolbar"],
                                simpleUpload: {
                                    // The URL that the images are uploaded to.
                                    uploadUrl: '{{ route('square-ui.file-upload') }}',

                                    // Enable the XMLHttpRequest.withCredentials property.
                                    withCredentials: true,

                                    // Headers sent along with the XMLHttpRequest to the upload server.
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                }
                            })
                            .then(editor => {
                                this.editor = editor;
                                editor.setData(this.value);
                                editor.model.document.on('change:data', () => {
                                    @this.set(this.model, editor.getData());
                                });

                                document.addEventListener('update-editor-data', (event) => {
                                    const id = event.detail[0]['id'];
                                    const value = event.detail[0]['value'];
                                    if(id === this.identifier) {
                                        editor.setData(value)
                                    }
                                })
                            })
                            .catch(error => {
                                console.error('Error initializing CKEditor:', error);
                            });

                        this.isInitialized = true;
                    }
                }
            }));
        });
    </script>
</div>
