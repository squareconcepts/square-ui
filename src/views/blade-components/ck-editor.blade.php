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
    <div  x-data="ckEditor('{{ $identifier }}', '{{ addslashes($value) }}', '{{ $model }}', '{{ $componentId }}')" x-init="initEditor" >
        <div x-show="!showHtml">
            <textarea x-ref="ckeditor_{{ $identifier }}" {{ $attributes }}></textarea>
        </div>
        <div x-show="showHtml">
            <textarea class="input-style" style="width: 100%;" x-ref="ckeditor_{{ $identifier }}_preview"></textarea>
        </div>
        <div class="block w-full h-8">
            <button class="text-sm float-right mt-1" x-on:click="changeMode()">Toon HTML</button>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            initAlpineEditor()
        });
        document.addEventListener('livewire:navigated', () => {
            initAlpineEditor()
        });

        function initAlpineEditor() {
            window.Alpine.data('ckEditor', (identifier, value, model, componentId) => ({
                identifier: identifier,
                value: value,
                model: model,
                componentId: componentId,
                isInitialized: false,
                showHtml: false,
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
                                },
                            })
                            .then(editor => {
                                this.editor = editor;
                                editor.setData(this.value);
                                editor.model.document.on('change:data', () => {
                                    this.value = editor.getData();
                                    if(this.componentId.length > 0) {
                                        Livewire.find(this.componentId).set(this.model, editor.getData());
                                    } else {
                                        @this.set(this.model, editor.getData());
                                    }
                                });

                                document.addEventListener('update-editor-data', (event) => {
                                    const id = event.detail[0]['id'];
                                    const value = event.detail[0]['value'];
                                    if(id === this.identifier) {
                                        editor.setData(value)
                                    }
                                })

                                document.addEventListener('update-'+ this.identifier +'-value', (event) => {
                                    editor.setData(event.detail)
                                })
                            })
                            .catch(error => {
                                console.error('Error initializing CKEditor:', error);
                            });

                        this.isInitialized = true;
                    }
                },
                changeMode() {
                    if(this.showHtml) {
                        const value = this.$refs['ckeditor_' + this.identifier + '_preview'].value;
                        document.dispatchEvent(new CustomEvent('update-' + this.identifier + '-value', { detail: value}));
                        this.showHtml = false;
                    } else {
                        this.$refs['ckeditor_' + this.identifier + '_preview'].value = this.value;
                        this.showHtml = true;
                    }
                }
            }));
        }
    </script>
</div>
