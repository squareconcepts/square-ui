@props(['language' => ''])

<div
    x-data="{
        copyToClipboard() {
            navigator.clipboard.writeText(this.$refs.code.innerText);
            this.$refs.button.innerText = 'Gekopieerd!';
            setTimeout(() => {
                this.$refs.button.innerHTML = `
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' viewBox='0 0 16 16'><path d='M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z'/><path d='M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zM9 2H7v.5h2V2z'/></svg>
                    <span>Kopieer</span>`;
            }, 2000);
        }
    }"
    class="code-block-container"
>

    <div class="code-block-header">
        @if ($language)
            <span class="code-block-language">{{ $language }}</span>
        @endif
        <button x-ref="button" @click="copyToClipboard()" class="code-block-copy-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/><path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zM9 2H7v.5h2V2z"/></svg>
            <span>Kopieer</span>
        </button>
    </div>

    <pre><code x-ref="code" class="code-block-content">{{ $slot }}</code></pre>
</div>
@push('styles')
    <style>
        /* Container voor het hele codeblok */
        .code-block-container {
            position: relative;
            background-color: #2d3748;
            color: #e2e8f0;
            border-radius: 0.5rem;
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
            font-family: 'Courier New', Courier, monospace;
        }

        /* Header voor taal en knop */
        .code-block-header {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem; /* Ruimte tussen taal en knop */
        }

        /* Weergave van de taal */
        .code-block-language {
            font-size: 0.8rem;
            color: #a0aec0;
            text-transform: uppercase;
            font-weight: bold;
            user-select: none; /* Maakt de tekst niet selecteerbaar */
        }

        /* De <pre> tag die de opmaak behoudt */
        .code-block-container pre {
            padding: 1.5rem;
            padding-top: 2.5rem; /* Extra padding bovenin om ruimte te maken voor de header */
            overflow-x: auto;
            white-space: pre;
        }

        /* De <code> tag zelf */
        .code-block-container code {
            font-size: 0.875rem;
        }

        /* De kopieer-knop */
        .code-block-copy-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #4a5568;
            color: #e2e8f0;
            border: none;
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.8rem;
            opacity: 0.7;
            transition: opacity 0.2s ease-in-out;
        }

        /* Stijl voor als je over de knop hovert */
        .code-block-container:hover .code-block-copy-button {
            opacity: 1;
        }
    </style>
@endpush
