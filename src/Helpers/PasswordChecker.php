<?php

namespace Squareconcepts\SquareUi\Helpers;

use Illuminate\Support\Facades\Http;
use Livewire\Wireable;

class PasswordChecker implements Wireable
{
    public int $passwordStrength = 0;
    public string $passwordStrengthString = 'weak';
    public bool $hasCapitalLetter = false;
    public bool $hasSmallLetter = false;
    public bool $hasNumbers = false;
    public bool $hasSpecial = false;
    public bool $length = false;
    public bool $isUncompromised = false;
    public string $password;

    public static function checkPassword(string $password): PasswordChecker
    {
        $requiredCriteria = 5;
        $fulfilledCriteria = 0;
        $instance = new self();
        $instance->password = $password;

        if (strlen($password) >= 8) {
            $fulfilledCriteria++;
            $instance->length = true;
        }

        if (preg_match('/[A-Z]/', $password)) {
            $fulfilledCriteria++;
            $instance->hasCapitalLetter = true;
        }

        if (preg_match('/[a-z]/', $password)) {
            $fulfilledCriteria++;
            $instance->hasSmallLetter = true;
        }

        if (preg_match('/[0-9]/', $password)) {
            $fulfilledCriteria++;
            $instance->hasNumbers = true;
        }

        if (preg_match('/[\W_]/', $password)) {
            $fulfilledCriteria++;
            $instance->hasSpecial = true;
        }

        if (!empty($password) && !self::isPasswordCompromised($password)) {
            $fulfilledCriteria++;
            $instance->isUncompromised = true;
        }

        $instance->passwordStrength = ($fulfilledCriteria / $requiredCriteria) * 100;
        $instance->passwordStrengthString = self::calculatePasswordStrengthPercentage($password);

        return $instance;
    }

    public static function calculatePasswordStrength(string $password): mixed
    {
        $percentage = self::calculatePasswordStrengthPercentage($password);
        $lookupArray = [
            0 => 'weak',
            config('square-ui.password_strength_checker.good', 70) => 'good',
            config('square-ui.password_strength_checker.strong', 80) => 'strong',
            config('square-ui.password_strength_checker.very_strong', 90) => 'very strong',
        ];

        ksort($lookupArray);

        foreach (array_reverse($lookupArray, true) as $threshold => $strength) {
            if ($percentage >= $threshold) {
                return $strength;
            }
        }

        return 'weak';
    }

    public static function calculatePasswordStrengthPercentage(string $password): float|int
    {
        $requiredCriteria = 5;
        $fulfilledCriteria = 0;

        if (strlen($password) >= 8) {
            $fulfilledCriteria++;
        }

        if (preg_match('/[A-Z]/', $password)) {
            $fulfilledCriteria++;
        }

        if (preg_match('/[a-z]/', $password)) {
            $fulfilledCriteria++;
        }

        if (preg_match('/[0-9]/', $password)) {
            $fulfilledCriteria++;
        }

        if (preg_match('/[\W_]/', $password)) {
            $fulfilledCriteria++;
        }

        if (!empty($password) && !self::isPasswordCompromised($password)) {
            $fulfilledCriteria++;
        }

        return ($fulfilledCriteria / $requiredCriteria) * 100;
    }

    public static function isPasswordCompromised(string $password): bool
    {
        $hashedPassword = sha1($password);
        $prefix = substr($hashedPassword, 0, 5);
        $suffix = strtoupper(substr($hashedPassword, 5));

        try {
            $response = Http::get("https://api.pwnedpasswords.com/range/{$prefix}");
            foreach (explode("\r\n", (string) $response->body()) as $line) {
                $parts = explode(':', $line);
                if ($parts[0] === $suffix) {
                    return true;
                }
            }
        } catch (\Exception) {
            return false;
        }

        return false;
    }

    public function toLivewire(): array
    {
        return [
            'passwordStrength' => $this->passwordStrength,
            'passwordStrengthString' => $this->passwordStrengthString,
            'length' => $this->length,
            'hasNumbers' => $this->hasNumbers,
            'hasSpecial' => $this->hasSpecial,
            'hasSmallLetter' => $this->hasSmallLetter,
            'hasCapitalLetter' => $this->hasCapitalLetter,
            'isUncompromised' => $this->isUncompromised,
            'password' => $this->password,
        ];
    }

    public static function fromLivewire($value): static
    {
        $instance = new static();
        $instance->password = $value['password'];
        $instance->passwordStrength = $value['passwordStrength'];
        $instance->passwordStrengthString = $value['passwordStrengthString'];
        $instance->length = $value['length'];
        $instance->hasNumbers = $value['hasNumbers'];
        $instance->hasSpecial = $value['hasSpecial'];
        $instance->hasSmallLetter = $value['hasSmallLetter'];
        $instance->hasCapitalLetter = $value['hasCapitalLetter'];
        $instance->isUncompromised = $value['isUncompromised'];

        return $instance;
    }
}
