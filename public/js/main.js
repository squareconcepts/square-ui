document.addEventListener('alpine:init', () => {
    Alpine.data('slideOverPanel', (id) => ({
        id: id,
        showing: false,
        open() {
            document.getElementById('slideover-'+ this.id +'-container').classList.remove('invisible');
            document.getElementById('slideover-'+ this.id +'-bg').classList.remove('opacity-0');
            document.getElementById('slideover-'+ this.id +'-bg').classList.add('opacity-50');
            document.getElementById('slideover-'+ this.id).classList.remove('translate-x-full');
            setTimeout(() => {
                this.showing = !this.showing;
            }, 10)
        },
        close() {
            document.getElementById('slideover-'+ this.id +'-container').classList.add('invisible');
            document.getElementById('slideover-'+ this.id +'-bg').classList.add('opacity-0');
            document.getElementById('slideover-'+ this.id +'-bg').classList.remove('opacity-50');
            document.getElementById('slideover-'+ this.id).classList.add('translate-x-full');
            setTimeout(() => {
                this.showing = !this.showing;
            }, 10)
        },
        clickOutside(show) {
            if(this.showing) {
                this.close();
            }
        }
    }));
    Alpine.data('ckEditor', (identifier, value, model, componentId, uploadRoute, csrfToken, chatGPTRoute) => ({
        identifier: identifier,
        value: value,
        model: model,
        componentId: componentId,
        uploadRoute: uploadRoute,
        csrfToken: csrfToken,
        chatGPTRoute: chatGPTRoute,
        isInitialized: false,
        showHtml: false,
        showChatGpt: false,
        promptPrefixOption: ['Kun je van deze tekst een makkelijker te lezen sales ingestoken variant maken maar behoud de html zoals deze al in te tekst staat:', ''],
        promptPrefix: '',
        prompt: '',
        promptRows: 1,
        chatGptResult: null,
        askingChatGpt: false,
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
                            uploadUrl: this.uploadRoute,

                            // Enable the XMLHttpRequest.withCredentials property.
                            withCredentials: true,

                            // Headers sent along with the XMLHttpRequest to the upload server.
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken,
                            }
                        },
                        toolbar: [
                            'undo', 'redo', '|', 'heading', '|', 'bold', 'italic', 'underline','strikethrough', '|', 'link', '|', 'bulletedList', 'numberedList', 'todoList', '|',
                            'outdent', 'indent', '|', 'blockQuote', 'insertTable', '|', 'alignment', '|', 'imageUpload', '|', 'codeBlock'
                        ]
                    })
                    .then(editor => {
                        this.editor = editor;
                        editor.setData(this.value);
                        this.prompt = this.value;
                        this.promptPrefix = this.promptPrefixOption[0];
                        this.getLineCount();
                        editor.model.document.on('change:data', () => {
                            this.value = editor.getData();
                            this.prompt = this.value;
                            this.getLineCount();
                            if(this.componentId.length > 0) {
                                Livewire.find(this.componentId).set(this.model, editor.getData());
                            } else {
                                console.error('No component id set, this is required after updating the package')
                                // @this.set(this.model, editor.getData());
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
        },
        changeChatGptMode() {
            this.showChatGpt = ! this.showChatGpt;
        },
        getLineCount() {
            if(!this.showChatGpt) {
                return 1;
            }
            const textarea = this.$refs['chatgpt_' + this.identifier + '_prompt_preview'];

            const lineHeight = parseInt(getComputedStyle(textarea).lineHeight);
            const textareaHeight = Math.max(textarea.scrollHeight,textarea.clientHeight) ;
            this.promptRows = Math.round(textareaHeight / lineHeight);
        },
        askChatGPT() {
            this.chatGptResult = null;
            this.askingChatGpt = true;
            const question = this.promptPrefix + ' ' + this.prompt;
            const route = this.chatGPTRoute;
            if(route == null ) {
                console.error('Error: Er bestaat geen route met de naam: "square-ui.chat-gpt.ask". Maak een post route aan met deze naam.')
                return;
            }

            fetch(route, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ question: question }),
            })
                .then(response => response.text())
                .then(data => {
                    this.chatGptResult = data;
                    this.askingChatGpt = false;
                })
                .catch(error => {
                    console.error('Error:', error)
                    this.askingChatGpt = false;
                })

        },
        useText() {
            document.dispatchEvent(new CustomEvent('update-' + this.identifier + '-value', { detail: this.chatGptResult}));
            this.chatGptResult = null;
            this.changeChatGptMode();
        }

    }));
})
