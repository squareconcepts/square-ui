<div
    x-data="{
        open: false,
        withTime: @js($withTime),
        withSeconds: @js($withSeconds),
        model: @entangle($attributes->wire('model')).live,

        calDate:  null,
        hours:    '00',
        minutes:  '00',
        seconds:  '00',
        _syncing: false,

        get displayString() {
            if (!this.calDate) return '';
            const [y, m, d] = this.calDate.split('-');
            let s = `${d}-${m}-${y}`;
            if (this.withTime) {
                s += ` ${this.hours}:${this.minutes}`;
                if (this.withSeconds) s += `:${this.seconds}`;
            }
            return s;
        },

        pad(n) {
            return String(parseInt(n) || 0).padStart(2, '0');
        },
        clamp(val, min, max) {
            const n = parseInt(val);
            return isNaN(n) ? min : Math.max(min, Math.min(max, n));
        },

        init() {
            if (this.model) this.parse(this.model);

            this.$watch('model', (v) => {
                if (this._syncing) return;
                this.parse(v);
            });

            this.$watch('calDate', () => {
                this.commit();
                if (!this.withTime) this.$nextTick(() => { this.open = false; });
            });
        },

        parse(value) {
            if (!value) { this.clear(false); return; }
            const str       = String(value);
            const spaceIdx  = str.indexOf(' ');
            this.calDate    = spaceIdx !== -1 ? str.slice(0, spaceIdx) : str;
            if (spaceIdx !== -1) {
                const parts     = str.slice(spaceIdx + 1).split(':');
                this.hours      = this.pad(parts[0] ?? 0);
                this.minutes    = this.pad(parts[1] ?? 0);
                this.seconds    = this.pad(parts[2] ?? 0);
            }
        },

        commit() {
            if (!this.calDate) { this.model = null; return; }
            this._syncing = true;
            let v = this.calDate;
            if (this.withTime) {
                v += ` ${this.hours}:${this.minutes}:${this.seconds}`;
            }
            this.model = v;
            this.$nextTick(() => { this._syncing = false; });
        },

        blurTime(field, min, max) {
            this[field] = this.pad(this.clamp(this[field], min, max));
            this.commit();
        },
        stepTime(field, min, max, delta) {
            const cur   = parseInt(this[field]) || 0;
            const range = max - min + 1;
            this[field] = this.pad(((cur + delta - min + range) % range) + min);
            this.commit();
        },
        _lastScroll: 0,
        scrollTime(field, min, max, event) {
            const now = Date.now();
            if (now - this._lastScroll < 120) return;
            this._lastScroll = now;
            const delta = event.deltaY < 0 ? 1 : -1;
            this.stepTime(field, min, max, delta);
        },

        clear(close = true) {
            this.calDate = null;
            this.hours   = '00';
            this.minutes = '00';
            this.seconds = '00';
            this._syncing = true;
            this.model = null;
            this.$nextTick(() => { this._syncing = false; });
            if (close) this.open = false;
        },

    }"
    @keydown.escape.window="open = false"
    class="relative"
>
    <flux:field>
        @if($label)
            <flux:label>{{ $label }}</flux:label>
        @endif

        <flux:input
            as="button"
            icon="calendar"
            class="cursor-pointer text-left"
            @click="open = !open"
            :id="$id"
        >
            <span x-show="displayString" x-text="displayString"></span>
            <span x-show="!displayString" class="text-zinc-400 dark:text-zinc-500">{{ $placeholder }}</span>
        </flux:input>

        @if($attributes->has('wire:model') || $attributes->has('wire:model.live'))
            <flux:error name="{{ $attributes->wire('model')->value() }}" />
        @endif
    </flux:field>

    <dialog
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.outside="open = false"
        wire:ignore
        x-cloak
        class="block mt-1 mx-0 self-start z-50 rounded-xl shadow-2xl
               bg-white dark:bg-zinc-900
               border border-zinc-200 dark:border-white/10
               p-4 space-y-3"
    >
        <flux:calendar x-model="calDate" />

        @if($withTime)
            <flux:separator :text="__('Tijd')" />

            <div class="flex items-center justify-center gap-1.5 py-1">
                <input
                    type="number"
                    x-model="hours"
                    @blur="blurTime('hours', 0, 23)"
                    @keydown.up.prevent="stepTime('hours', 0, 23, 1)"
                    @keydown.down.prevent="stepTime('hours', 0, 23, -1)"
                    @wheel.prevent="scrollTime('hours', 0, 23, $event)"
                    min="0" max="23"
                    maxlength="2"
                    placeholder="00"
                    class="w-11 py-1.5 text-center text-sm tabular-nums
                           rounded-lg border border-zinc-300 dark:border-zinc-600
                           bg-white dark:bg-zinc-800
                           text-zinc-900 dark:text-white
                           focus:outline-none focus:ring-2 focus:ring-accent-500
                           [appearance:textfield]
                           [&::-webkit-outer-spin-button]:appearance-none
                           [&::-webkit-inner-spin-button]:appearance-none"
                />
                <span class="text-zinc-400 dark:text-zinc-500 font-semibold select-none">:</span>
                <input
                    type="number"
                    x-model="minutes"
                    @blur="blurTime('minutes', 0, 59)"
                    @keydown.up.prevent="stepTime('minutes', 0, 59, 1)"
                    @keydown.down.prevent="stepTime('minutes', 0, 59, -1)"
                    @wheel.prevent="scrollTime('minutes', 0, 59, $event)"
                    min="0" max="59"
                    maxlength="2"
                    placeholder="00"
                    class="w-11 py-1.5 text-center text-sm tabular-nums
                           rounded-lg border border-zinc-300 dark:border-zinc-600
                           bg-white dark:bg-zinc-800
                           text-zinc-900 dark:text-white
                           focus:outline-none focus:ring-2 focus:ring-accent-500
                           [appearance:textfield]
                           [&::-webkit-outer-spin-button]:appearance-none
                           [&::-webkit-inner-spin-button]:appearance-none"
                />
                @if($withSeconds)
                    <span class="text-zinc-400 dark:text-zinc-500 font-semibold select-none">:</span>
                    <input
                        type="number"
                        x-model="seconds"
                        @blur="blurTime('seconds', 0, 59)"
                        @keydown.up.prevent="stepTime('seconds', 0, 59, 1)"
                        @keydown.down.prevent="stepTime('seconds', 0, 59, -1)"
                        @wheel.prevent="scrollTime('seconds', 0, 59, $event)"
                        min="0" max="59"
                        maxlength="2"
                        placeholder="00"
                        class="w-11 py-1.5 text-center text-sm tabular-nums
                               rounded-lg border border-zinc-300 dark:border-zinc-600
                               bg-white dark:bg-zinc-800
                               text-zinc-900 dark:text-white
                               focus:outline-none focus:ring-2 focus:ring-accent-500
                               [appearance:textfield]
                               [&::-webkit-outer-spin-button]:appearance-none
                               [&::-webkit-inner-spin-button]:appearance-none"
                    />
                @endif
            </div>
        @endif

        <flux:separator />
        <flux:button variant="danger" size="sm" @click="clear()" class="w-full">
            {{ __('Wissen') }}
        </flux:button>
    </dialog>
</div>
