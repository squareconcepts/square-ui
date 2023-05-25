<?php
namespace Squareconcepts\SquareUi\Helpers;

use Illuminate\Support\Facades\Http;

class PasswordChecker implements \Livewire\Wireable
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

    public static function checkPassword(string $password) : PasswordChecker {
        // Aantal vereisten voor sterke wachtwoorden
        $requiredCriteria = 5;

        // Totaal aantal voldane vereisten
        $fulfilledCriteria = 0;

        // Criteria voor wachtwoordsterkte
        $lengthRequirement = 8;
        $uppercaseRequirement = true;
        $lowercaseRequirement = true;
        $numberRequirement = true;
        $specialCharRequirement = true;
        $uncompromisedRequirement = true;

        $instance = new self();
        $instance->password = $password;

        // Controleren op lengtevereiste
        if (strlen($password) >= $lengthRequirement) {
            $fulfilledCriteria++;
            $instance->length = true;
        }

        // Controleren op hoofdlettervereiste
        if ($uppercaseRequirement && preg_match('/[A-Z]/', $password)) {
            $fulfilledCriteria++;
            $instance->hasCapitalLetter = true;
        }

        // Controleren op kleine lettervereiste
        if ($lowercaseRequirement && preg_match('/[a-z]/', $password)) {
            $fulfilledCriteria++;
            $instance->hasSmallLetter = true;
        }

        // Controleren op cijfervereiste
        if ($numberRequirement && preg_match('/[0-9]/', $password)) {
            $fulfilledCriteria++;
            $instance->hasNumbers = true;
        }

        // Controleren op speciaal tekenvereiste
        if ($specialCharRequirement && preg_match('/[\W_]/', $password)) {
            $fulfilledCriteria++;
            $instance->hasSpecial = true;
        }

        // Controleren op compromisvereiste (uncompromised)
        if ($uncompromisedRequirement && !self::isPasswordCompromised($password)) {
            $fulfilledCriteria++;
            $instance->isUncompromised = true;
        }

        // Berekenen van het percentage van voldane vereisten
        $strengthPercentage = ($fulfilledCriteria / $requiredCriteria) * 100;
        $instance->passwordStrength = $strengthPercentage;
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
            config('square-ui.password_strength_checker.very_strong', 90) =>'very strong'
        ];

        // Sorteer de array oplopend op de score
        ksort($lookupArray);

        // Doorloop de array in omgekeerde volgorde en retourneer de eerste waarde waar de score lager dan of gelijk aan is
        foreach (array_reverse($lookupArray, true) as $threshold => $strength) {
            if ($percentage >= $threshold) {
                return $strength;
            }
        }
        return  'weak';

    }

    /**
     * Bereken het percentage van de wachtwoordsterkte.
     *
     * @param string $password
     * @return float|int
     */
   public static function calculatePasswordStrengthPercentage(string $password): float|int
   {
        // Aantal vereisten voor sterke wachtwoorden
        $requiredCriteria = 5;

        // Totaal aantal voldane vereisten
        $fulfilledCriteria = 0;

        // Criteria voor wachtwoordsterkte
        $lengthRequirement = 8;
        $uppercaseRequirement = true;
        $lowercaseRequirement = true;
        $numberRequirement = true;
        $specialCharRequirement = true;
        $uncompromisedRequirement = true;


        // Controleren op lengtevereiste
        if (strlen($password) >= $lengthRequirement) {
            $fulfilledCriteria++;

        }

        // Controleren op hoofdlettervereiste
        if ($uppercaseRequirement && preg_match('/[A-Z]/', $password)) {
            $fulfilledCriteria++;

        }

        // Controleren op kleine lettervereiste
        if ($lowercaseRequirement && preg_match('/[a-z]/', $password)) {
            $fulfilledCriteria++;

        }

        // Controleren op cijfervereiste
        if ($numberRequirement && preg_match('/[0-9]/', $password)) {
            $fulfilledCriteria++;

        }

        // Controleren op speciaal tekenvereiste
        if ($specialCharRequirement && preg_match('/[\W_]/', $password)) {
            $fulfilledCriteria++;

        }

        // Controleren op compromisvereiste (uncompromised)
        if ($uncompromisedRequirement && !empty($password) && !self::isPasswordCompromised($password)) {
            $fulfilledCriteria++;
        }

        // Berekenen van het percentage van voldane vereisten
        $strengthPercentage = ($fulfilledCriteria / $requiredCriteria) * 100;

        return $strengthPercentage;
    }

    /**
     * Controleer of het wachtwoord gecompromitteerd is door het te vergelijken met de Have I Been Pwned API.
     *
     * @param string $password
     * @return bool
     */
    public static function isPasswordCompromised(string $password): bool
    {
        $hashedPassword = sha1($password);
        $prefix = substr($hashedPassword, 0, 5);
        $suffix = strtoupper(substr($hashedPassword, 5));

        $apiUrl = "https://api.pwnedpasswords.com/range/{$prefix}";

        try {
            $response = Http::get($apiUrl);
            $responseBody = (string) $response->body();
            $responseLines = explode("\r\n", $responseBody);
            foreach ($responseLines as $line) {
                $lineParts = explode(":", $line);
                if ($lineParts[0] === $suffix) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            // Error handling
            return false;
        }

        return false;
    }

    public static function getInstance()
    {

    }

    public function toLivewire()
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

    public static function fromLivewire($value)
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