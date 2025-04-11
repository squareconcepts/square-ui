@php
    $placeholder = $attributes->has('placeholder') ? $attributes->get('placeholder') : $label;
@endphp

<div class="flex flex-col mb-4  {{$attributes->has('disabled') ? 'opacity-60' : ''}}"
     @keydown.escape.window="open = false"
     x-data="ScPasswordInput">
    <div class="relative ">
        <flux:input type="password"  x-model="value"  label="{{$label}}" placeholder="{{$placeholder}}"  viewable="true" @click="toggle()"
        >
            <x-slot name="icon">

                <template x-if="strength < 4">
                    <flux:icon.check-circle />
                </template>

                <template x-if="value && strength == 4">
                    <flux:icon.check-circle class="text-green-500" />
                </template>

            </x-slot>
        </flux:input>
        @if($showPasswordStrength)
            <dialog
                x-ref="dialog"
                x-show="open"
                x-transition
                @click.outside="open = false"
                wire:ignore
                wire:cloak
                class="max-sm:max-h-full! rounded-xl shadow-xl sm:shadow-2xs max-sm:fixed! max-sm:inset-0! sm:backdrop:bg-transparent bg-white dark:bg-zinc-900 sm:border border-zinc-200 dark:border-white/10 block  mt-1 self-start mx-0 p-4 z-50"
            >
                <div class="flex gap-1 h-2 mt-1 max-w-96">
                    <template x-for="i in 4">
                        <div
                            class="flex-1 rounded"
                            :class="{
                            'bg-gray-200': strength < i,
                            'bg-red-500': strength === 1 && i === 1,
                            'bg-orange-400': strength === 2 && i <= 2,
                            'bg-yellow-400': strength === 3 && i <= 3,
                            'bg-green-500': strength === 4
                        }"
                        ></div>
                    </template>
                </div>

                <!-- Regels -->
                <ul class="text-sm space-y-1 mt-2">
                    <li :class="ruleClasses.hasMinLength" class="flex gap-1 items-center"> <flux:icon.check variant="micro" /> Minimaal 8 tekens</li>
                    <li :class="ruleClasses.hasLowercase" class="flex gap-1 items-center"> <flux:icon.check variant="micro" /> Minimaal 1 kleine letter</li>
                    <li :class="ruleClasses.hasUppercase" class="flex gap-1 items-center"> <flux:icon.check variant="micro" /> Minimaal 1 hoofdletter</li>
                    <li :class="ruleClasses.hasSpecial" class="flex gap-1 items-center"> <flux:icon.check variant="micro" /> Minimaal 1 speciaal teken</li>
                </ul>
                <flux:button variant="primary" class="w-full mt-3" @click="generatePassword()"> Wachtwoord genereren</flux:button>
            </dialog>
        @endif

    </div>



    <!-- Balkjes -->


</div>

@script
<script>
    Alpine.data('ScPasswordInput', () => ({
        open: false,
        value:  $wire.entangle('{{ $attributes['wire:model'] }}').live,
        showPasswordStrength: @js($showPasswordStrength),
        toggle() {
            this.open = ! this.open
        },
        generatePassword() {
            const specials = '!@#$%^&*()_+{}[]:;<>,.?~';
            const lowercase = 'abcdefghijklmnopqrstuvwxyz';
            const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const numbers = '0123456789';

            const getRandom = (chars) => chars[Math.floor(Math.random() * chars.length)];

            let password = [
                getRandom(lowercase),
                getRandom(uppercase),
                getRandom(specials),
                getRandom(numbers),
            ];

            const all = lowercase + uppercase + specials + numbers;

            while (password.length < 12) {
                password.push(getRandom(all));
            }

            // shuffle het wachtwoord
            password = password.sort(() => Math.random() - 0.5).join('');

            this.value = password;
        },
        init() {
        },
        get strength() {
            return this.rulesMet().length;
        },
        rulesMet() {
            return [
                this.value.length >= 8,
                /[a-z]/.test(this.value),
                /[A-Z]/.test(this.value),
                /[^A-Za-z0-9]/.test(this.value),
            ].filter(Boolean);
        },
        get ruleClasses() {
            return {
                hasMinLength: this.value.length >= 8 ? 'text-green-600' : 'text-gray-400',
                hasLowercase: /[a-z]/.test(this.value) ? 'text-green-600' : 'text-gray-400',
                hasUppercase: /[A-Z]/.test(this.value) ? 'text-green-600' : 'text-gray-400',
                hasSpecial: /[^A-Za-z0-9]/.test(this.value) ? 'text-green-600' : 'text-gray-400',
            };
        },

    }))
</script>
@endscript

