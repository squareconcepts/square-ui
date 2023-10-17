<div {{ $attributes }} wire:ignore x-data="{
    signaturePadId: $id('signature'),
    signaturePad: null,
    @if (!empty($attributes['wire:model']))
    signature: @entangle($attributes->get('wire:model')).live,
    @else
    signature: '',
    @endif
    ratio: null,
    init() {
        this.resizeCanvas();
        this.signaturePad = new SignaturePad(this.$refs.canvas);
        if (this.signature) {
            this.signaturePad.fromDataURL(this.signature, { ratio: this.ratio });
        }
    },
    save() {
        const data = this.signaturePad.toData();
        if(data.length > 0){
            this.signature = this.signaturePad.toDataURL();
            this.$dispatch('signature-saved', this.signaturePadId);
        } else {
            this.signature = null;
         }
        this.$dispatch('signatureSaved')
    },
    clear() {
        this.signaturePad.clear();
        this.signature = null;
        this.$dispatch('signatureCleared')
    },
    resizeCanvas() {
        this.ratio = Math.max(window.devicePixelRatio || 1, 1);
        this.$refs.canvas.width = this.$refs.canvas.offsetWidth * this.ratio;
        this.$refs.canvas.height = this.$refs.canvas.offsetHeight * this.ratio;
        this.$refs.canvas.getContext('2d').scale(this.ratio, this.ratio);
    }
}" @resize.window="resizeCanvas" x-init="init" x-intersect="init">
    <div class="flex justify-between items-end mb-1">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{ $label }}
        </label>
    </div>
    <canvas x-ref="canvas"
            @class(['w-full h-full border-2 border-dashed rounded-md bg-white border-gray-300 max-w-[400px]']) style="height: 200px;"></canvas>
    <div class="flex mt-2 space-x-2">
        <x-square-ui::button :label="$clearText" x-on:click="clear()" />
        <x-square-ui::button :label="$saveButtonText" type="positive" x-on:click="save()" />
    </div>

</div>


@pushonce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
@endpushonce
