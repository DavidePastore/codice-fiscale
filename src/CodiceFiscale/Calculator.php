<?php

namespace CodiceFiscale;

use Normalizer;

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
     * @param array $properties An array with additional properties.
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
     * @returns string The complete codice fiscale.
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
     * @return array The list of all possibilities for the code fiscale.
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
     * @return string The surname part of the codice fiscale.
     */
    private function calculateSurname()
    {
        $surname = $this->cleanString($this->subject->getSurname());
        $consonants = str_replace($this->vowels, '', strtoupper($surname));
        if (strlen($consonants) > 2) {
            $result = substr($consonants, 0, 3);
        } else {
            $result = $this->calculateSmallString($consonants, $surname);
        }

        return $result;
    }

    /**
     * Calculate the name part of the codice fiscale.
     *
     * @return string The name part of the codice fiscale.
     */
    private function calculateName()
    {
        $name = $this->cleanString($this->subject->getName());
        $consonants = str_replace($this->vowels, '', strtoupper($name));
        if (strlen($consonants) > 3) {
            $result = $consonants[0].$consonants[2].$consonants[3];
        } elseif (strlen($consonants) == 3) {
            $result = $consonants;
        } else {
            $result = $this->calculateSmallString($consonants, $name);
        }

        return $result;
    }

    /**
     * Calculate small string for the given parameters (used by name and surname).
     *
     * @param string $consonants   A consonants string.
     * @param string $string  The small string.
     * @return string The calculated result for the small string.
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
     * @returns string The birth date and gender part of the codice fiscale.
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
     * @return string The Belfiore code.
     */
    private function calculateBelfioreCode()
    {
        return strtoupper($this->subject->getBelfioreCode());
    }

    /**
     * Calculate the omocodia case (additional translation).
     *
     * @param $temporaryCodiceFiscale string The first part of the codice fiscale.
     * @return string
     */
    private function calculateOmocodia($temporaryCodiceFiscale)
    {
        if ($this->omocodiaLevel > 0) {
            if ($this->omocodiaLevel) {
                $temporaryCodiceFiscale = $this->replaceOmocodiaSection(2, $temporaryCodiceFiscale, 1, 1, $this->omocodiaPositions[0]);
                $temporaryCodiceFiscale = $this->replaceOmocodiaSection(4, $temporaryCodiceFiscale, 2, 3, $this->omocodiaPositions[1]);
                $temporaryCodiceFiscale = $this->replaceOmocodiaSection(8, $temporaryCodiceFiscale, 4, 7, $this->omocodiaPositions[2]);
                $temporaryCodiceFiscale = $this->replaceOmocodiaSection(16, $temporaryCodiceFiscale, 8, 15, $this->omocodiaPositions[3]);
                $temporaryCodiceFiscale = $this->replaceOmocodiaSection(32, $temporaryCodiceFiscale, 16, 31, $this->omocodiaPositions[4]);
                $temporaryCodiceFiscale = $this->replaceOmocodiaSection(64, $temporaryCodiceFiscale, 32, 63, $this->omocodiaPositions[5]);
                $temporaryCodiceFiscale = $this->replaceOmocodiaSection(128, $temporaryCodiceFiscale, 64, 127, $this->omocodiaPositions[6]);
            }
        }

        return $temporaryCodiceFiscale;
    }

    /**
     * Replace a section of the omocodia.
     *
     * @param $divider int The divider.
     * @param $temporaryCodiceFiscale string The first part of the codice fiscale on which make the substitutions.
     * @param $startingIndex int The starting index.
     * @param $endingIndex int The ending index.
     * @param $characterIndex int The index to use to make the substitutions on the $temporaryCodiceFiscale.
     * @return string The temporary codice fiscale with the substitutions made.
     */
    private function replaceOmocodiaSection($divider, $temporaryCodiceFiscale, $startingIndex, $endingIndex, $characterIndex)
    {
        if ($this->omocodiaLevel % $divider >= $startingIndex && $this->omocodiaLevel % $divider <= $endingIndex) {
            $k = $temporaryCodiceFiscale[$characterIndex];
            $newChar = $this->omocodiaCodes[$k];
            $temporaryCodiceFiscale[$characterIndex] = $newChar;
        }
        return $temporaryCodiceFiscale;
    }

    /**
     * @param $string string The string to clean.
     * @return string Cleaned string
     */
    private function cleanString($string)
    {
        return preg_replace(array('/\pM*/u', '/[\s\'"`]+/'), '', Normalizer::normalize($string, Normalizer::FORM_D));
    }
}
