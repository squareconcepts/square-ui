<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

/**
 * @deprecated 4.x Wordt verwijderd in 5.0. Gebruik <flux:input type="file"> of (Flux Pro) <flux:file-upload>.
 *             Zie UPGRADE.md, "Deprecated in 4.x".
 */
class FileUpload extends Component
{
    public string $inputId;

    public function __construct(
        // null = vertaalde standaardtekst. Een file-input kent geen placeholder: zonder label wordt dit de aria-label.
        public ?string $placeholder = null,
        public bool $multiple = false,
        public ?string $label = null,
        ?string $id = null,
    ) {
        $this->placeholder ??= __('square-ui::square-ui.upload.select_file');
        $this->inputId = $id ?? 'file-upload-' . Str::uuid();
    }

    public function render(): View
    {
        return view('square-ui::blade-components.file-upload');
    }
}
