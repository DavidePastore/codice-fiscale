<?php

namespace CodiceFiscale;

/**
 * Description of Validator
 *
 * @author Antonio Turdo <antonio.turdo@gmail.com>
 */
class Validator extends AbstractCalculator
{
    private $regexs = array(
        0 => '/^[a-z]{6}[0-9]{2}[a-z][0-9]{2}[a-z][0-9]{3}[a-z]$/i',
        1 => '/^[a-z]{6}[0-9]{2}[a-z][0-9]{2}[a-z][0-9]{2}[a-z]{2}$/i',
        2 => '/^[a-z]{6}[0-9]{2}[a-z][0-9]{2}[a-z][0-9][a-z]{3}$/i',
        3 => '/^[a-z]{6}[0-9]{2}[a-z][0-9]{2}[a-z]{5}$/i',
        4 => '/^[a-z]{6}[0-9]{2}[a-z][0-9][a-z]{6}$/i',
        5 => '/^[a-z]{6}[0-9]{2}[a-z]{8}$/i',
        6 => '/^[a-z]{6}[0-9][a-z]{9}$/i',
        7 => '/^[a-z]{16}$/i',
    );
    private $omocodiaPositions = array(14, 13, 12, 10, 9, 7, 6);
    
    private $codiceFiscale;
    private $omocodiaAllowed = true;
    private $century = null;
    
    private $foundOmocodiaLevel = null;
    private $codiceFiscaleWithoutOmocodia = null;
    private $birthDate = null;
    private $gender = null;
    
    private $error = null;
    private $isValid = false;

    /**
     * Create a Validator instance.
     *
     * @param string $codiceFiscale the codice fiscale to validate
     * @param array $properties  An array with additional properties.
     */
    public function __construct($codiceFiscale, $properties = array())
    {
        $this->codiceFiscale = strtoupper($codiceFiscale);
        
        if (array_key_exists('omocodiaAllowed', $properties)) {
            $this->omocodiaAllowed = $properties['omocodiaAllowed'];
        }
        
        if (array_key_exists('century', $properties)) {
            $this->century = $properties['century'];
        }
        
        try {
            $this->validateLength();

            $this->validateFormat();

            $this->validateCheckDigit();
            
            $this->validateAndReplaceOmocodia();

            $this->validateBirthDateAndGender();

            $this->isValid = true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * Validates length
     *
     * @throws \Exception
     */
    private function validateLength()
    {
        // check empty
        if (empty($this->codiceFiscale)) {
            throw new \Exception('The codice fiscale to validate is empty');
        }

        // check length
        if (strlen($this->codiceFiscale) !== 16) {
            throw new \Exception('The codice fiscale to validate has an invalid length');
        }
    }

    /**
     * Validates format
     *
     * @throws \Exception
     */
    private function validateFormat()
    {
        $regexpValid = false;
        if (!$this->omocodiaAllowed) {
            // just one regex
            if (preg_match($this->regexs[0], $this->codiceFiscale)) {
                $this->foundOmocodiaLevel = 0;
                $regexpValid = true;
            }
        } else {
            // all the regex
            $omocodiaLevelApplied = 0;
            foreach ($this->regexs as $regex) {
                if (preg_match($regex, $this->codiceFiscale)) {
                    $this->foundOmocodiaLevel = $omocodiaLevelApplied;
                    $regexpValid = true;
                    break;
                }
                $omocodiaLevelApplied++;
            }
        }

        if (!$regexpValid) {
            throw new \Exception('The codice fiscale to validate has an invalid format');
        }
    }
    
    /**
     * Validates check digit
     *
     * @throws \Exception
     */
    private function validateCheckDigit()
    {
        $checkDigit = $this->calculateCheckDigit($this->codiceFiscale);
        if ($checkDigit != $this->codiceFiscale[15]) {
            throw new \Exception('The codice fiscale to validate has an invalid control character');
        }
    }
    
    /**
     * Validates omocodia and replace with matching chars
     *
     * @throws \Exception
     */
    private function validateAndReplaceOmocodia()
    {
        // check and replace omocodie
        $this->codiceFiscaleWithoutOmocodia = $this->codiceFiscale;
        for ($omocodiaCheck = 0; $omocodiaCheck < $this->foundOmocodiaLevel; $omocodiaCheck++) {
            $positionToCheck = $this->omocodiaPositions[$omocodiaCheck];
            $charToCheck = $this->codiceFiscaleWithoutOmocodia[$positionToCheck];
            if (!in_array($charToCheck, $this->omocodiaCodes)) {
                throw new \Exception('The codice fiscale to validate has an invalid character');
            }
            $this->codiceFiscaleWithoutOmocodia[$positionToCheck] = array_search($charToCheck, $this->omocodiaCodes);
        }
    }
    
    /**
     * Validates birthdate and gender
     *
     * @throws \Exception
     */
    private function validateBirthDateAndGender()
    {
        // calculate day and sex
        $day = (int) substr($this->codiceFiscaleWithoutOmocodia, 9, 2);
        $this->gender = $day > 40 ? self::CHR_WOMEN : self::CHR_MALE;

        if ($this->gender === self::CHR_WOMEN) {
            $day -= 40;
        }

        // check day
        if ($day > 31) {
            throw new \Exception('The codice fiscale to validate has invalid characters for birth day');
        }

        // check month
        $monthChar = substr($this->codiceFiscaleWithoutOmocodia, 8, 1);
        if (!in_array($monthChar, $this->months)) {
            throw new \Exception('The codice fiscale to validate has an invalid character for birth month');
        }
        
        // calculate month, year and century
        $month = array_search($monthChar, $this->months);
        $year = substr($this->codiceFiscaleWithoutOmocodia, 6, 2);
        $century = $this->calculateCentury($year);

        // validate and calculate birth date
        if (!checkdate($month, $day, $century.$year)) {
            throw new \Exception('The codice fiscale to validate has an non existent birth date');
        }
        
        $this->birthDate = new \DateTime();
        $this->birthDate->setDate($century.$year, $month, $day)->setTime(0,0,0)->format('Y-m-d');
    }
    
    /**
     *
     * @param string $year
     * @return string
     */
    private function calculateCentury($year)
    {
        $currentDate = new \DateTime();
        $currentYear = $currentDate->format('y');
        if (!is_null($this->century)) {
            $century = $this->century;
        } else {
            $currentCentury = substr($currentDate->format('Y'), 0, 2);
            $century = $year < $currentYear ? $currentCentury : $currentCentury - 1;
        }
        
        return $century;
    }

    /**
     * Return the validation error
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Return true if the provided codice fiscale is valid, false otherwise
     *
     * @return boolean
     */
    public function isFormallyValid()
    {
        return $this->isValid;
    }

    /**
     * Return true if the provided codice fiscale is an omocodia, false otherwise
     *
     * @return boolean
     */
    public function isOmocodia()
    {
        return $this->foundOmocodiaLevel > 0;
    }

    /**
     * Return the provided codice fiscale, cleaned up by omocodia
     *
     * @return string
     */
    protected function getCodiceFiscaleWithoutOmocodia()
    {
        return $this->codiceFiscaleWithoutOmocodia;
    }

    /**
     * Return the birth date
     *
     * @return \DateTime
     */
    protected function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Return the gender
     *
     * @return string
     */
    protected function getGender()
    {
        return $this->gender;
    }
}
