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
        @if($autoSave)
            this.signaturePad.addEventListener('endStroke', () =>  this.save());
        @endif
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
        <flux:subheading>
            {{ $label }}
        </flux:subheading>

    </div>
    <canvas x-ref="canvas"
            @class(['w-full h-full border rounded-md bg-white border-zinc-100 ']) style="height: 200px;"></canvas>
    <div class="flex space-x-2 mt-3">
        <flux:button  variant="danger" @click="clear()" icon="trash">
            {{$clearText}}
        </flux:button>
        @if(!$autoSave)
            <flux:button variant="positive" @click="save()">
                {{$saveButtonText}}
            </flux:button>
        @endif
    </div>

</div>


@pushonce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
@endpushonce
