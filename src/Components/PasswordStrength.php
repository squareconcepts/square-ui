<?php

namespace Squareconcepts\SquareUi\Components;


use Livewire\Component;
use Squareconcepts\SquareUi\Helpers\PasswordChecker;

class PasswordStrength  extends Component
{

    public string $password;
    public PasswordChecker $passwordChecker;
    public string $passwordChangedEvent;


    protected function getListeners()
    {
        return [$this->passwordChangedEvent => 'passwordChanged'];
    }

    public function mount(string $password) {
        $this->password = $password;
        $this->passwordChecker = PasswordChecker::checkPassword($this->password);

    }
    public function render()
    {
        return view('square-ui::password-strength');
    }

    public function passwordChanged($password): void
    {
        $this->password = $password;
        $this->passwordChecker = PasswordChecker::checkPassword($this->password);
    }
}