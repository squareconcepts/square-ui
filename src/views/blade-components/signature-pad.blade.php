<div {{ $attributes }} wire:ignore x-data="{
    signaturePadId: $id('signature'),
    signaturePad: null,
    @if (!empty($attributes['wire:model']))
    signature: @entangle($attributes->get('wire:model')).live,
    @else
    signature: '',
    @endif
    ratio: null,
    width: 0,
    init() {
        {{-- Alpine roept init() zelf één keer aan; geen x-init/x-intersect meer (dat gaf meerdere SignaturePads en dubbele endStroke-listeners). --}}
        if (this.signaturePad) {
            return;
        }
        {{-- Eén keer per pagina laden, ook als de pad pas na een Livewire-update verschijnt (@pushonce werkte alleen met een scripts-stack in de layout en bij de eerste render). --}}
        window.scSignaturePadLoader ??= new Promise((resolve, reject) => {
            const js = document.createElement('script');
            js.src = 'https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js';
            js.onload = resolve;
            js.onerror = reject;
            document.head.append(js);
        });
        window.scSignaturePadLoader.then(() => this.setup());
    },
    setup() {
        if (this.signaturePad) {
            return;
        }
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
        const canvas = this.$refs.canvas;
        {{-- Onzichtbaar (bijv. in een gesloten modal/tab): niets doen, x-intersect probeert het opnieuw. --}}
        if (canvas.offsetWidth === 0) {
            return;
        }
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        if (this.signaturePad && canvas.offsetWidth === this.width && ratio === this.ratio) {
            return;
        }
        {{-- Canvas resizen wist de tekening: eerst bewaren, daarna opnieuw tekenen. --}}
        const data = this.signaturePad ? this.signaturePad.toData() : [];
        this.ratio = ratio;
        this.width = canvas.offsetWidth;
        canvas.width = canvas.offsetWidth * this.ratio;
        canvas.height = canvas.offsetHeight * this.ratio;
        canvas.getContext('2d').scale(this.ratio, this.ratio);
        if (this.signaturePad) {
            this.signaturePad.clear();
            if (data.length > 0) {
                this.signaturePad.fromData(data);
            } else if (this.signature) {
                this.signaturePad.fromDataURL(this.signature, { ratio: this.ratio });
            }
        }
    }
}" @resize.window="resizeCanvas()" x-intersect="resizeCanvas()">
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
            <flux:button variant="primary" color="green" @click="save()">
                {{$saveButtonText}}
            </flux:button>
        @endif
    </div>

</div>

