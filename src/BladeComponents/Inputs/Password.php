<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * <x-square-ui::inputs.password> / <x-square-ui.inputs::password>
 *
 * @deprecated 4.x Wordt verwijderd in 5.0. Gebruik <flux:input type="password" viewable> plus de validatieregel Password::defaults().
 *             Zie UPGRADE.md, "Deprecated in 4.x".
 */
class Password extends Component
{
    // withConfirmation/confirmationField worden (nog) niet gebruikt; ze blijven optioneel voor BC.
    public function __construct(public ?string $label = null, public bool $showPasswordStrength = true, public bool $withConfirmation = false, public ?string $confirmationField = null)
    {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.password');
    }
}
