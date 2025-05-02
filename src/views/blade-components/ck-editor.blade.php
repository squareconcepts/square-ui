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
    <div x-data="ckEditor('{{ $identifier }}', @js(str_replace(['\n', '\r', '\r\n'], '<br>', $value)), '{{ $model }}', '{{ $componentId }}', {{ $debounceTime }})" x-init="initEditor" >
        <div x-show="!showHtml" x-cloak>
            <flux:textarea rows="auto"   x-ref="ckeditor_{{ $identifier }}" {{$attributes}}   />
        </div>
        <div x-show="showHtml" x-cloak>
            <flux:textarea rows="auto"  x-ref="ckeditor_{{ $identifier }}_preview" />
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
    <script type="module">
        Alpine.data('ckEditor', (identifier, value, model, componentId, debounceTime) => ({
            identifier: identifier,
            value: value,
            model: model,
            componentId: componentId,
            debounceTime: debounceTime,
            isInitialized: false,
            showHtml: false,
            showChatGpt: false,
            promptPrefixOption: ['Kun je van deze tekst een makkelijker te lezen sales ingestoken variant maken maar behoud de html zoals deze al in te tekst staat:', ''],
            promptPrefix: '',
            prompt: '',
            promptRows: 1,
            chatGptResult: null,
            askingChatGpt: false,
            debounceTimer: null,
            initEditor() {
                const {
                    ClassicEditor,
                    Alignment,
                    Autoformat,
                    AutoImage,
                    Autosave,
                    BlockQuote,
                    Bold,
                    Code,
                    CodeBlock,
                    Emoji,
                    Essentials,
                    Heading,
                    ImageBlock,
                    ImageCaption,
                    ImageInline,
                    ImageInsert,
                    ImageInsertViaUrl,
                    ImageResize,
                    ImageStyle,
                    ImageTextAlternative,
                    ImageToolbar,
                    ImageUpload,
                    Indent,
                    IndentBlock,
                    Italic,
                    Link,
                    LinkImage,
                    List,
                    ListProperties,
                    MediaEmbed,
                    Mention,
                    Paragraph,
                    PasteFromOffice,
                    SimpleUploadAdapter,
                    Table,
                    TableCaption,
                    TableCellProperties,
                    TableColumnResize,
                    TableProperties,
                    TableToolbar,
                    TextTransformation,
                    TodoList,
                    Underline
                } = window.CKEDITOR;

                const LICENSE_KEY =
                    @js(config('square-ui.ck_editor_license', 'GPL'));

                const editorConfig = {
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
                    toolbar: {
                        items: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            'code',
                            '|',
                            'emoji',
                            'link',
                            'insertImage',
                            'mediaEmbed',
                            'insertTable',
                            'blockQuote',
                            'codeBlock',
                            '|',
                            'alignment',
                            '|',
                            'bulletedList',
                            'numberedList',
                            'todoList',
                            'outdent',
                            'indent'
                        ],
                        shouldNotGroupWhenFull: false
                    },
                    plugins: [
                        Alignment,
                        Autoformat,
                        AutoImage,
                        Autosave,
                        BlockQuote,
                        Bold,
                        Code,
                        CodeBlock,
                        Emoji,
                        Essentials,
                        Heading,
                        ImageBlock,
                        ImageCaption,
                        ImageInline,
                        ImageInsert,
                        ImageInsertViaUrl,
                        ImageResize,
                        ImageStyle,
                        ImageTextAlternative,
                        ImageToolbar,
                        ImageUpload,
                        Indent,
                        IndentBlock,
                        Italic,
                        Link,
                        LinkImage,
                        List,
                        ListProperties,
                        MediaEmbed,
                        Mention,
                        Paragraph,
                        PasteFromOffice,
                        SimpleUploadAdapter,
                        Table,
                        TableCaption,
                        TableCellProperties,
                        TableColumnResize,
                        TableProperties,
                        TableToolbar,
                        TextTransformation,
                        TodoList,
                        Underline
                    ],
                    heading: {
                        options: [
                            {
                                model: 'paragraph',
                                title: 'Paragraph',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading1',
                                view: 'h1',
                                title: 'Heading 1',
                                class: 'ck-heading_heading1'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Heading 2',
                                class: 'ck-heading_heading2'
                            },
                            {
                                model: 'heading3',
                                view: 'h3',
                                title: 'Heading 3',
                                class: 'ck-heading_heading3'
                            },
                            {
                                model: 'heading4',
                                view: 'h4',
                                title: 'Heading 4',
                                class: 'ck-heading_heading4'
                            },
                            {
                                model: 'heading5',
                                view: 'h5',
                                title: 'Heading 5',
                                class: 'ck-heading_heading5'
                            },
                            {
                                model: 'heading6',
                                view: 'h6',
                                title: 'Heading 6',
                                class: 'ck-heading_heading6'
                            }
                        ]
                    },
                    image: {
                        toolbar: [
                            'toggleImageCaption',
                            'imageTextAlternative',
                            '|',
                            'imageStyle:inline',
                            'imageStyle:wrapText',
                            'imageStyle:breakText',
                            '|',
                            'resizeImage'
                        ]
                    },
                    initialData:
                        '<h2>Congratulations on setting up CKEditor 5! 🎉</h2>\n<p>\n\tYou\'ve successfully created a CKEditor 5 project. This powerful text editor\n\twill enhance your application, enabling rich text editing capabilities that\n\tare customizable and easy to use.\n</p>\n<h3>What\'s next?</h3>\n<ol>\n\t<li>\n\t\t<strong>Integrate into your app</strong>: time to bring the editing into\n\t\tyour application. Take the code you created and add to your application.\n\t</li>\n\t<li>\n\t\t<strong>Explore features:</strong> Experiment with different plugins and\n\t\ttoolbar options to discover what works best for your needs.\n\t</li>\n\t<li>\n\t\t<strong>Customize your editor:</strong> Tailor the editor\'s\n\t\tconfiguration to match your application\'s style and requirements. Or\n\t\teven write your plugin!\n\t</li>\n</ol>\n<p>\n\tKeep experimenting, and don\'t hesitate to push the boundaries of what you\n\tcan achieve with CKEditor 5. Your feedback is invaluable to us as we strive\n\tto improve and evolve. Happy editing!\n</p>\n<h3>Helpful resources</h3>\n<ul>\n\t<li>📝 <a href="https://portal.ckeditor.com/checkout?plan=free">Trial sign up</a>,</li>\n\t<li>📕 <a href="https://ckeditor.com/docs/ckeditor5/latest/installation/index.html">Documentation</a>,</li>\n\t<li>⭐️ <a href="https://github.com/ckeditor/ckeditor5">GitHub</a> (star us if you can!),</li>\n\t<li>🏠 <a href="https://ckeditor.com">CKEditor Homepage</a>,</li>\n\t<li>🧑‍💻 <a href="https://ckeditor.com/ckeditor-5/demo/">CKEditor 5 Demos</a>,</li>\n</ul>\n<h3>Need help?</h3>\n<p>\n\tSee this text, but the editor is not starting up? Check the browser\'s\n\tconsole for clues and guidance. It may be related to an incorrect license\n\tkey if you use premium features or another feature-related requirement. If\n\tyou cannot make it work, file a GitHub issue, and we will help as soon as\n\tpossible!\n</p>\n',
                    language: 'nl',
                    licenseKey: LICENSE_KEY,
                    link: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        decorators: {
                            toggleDownloadable: {
                                mode: 'manual',
                                label: 'Downloadable',
                                attributes: {
                                    download: 'file'
                                }
                            }
                        }
                    },
                    list: {
                        properties: {
                            styles: true,
                            startIndex: true,
                            reversed: true
                        }
                    },
                    mention: {
                        feeds: [
                            {
                                marker: '@',
                                feed: [
                                    /* See: https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html */
                                ]
                            }
                        ]
                    },
                    placeholder: 'Type or paste your content here!',
                    table: {
                        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
                    }
                };
                if(!this.isInitialized) {
                    ClassicEditor
                        .create(this.$refs['ckeditor_' + this.identifier], editorConfig)
                        .then(editor => {
                            this.editor = editor;
                            editor.setData(this.value);
                            this.prompt = this.value;
                            this.promptPrefix = this.promptPrefixOption[0];
                            this.getLineCount();
                            editor.model.document.on('change:data', () => {
                                clearInterval(this.debounceTimer);
                                this.debounceTimer = setTimeout(() => {
                                    this.value = editor.getData();
                                    this.prompt = this.value;
                                    this.getLineCount();
                                    if(this.componentId.length > 0) {
                                        Livewire.find(this.componentId).set(this.model, editor.getData());
                                    } else {
                                        @this.set(this.model, editor.getData());
                                    }
                                }, this.debounceTime);
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
    <style>
        .ck.ck-balloon-panel.ck-powered-by-balloon .ck.ck-powered-by{display: none!important;}
        .ck-editor h1, .ck-editor h2, .ck-editor h3, .ck-editor h4, .ck-editor h5, .ck-editor h6 {font-weight: bold}
        .ck-editor h1 {font-size: 1.8rem;}
        .ck-editor h2 {font-size: 1.5rem;}
        .ck-editor h3 {font-size: 1.4rem;}
        .ck-editor h4 {font-size: 1.3rem;}
        .ck-editor h5 {font-size: 1.2rem;}
        .ck-editor h6 {font-size: 1.1rem;}
        .ck-editor a {text-decoration: underline !important;}
        .ck-editor .ck-content ul {margin-left: 1rem;}

        /** Html content **/
        .html_content ol, .html_content ul:not(.todo-list) {display: block !important;list-style-type: decimal !important;margin-block-start: 1em !important;margin-block-end: 1em !important;margin-inline-start: 0 !important;margin-inline-end: 0 !important;padding-inline-start: 40px !important;}
        .html_content ol {list-style-type: decimal !important;}
        .html_content ol ol {list-style-type: lower-alpha !important;}
        .html_content ul:not(.todo-list):not(.ck-list) {list-style-type: disc !important;}
        .html_content ul:not(.todo-list):not(.ck-list) ul {list-style-type: circle !important;}
        .html_content a {color: #1d68cd;text-decoration: underline;}
        .html_content blockquote {padding: 1rem 2rem;border-left: 2px solid #ccc;}
        .html_content table {width: 100%;}
        .html_content table th, .html_content table td {border: 1px solid #ddd;}
        .html_content .marker-yellow {background-color: yellow;}
        .html_content .marker-green {background-color: lime;}
        .html_content .marker-pink {background-color: hotpink;}
        .html_content .marker-blue {background-color: cornflowerblue;}
        .html_content .pen-red {background-color: transparent;color: red;}
        .html_content .pen-green {background-color: transparent;color: green;}
        .html_content figure.media {width: 100%;margin-bottom: 2rem;}
        .html_content .todo-list__label input:checked ~ span {text-decoration: line-through;}
        .html_content .todo-list__label span {margin-left: 1rem;}
        .html_content hr {width: 100% !important;}
    </style>
</div>
