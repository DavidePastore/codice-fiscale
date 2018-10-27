<?php

namespace CodiceFiscale;

/**
 * Codice Fiscale calculator.
 *
 * @author davidepastore
 */
class Calculator extends AbstractCalculator
{
    private $subject;
    private $omocodiaLevel = 0;

    /**
     * Array of available vowels.
     */
    private $vowels = array('A', 'E', 'I', 'O', 'U');

    /**
     * Create a Codice Fiscale instance.
     *
     * @param Subject $subject The subject that will have the codice fiscale.
     * @param $properties An array with additional properties.
     */
    public function __construct(Subject $subject, $properties = array())
    {
        $this->subject = $subject;

        if (array_key_exists('omocodiaLevel', $properties)) {
            $this->omocodiaLevel = $properties['omocodiaLevel'];
        }
    }

    /**
     * Calculate the code fiscale.
     *
     * @returns Returns the complete codice fiscale.
     */
    public function calculate()
    {
        $temporaryCodiceFiscale = $this->calculateSurname().$this->calculateName().
               $this->calculateBirthDateAndGender().$this->calculateBelfioreCode();
        $temporaryCodiceFiscale = $this->calculateOmocodia($temporaryCodiceFiscale);

        return $temporaryCodiceFiscale.$this->calculateCheckDigit($temporaryCodiceFiscale);
    }

    /**
     * Calculate all possibilities for the code fiscale.
     *
     * @returns Returns the complete codice fiscale.
     */
    public function calculateAllPossibilities()
    {
        $allPossibilities = array();
        for ($i = 0; $i < 128; ++$i) {
            $this->omocodiaLevel = $i;
            $allPossibilities[] = $this->calculate();
        }

        return $allPossibilities;
    }

    /**
     * Calculate the surname part of the codice fiscale.
     *
     * @returns Returns the surname part of the codice fiscale.
     */
    private function calculateSurname()
    {
        $consonants = str_replace($this->vowels, '', strtoupper($this->subject->getSurname()));
        $consonants = $this->cleanString($consonants);
        if (strlen($consonants) > 2) {
            $result = substr($consonants, 0, 3);
        } else {
            $result = $this->calculateSmallString($consonants, $this->subject->getSurname());
        }

        return $result;
    }

    /**
     * Calculate the name part of the codice fiscale.
     *
     * @returns Returns the name part of the codice fiscale.
     */
    private function calculateName()
    {
        $consonants = str_replace($this->vowels, '', strtoupper($this->subject->getName()));
        $consonants = $this->cleanString($consonants);
        if (strlen($consonants) > 3) {
            $result = $consonants[0].$consonants[2].$consonants[3];
        } elseif (strlen($consonants) == 3) {
            $result = $consonants;
        } else {
            $result = $this->calculateSmallString($consonants, $this->subject->getName());
        }

        return $result;
    }

    /**
     * Calculate small string for the given parameters (used by name and surname).
     *
     * @param $consonants A consonants string.
     * @param $string The small string.
     * @returns Returns the calculated result for the small string.
     */
    private function calculateSmallString($consonants, $string)
    {
        $string = $this->cleanString($string);
        $vowels = str_replace(str_split($consonants), '', strtoupper($string));
        $result = substr($consonants.$vowels.'XXX', 0, 3);

        return $result;
    }

    /**
     * Calculate the birth date and the gender.
     *
     * @returns Returns the birth date and gender part of the codice fiscale.
     */
    private function calculateBirthDateAndGender()
    {
        $year = $this->subject->getBirthDate()->format('y');
        $month = $this->months[$this->subject->getBirthDate()->format('n')];
        $day = $this->subject->getBirthDate()->format('d');
        if (strtoupper($this->subject->getGender()) == self::CHR_WOMEN) {
            $day += 40;
        }

        return $year.$month.$day;
    }

    /**
     * Calculate the Belfiore code.
     *
     * @returns Returns the Belfiore code.
     */
    private function calculateBelfioreCode()
    {
        return strtoupper($this->subject->getBelfioreCode());
    }

    /**
     * Calculate the omocodia case (additional translation).
     *
     * @param $temporaryCodiceFiscale The first part of the codice fiscale.
     * @returns Returns the new codice fiscale.
     */
    private function calculateOmocodia($temporaryCodiceFiscale)
    {
        if ($this->omocodiaLevel > 0) {
            if ($this->omocodiaLevel) {
                if ($this->omocodiaLevel % 2 == 1) {
                    $k = $temporaryCodiceFiscale{14};
                    $newChar = $this->omocodiaCodes[$k];
                    $temporaryCodiceFiscale{14} = $newChar;
                }

                if ($this->omocodiaLevel % 4 == 2 || $this->omocodiaLevel % 4 == 3) {
                    $k = $temporaryCodiceFiscale{13};
                    $newChar = $this->omocodiaCodes[$k];
                    $temporaryCodiceFiscale{13} = $newChar;
                }

                if ($this->omocodiaLevel % 8 >= 4 && $this->omocodiaLevel % 8 <= 7) {
                    $k = $temporaryCodiceFiscale{12};
                    $newChar = $this->omocodiaCodes[$k];
                    $temporaryCodiceFiscale{12} = $newChar;
                }

                if ($this->omocodiaLevel % 16 >= 8 && $this->omocodiaLevel % 16 <= 15) {
                    $k = $temporaryCodiceFiscale{10};
                    $newChar = $this->omocodiaCodes[$k];
                    $temporaryCodiceFiscale{10} = $newChar;
                }

                if ($this->omocodiaLevel % 32 >= 16 && $this->omocodiaLevel % 32 <= 31) {
                    $k = $temporaryCodiceFiscale{9};
                    $newChar = $this->omocodiaCodes[$k];
                    $temporaryCodiceFiscale{9} = $newChar;
                }

                if ($this->omocodiaLevel % 64 >= 32 && $this->omocodiaLevel % 64 <= 63) {
                    $k = $temporaryCodiceFiscale{7};
                    $newChar = $this->omocodiaCodes[$k];
                    $temporaryCodiceFiscale{7} = $newChar;
                }

                if ($this->omocodiaLevel % 128 >= 64 && $this->omocodiaLevel % 128 <= 127) {
                    $k = $temporaryCodiceFiscale{6};
                    $newChar = $this->omocodiaCodes[$k];
                    $temporaryCodiceFiscale{6} = $newChar;
                }
            }
        }

        return $temporaryCodiceFiscale;
    }

    /**
     * Clean the string removing some characters.
     *
     * @param $string The string to clean.
     * @returns Returns a clean string.
     */
    private function cleanString($string)
    {
        return preg_replace('/[\s\'"`]+/', '', $string);
    }
}
