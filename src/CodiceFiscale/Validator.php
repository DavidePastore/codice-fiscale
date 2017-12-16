<?php

namespace CodiceFiscale;

/**
 * Description of Validator
 *
 * @author Antonio Turdo <antonio.turdo@gmail.com>
 */
class Validator extends AbstractCalculator {

    private $error = null;
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
    private $foundOmocodiaLevel = null;
    private $codiceFiscaleWithoutOmocodia = null;
    private $birthDate = null;
    private $gender = null;
    private $isValid = false;

    /**
     * Create a Validator instance.
     *
     * @param string $codiceFiscale the codice fiscale to validate
     * @param boolean $omocodiaAllowed whether to accept or not omocodia
     */
    public function __construct($codiceFiscale, $omocodiaAllowed = true, $secular = false) {
        try {
            $normalizedCodiceFiscale = strtoupper($codiceFiscale);
            
            $this->validateLength($normalizedCodiceFiscale);

            $this->validateFormat($normalizedCodiceFiscale, $omocodiaAllowed);

            $this->validateCheckDigit($normalizedCodiceFiscale);
            
            $this->validateAndReplaceOmocodia($normalizedCodiceFiscale);

            $this->validateBirthDateAndGender($secular);

            $this->isValid = true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * Validates length
     * 
     * @param string $codiceFiscale
     * @throws \Exception
     */
    private function validateLength($codiceFiscale) {
        // check empty
        if (empty($codiceFiscale)) {
            throw new \Exception('empty');
        }

        // check length
        if (strlen($codiceFiscale) !== 16) {
            throw new \Exception('length');
        }
    }

    /**
     * Validates format 
     * 
     * @param string $codiceFiscale
     * @param boolean $omocodiaAllowed
     * @throws \Exception
     */
    private function validateFormat($codiceFiscale, $omocodiaAllowed) {
        $regexpValid = false;
        if (!$omocodiaAllowed) {
            // just one regex
            if (preg_match($this->regexs[0], $codiceFiscale)) {
                $this->foundOmocodiaLevel = 0;
                $regexpValid = true;
            }
        } else {
            // all the regex
            $omocodiaLevelApplied = 0;
            foreach ($this->regexs as $regex) {
                if (preg_match($regex, $codiceFiscale)) {
                    $this->foundOmocodiaLevel = $omocodiaLevelApplied;
                    $regexpValid = true;
                    break;
                }
                $omocodiaLevelApplied++;
            }
        }

        if (!$regexpValid) {
            throw new \Exception('format');
        }
    }
    
    /**
     * Validates check digit
     * 
     * @param string $codiceFiscale
     * @throws \Exception
     */
    private function validateCheckDigit($codiceFiscale) {
        $checkDigit = $this->calculateCheckDigit($codiceFiscale);
        if ($checkDigit != $codiceFiscale[15]) {
            throw new \Exception('checksum');
        }        
    }
    
    /**
     * Validates omocodia and replace with matching chars
     * 
     * @param string $codiceFiscale
     * @throws \Exception
     */
    private function validateAndReplaceOmocodia($codiceFiscale) {
        // check and replace omocodie
        $this->codiceFiscaleWithoutOmocodia = $codiceFiscale;
        for ($omocodiaCheck = 0; $omocodiaCheck < $this->foundOmocodiaLevel; $omocodiaCheck++) {
            $positionToCheck = $this->omocodiaPositions[$omocodiaCheck];
            $charToCheck = $this->codiceFiscaleWithoutOmocodia[$positionToCheck];
            if (!in_array($charToCheck, $this->omocodiaCodes)) {
                throw new \Exception('omocodia');
            }
            $this->codiceFiscaleWithoutOmocodia[$positionToCheck] = array_search($charToCheck, $this->omocodiaCodes);
        }
    }
    
    /**
     * Validates birthdate and gender
     * 
     * @throws \Exception
     */
    private function validateBirthDateAndGender($secular) {
        // calculate day and sex
        $day = (int) substr($this->codiceFiscaleWithoutOmocodia, 9, 2);
        $this->gender = $day > 40 ? self::CHR_WOMEN : self::CHR_MALE;

        if ($this->gender === self::CHR_WOMEN) {
            $day -= 40;
        }

        // check day
        if ($day > 31) {
            throw new \Exception('day');
        }

        // check month
        $monthChar = substr($this->codiceFiscaleWithoutOmocodia, 8, 1);
        if (!in_array($monthChar, $this->months)) {
            throw new \Exception('month');
        }   
        
        // calculate month
        $month = array_search($monthChar, $this->months);

        // calculate year
        $year = substr($this->codiceFiscaleWithoutOmocodia, 6, 2);

        // calculate century
        $currentDate = new \DateTime();
        $currentYear = $currentDate->format('y');
        $currentCentury = substr($currentDate->format('Y'), 0, 2);
        $century = $year < $currentYear && !$secular ? $currentCentury : $currentCentury - 1;

        // validate and calculate birth date
        if (!checkdate($month, $day, $century.$year)) {
            throw new \Exception('date');
        }
        
        $this->birthDate = new \DateTime();
        $this->birthDate->setDate($century.$year, $month, $day);
        $this->birthDate->setTime(0, 0, 0);        
    }    

    /**
     * Return the validation error
     * 
     * @return string
     */
    public function getError() {
        return $this->error;
    }

    /**
     * Return true if the provided codice fiscale is valid, false otherwise
     * 
     * @return boolean
     */
    public function isFormallyValid() {
        return $this->isValid;
    }

    /**
     * Return true if the provided codice fiscale is an omocodia, false otherwise
     * 
     * @return boolean
     */
    public function isOmocodia() {
        return $this->foundOmocodiaLevel > 0;
    }

    /**
     * Return the provided codice fiscale, cleaned up by omocodia
     * 
     * @return string
     */
    protected function getCodiceFiscaleWithoutOmocodia() {
        return $this->codiceFiscaleWithoutOmocodia;
    }

    /**
     * Return the birth date
     * 
     * @return \DateTime
     */
    protected function getBirthDate() {
        return $this->birthDate;
    }

    /**
     * Return the gender
     * 
     * @return string
     */
    public function getGender() {
        return $this->gender;
    }

}
