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
        <div class="flex items-center justify-between flex-row-reverse">

            <button class="text-sm float-right mt-1" x-on:click="changeMode()">
                <span x-show="!showHtml">Toon HTML</span>
                <span x-show="showHtml">Verberg HTML</span>
            </button>
            @if($useChatGpt && Route::has('square-ui.chat-gpt.ask'))
                <div class="text-sm mt-1 py-2 flex items-center gap-2 cursor-pointer" x-on:click="changeChatGptMode()">
                    <div x-show="!showChatGpt" class="relative p-1 rounded-sm h-9 w-9 text-white flex items-center justify-center bg-slate-200 p-2">
                        <x-square-ui.svg::chatgpt-logo/>
                    </div>
                    <div x-show="showChatGpt" class="relative p-1 rounded-sm h-9 w-9 text-white flex items-center justify-center bg-positive-500">
                        <x-square-ui.svg::chatgpt-logo/>
                    </div>
                    <p class="font-bold">ChatGPT 3.5</p>
                </div>
            @endif
        </div>
        @if($useChatGpt && Route::has('square-ui.chat-gpt.ask'))
            <div x-show="showChatGpt" class="p-2 rounded bg-[#343541] mt-2 text-white flex flex-col gap-2" x-cloak>
                <div class="header flex gap-2">
                    <div class="relative p-1 rounded-sm h-9 w-9 text-white flex items-center justify-center" style="background-color: rgb(25, 195, 125); width: 24px; height: 24px;">
                        <x-square-ui.svg::chatgpt-logo/>
                    </div>
                    <p class="font-bold">ChatGPT 3.5</p>
                </div>
                <div class="prompt flex flex-col gap-2 p-4">
                    <div class="relative flex h-full flex-1 items-stretch md:flex-col mt-3">
                        <div class="flex w-full items-center">
                            <div class="relative w-full">
                                <textarea x-model="promptPrefix" id="prompt-textarea" tabindex="1"  rows="1" placeholder="Wat moet ik met de tekst doen?" class="block px-2.5 pb-2.5 pt-4  pe-20 w-full text-sm  bg-gray-800 rounded-lg border-1 appearance-none text-white border-gray-600 focus:border-white focus:outline-none focus:ring-0 focus:border-white peer resize-none"></textarea>
                                <label for="floating_outlined" class="absolute text-sm text-white duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-800 px-2 peer-focus:px-2 peer-focus:text-white  peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                    Wat moet ik met de tekst doen?
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="relative flex h-full flex-1 items-stretch md:flex-col mt-3">
                        <div class="flex w-full items-center">
                            <div class="relative w-full">
                                <textarea  x-ref="chatgpt_{{ $identifier }}_prompt" tabindex="0"  :rows="promptRows" x-model="prompt" class="block px-2.5 pb-2.5 pt-4  pe-20 w-full text-sm  bg-gray-800 rounded-lg border-1 appearance-none text-white border-gray-600 focus:border-white focus:outline-none focus:ring-0 focus:border-white peer resize-none" placeholder=" " @input="getLineCount()"></textarea>
                                <textarea  x-ref="chatgpt_{{ $identifier }}_prompt_preview" tabindex="-1"  rows="1" x-model="prompt" class="w-full opacity-0 absolute"></textarea>
                                <label for="floating_outlined" class="absolute text-sm text-white duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-800 px-2 peer-focus:px-2 peer-focus:text-white  peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Vraag het ChatGpt</label>
                                <button  :disabled="askingChatGpt" @click="askChatGPT()" class="text-white absolute end-1.5  bottom-1.5 disabled:bg-slate-400 bg-positive-500 hover:bg-positive-800 focus:ring-4 focus:outline-none focus:ring-positive-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <i x-show="askingChatGpt" class="fa-duotone fa-spinner-third fa-spin mr-2" x-cloak></i>
                                    Sturen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="chatGptResult != null" class="result  p-4">

                    <div class="flex flex-1 text-base mx-auto gap-3 md:px-5 lg:px-1 xl:px-5 md:max-w-3xl lg:max-w-[40rem] xl:max-w-[48rem] group final-completion">
                        <div class="flex-shrink-0 flex flex-col relative items-end"><div>
                                <div class="pt-0.5">
                                    <div class="gizmo-shadow-stroke flex h-6 w-6 items-center justify-center overflow-hidden rounded-full">
                                        <div class="relative p-1 rounded-sm h-9 w-9 text-white flex items-center justify-center" style="background-color: rgb(25, 195, 125); width: 24px; height: 24px;">
                                            <x-square-ui.svg::chatgpt-logo />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative flex w-full flex-col lg:w-[calc(100%-115px)] agent-turn">
                            <div class="font-semibold select-none mb-3">ChatGPT</div>
                            <div class="flex-col gap-1 md:gap-3">
                                <div class="markdown prose w-full break-words dark:prose-invert dark max-w-none">
                                    <div class="break-words">
                                        <div class="bg-black rounded-md flex flex-col gap-0">
                                            <div class="flex items-center relative text-gray-200 bg-gray-800 dark:bg-token-surface-primary px-4 py-2 text-xs font-sans justify-between rounded-t-md">
                                                <span>html</span>
                                                <div @click="useText()" class="flex gap-1 items-center cursor-pointer">
                                                    <x-square-ui.svg::chatgpt-copy/> Deze tekst gebruiken
                                                </div>
                                            </div>
                                            <div class="p-2 break-words text-white" x-html="chatGptResult"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @script
        <script>
            Alpine.data('ckEditor', (identifier, value, model, componentId) => ({
                identifier: identifier,
                value: value,
                model: model,
                componentId: componentId,
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
                                    uploadUrl: '{{ route('square-ui.file-upload') }}',

                                    // Enable the XMLHttpRequest.withCredentials property.
                                    withCredentials: true,

                                    // Headers sent along with the XMLHttpRequest to the upload server.
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
                    const route = @js(Route::has('square-ui.chat-gpt.ask') ? route('square-ui.chat-gpt.ask') : null);
                    if(route == null ) {
                        console.error('Error: Er bestaat geen route met de naam: "square-ui.chat-gpt.ask". Maak een post route aan met deze naam.')
                        return;
                    }

                    fetch(route, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
        </script>
    @endscript
</div>
