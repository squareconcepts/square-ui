<div x-data="dropzone('{{$model}}')"
     @dragover.prevent="isDragging = true"
     @dragleave="isDragging = false"
     @drop.prevent="isDragging = false; handleDroppedFiles($event.dataTransfer.files)"
     x-on:dropzone-reset.window="resetDropzone($event.detail)"
>
    <label :class="{ 'bg-slate-300': isDragging }" class="py-8 w-full bg-slate-200 flex items-center justify-center border-2 border-slate-300 border-dashed rounded cursor-pointer hover:bg-slate-300 text-slate-500 font-bold uppercase rounded-xl" for="{{$identifier}}">
        {{$multiple ? $dropzoneText : 'Sleep bestand hier of klik om te bladeren'}}
    </label>
   <template x-if="progress > 0">
       <div  class="my-2">
           <div class="flex justify-between mb-1" >
               <span class="text-base font-medium text-slate-700">Upload voortgang</span>
               <span class="text-sm font-medium text-slate-700" x-text="progress + '%'">0&</span>
           </div>
           <div class="w-full bg-slate-100 rounded-full h-2.5">
               <div class="bg-green-500 h-2.5 rounded-full w-0" :style="{width: progress + '%'}"></div>
           </div>
       </div>
   </template>
    <input id="{{$identifier}}" type="file" style="display: none" @change="handleDroppedFiles($event.target.files)" @if($multiple) multiple @endif {{$attributes['wire:model']}}>
    <div class="flex flex-col w-full leading-1.5 p-4 border-gray-200 bg-gray-100 rounded-xl mt-4" x-show="files.length > 0" x-cloak>
        <div class="flex items-center space-x-2 rtl:space-x-reverse mb-2">
            <span class="text-sm font-semibold text-gray-900 dark:text-white">Bestanden (<span x-text="files.length"></span>)</span>
        </div>
        @if($showPreview)
            <div class="grid gap-4 grid-cols-2 md:grid-cols-6 my-2.5">
                <template x-for="(file, index) in files" :key="index">
                    <div class="group relative flex items-center justify-center">
                        <div class="absolute w-full h-full bg-gray-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg flex items-center justify-center">
                            <button data-tooltip-target="download-image-1" class="inline-flex items-center justify-center rounded-full bg-white/30 hover:bg-white/50 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50" @click="removeImage(index)">
                                <i class="fa fa-trash text-white"></i>
                            </button>
                        </div>
                        <div class="flex w-full h-full items-center justify-center text-[40px]" x-show="isImage(file)" x-cloak>
                            <img  :src="URL.createObjectURL(file)" class="rounded-lg min-w-[50px] max-w-[250px] p-4" />
                        </div>
                        <div class="flex flex-col justify-center gap-2 items-center text-[40px]" x-show="!isImage(file)" x-html="fileIcon(file)" x-cloak>
                        </div>
                    </div>
                </template>
            </div>
        @endif
       @if($multiple || !$showPreview)
            <div class="flex gap-2 items-center">
                <button class="text-sm text-red-500 font-medium inline-flex items-center hover:underline gap-2" @click="removeImage(-1)">
                    <i class="fa fa-trash"></i> Alle verwijderen
                </button>
            </div>
        @endif
    </div>
</div>
@script
<script>
    Alpine.data('dropzone', (model) => ({
        model: model,
        multiple: @js($multiple),
        files: [],
        isDragging: false,
        hasBeenSubmitted: false,
        progress: 0,
        handleDroppedFiles(files) {
            if (files instanceof FileList) {
                if(this.multiple) {
                    for (let i = 0; i < files.length; i++) {
                        this.files.push(files[i]);
                    }
                } else {
                    this.files = [];
                    this.files.push(files[0]);
                }
            }
        },
        removeImage(index) {
            if(index === -1) {
                this.files = [];
            } else {
                this.files =this.files.filter((item, i) => {return i !== index;})
            }

        },
        isImage(file) {
            return file.type.startsWith('image/');
        },
        fileIcon(file) {
            const fileType = file.type.toLowerCase();
            const fileName = file.name.toLowerCase();
            let val = '';
            if (fileType.includes('pdf')) {
                val =  '<i class="fa-solid fa-file-pdf fa-3x"></i>';
            } else if (fileType.includes('word') || fileType.includes('wordprocessingml') || fileName.endsWith('.docx')) {
                val =  '<i class="fa-solid fa-file-word fa-3x"></i>';
            } else if (fileType.includes('excel') || fileType.includes('spreadsheetml') || fileName.endsWith('.xlsx')) {
                val =  '<i class="fa-solid fa-file-excel fa-3x"></i>';
            } else if (fileType.includes('powerpoint') || fileType.includes('presentationml') || fileName.endsWith('.pptx')) {
                val =  '<i class="fa-solid fa-file-powerpoint fa-3x"></i>';
            } else if (fileType.includes('image/')) {
                val =  '<i class="fa-solid fa-file-image fa-3x"></i>';
            } else if (fileType.includes('audio/')) {
                val =  '<i class="fa-solid fa-file-audio fa-3x"></i>';
            } else if (fileType.includes('video/')) {
                val =  '<i class="fa-solid fa-file-video fa-3x"></i>';
            } else if (fileType.includes('text/')) {
                val =  '<i class="fa-solid fa-file-alt fa-3x"></i>';
            } else if (fileName.endsWith('.zip') || fileName.endsWith('.rar')) {
                val =  '<i class="fa-solid fa-file-archive fa-3x"></i>';
            } else if (fileName.endsWith('.html')) {
                val =  '<i class="fa-brands fa-html5 fa-3x"></i>';
            } else if (fileName.endsWith('.css')) {
                val =  '<i class="fa-brands fa-css3 fa-3x"></i>';
            } else if (fileName.endsWith('.js')) {
                val =  '<i class="fa-brands fa-js-square fa-3x"></i>';
            } else if (fileName.endsWith('.json')) {
                val =  '<i class="fa-solid fa-file-code fa-3x"></i>';
            } else {
                val =  '<i class="fa-solid fa-file fa-3x"></i>';
            }
            val += '<span class="text-2xs">' + fileName + '</span>';
            return val;
        },
        uploadFiles() {
            $wire.uploadMultiple(this.model, this.files, (uploadedFilename) => {
                //success
            }, () => {
                //error
            }, (event) => {
                // Progress callback...
                // event.detail.progress contains a number between 1 and 100 as the upload progresses
                this.progress = event.detail.progress;
            }, () => {
                // Cancelled callback...
            });
        },
        init() {
            this.$watch('files', (value) => {
                this.uploadFiles();
            });
            this.$watch('progress', async () => {
                if (this.progress === 100) {
                    setTimeout(() => this.resetProgress(), 2000);
                }
            });
        },
        resetProgress() {
           this.progress = 0;

        },
        resetDropzone(event) {
           if(event.identifier === '{{$identifier}}') {
               this.progress = 0;
               this.files = [];
           }

        }

    }))
</script>
@endscript
