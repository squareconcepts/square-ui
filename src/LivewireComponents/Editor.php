<?php

namespace Squareconcepts\SquareUi\LivewireComponents;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Editor extends Component
{
    #[Modelable] #[Rule('nullable')]
    public string $content = '';
    public string $label = '';
    public string $uploadUrl;
    public string $identifier;

    public function mount()
    {
        $this->uploadUrl = route('square-ui.file-upload');
    }

    public function render(): View
    {
        return view('square-ui::livewire-components.editor');
    }
}
