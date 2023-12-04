
document.addEventListener('alpine:init', () => {
    Alpine.data('editor', (content = '', identifier = '', uploadUrl = '') => ({
        content: content,
        editor: '',
        uploadUrl: uploadUrl,
        identifier: identifier,
        csrf_token: '',
        updateLivewire: function () {
            const event = new CustomEvent('updated-editor-' + this.identifier, { detail: this.content });
            document.dispatchEvent(event);
        },
        init: function () {

            if (document.querySelector('meta[name="csrf-token"]') != null) {
                this.csrf_token = document.querySelector('meta[name="csrf-token"]').content;
            } else {
                this.csrf_token = '';
            }

            this.editor = window.ClassicEditor
                .create(document.querySelector('#editor-' + identifier), {
                    heading: {
                        options: [
                            {model: 'paragraph', title: 'Paragraaf', class: 'ck-heading_paragraph'},
                            {model: 'heading2', view: 'h2', title: 'Kop 2', class: 'ck-heading_heading2'},
                            {model: 'heading3', view: 'h3', title: 'Kop 3', class: 'ck-heading_heading3'},
                            {model: 'heading4', view: 'h4', title: 'Kop 4', class: 'ck-heading_heading4'},
                            {model: 'heading5', view: 'h5', title: 'Kop 5', class: 'ck-heading_heading5'},
                            {model: 'heading6', view: 'h6', title: 'Kop 6', class: 'ck-heading_heading6'},
                        ]
                    },
                    mediaEmbed: {
                        previewsInData: true
                    },
                    removePlugins: ["MediaEmbedToolbar"],
                    simpleUpload: {
                        // The URL that the images are uploaded to.
                        uploadUrl: this.uploadUrl,

                        // Enable the XMLHttpRequest.withCredentials property.
                        withCredentials: true,

                        // Headers sent along with the XMLHttpRequest to the upload server.
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    }
                }).then( editor => {
                    editor.model.document.on('change:data', () => {
                        this.content =  editor.getData();
                        this.updateLivewire();
                    });
                } )
                .catch( error => {
                    // uncomment for debugging
                    // console.warn( error );
                } );
        }
    }));

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
})


