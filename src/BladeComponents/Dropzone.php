<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\View\Component;

/**
 * <x-square-ui::dropzone>
 *
 * @deprecated 4.x Wordt verwijderd in 5.0. Gebruik (Flux Pro) <flux:file-upload> met <flux:file-upload.dropzone>.
 *             Zie UPGRADE.md, "Deprecated in 4.x".
 */
class Dropzone extends Component
{
    public function __construct(
        public string $identifier,
        public string $model,
        // null = vertaalde standaardtekst (enkelvoud of meervoud, afhankelijk van $multiple).
        public ?string $dropzoneText = null,
        public bool $multiple = false,
        public bool $showPreview = true,
    ) {}

    public function render()
    {
        return view('square-ui::blade-components.dropzone');
    }
}
