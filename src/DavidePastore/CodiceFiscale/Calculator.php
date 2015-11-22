<?php

namespace DavidePastore\CodiceFiscale;

/**
 * Codice Fiscale calculator.
 *
 * @author davidepastore
 */
class Calculator
{
    private $subject;
    private $omocodiaLevel = 0;

    /**
     * Array of available vowels.
     */
    private $vowels = array('A', 'E', 'I', 'O', 'U');

    /**
     * Array of all available months.
     */
    private $months = array(
        '1' => 'A',
        '2' => 'B',
        '3' => 'C',
        '4' => 'D',
        '5' => 'E',
        '6' => 'H',
        '7' => 'L',
        '8' => 'M',
        '9' => 'P',
        '10' => 'R',
        '11' => 'S',
        '12' => 'T',
    );

    /**
     * Array of all avaialable odd characters.
     */
    private $odd = array(
        '0' => 1,
        '1' => 0,
        '2' => 5,
        '3' => 7,
        '4' => 9,
        '5' => 13,
        '6' => 15,
        '7' => 17,
        '8' => 19,
        '9' => 21,
        'A' => 1,
        'B' => 0,
        'C' => 5,
        'D' => 7,
        'E' => 9,
        'F' => 13,
        'G' => 15,
        'H' => 17,
        'I' => 19,
        'J' => 21,
        'K' => 2,
        'L' => 4,
        'M' => 18,
        'N' => 20,
        'O' => 11,
        'P' => 3,
        'Q' => 6,
        'R' => 8,
        'S' => 12,
        'T' => 14,
        'U' => 16,
        'V' => 10,
        'W' => 22,
        'X' => 25,
        'Y' => 24,
        'Z' => 23,
    );

    /**
     * Array of all avaialable even characters.
     */
    private $even = array(
        '0' => 0,
        '1' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        'A' => 0,
        'B' => 1,
        'C' => 2,
        'D' => 3,
        'E' => 4,
        'F' => 5,
        'G' => 6,
        'H' => 7,
        'I' => 8,
        'J' => 9,
        'K' => 10,
        'L' => 11,
        'M' => 12,
        'N' => 13,
        'O' => 14,
        'P' => 15,
        'Q' => 16,
        'R' => 17,
        'S' => 18,
        'T' => 19,
        'U' => 20,
        'V' => 21,
        'W' => 22,
        'X' => 23,
        'Y' => 24,
        'Z' => 25,
    );

    /**
     * Array of all avaialable omocodia characters.
     */
    private $omocodiaCodes = array(
        '0' => 'L',
        '1' => 'M',
        '2' => 'N',
        '3' => 'P',
        '4' => 'Q',
        '5' => 'R',
        '6' => 'S',
        '7' => 'T',
        '8' => 'U',
        '9' => 'V',
    );

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
        for ($i = 0; $i < 8; ++$i) {
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
     * @param $consonants A consonants string.
     * @param $string The small string.
     * @returns Returns the calculated result for the small string.
     */
    private function calculateSmallString($consonants, $string)
    {
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
        if (strtoupper($this->subject->getGender()) == 'F') {
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
     * Calculate the check digit.
     *
     * @param $temporaryCodiceFiscale The first part of the codice fiscale.
     * @returns Returns the check digit part of the codice fiscale.
     */
    private function calculateCheckDigit($temporaryCodiceFiscale)
    {
        $sumEven = $this->calculateSumByDictionary($temporaryCodiceFiscale, $this->even, 1);
        $sumOdd = $this->calculateSumByDictionary($temporaryCodiceFiscale, $this->odd, 0);

        return chr(($sumOdd + $sumEven) % 26 + 65);
    }

    /**
     * Calculate the sum by the given dictionary for the given temporary codice fiscale.
     *
     * @param $temporaryCodiceFiscale The temporary codice fiscale.
     * @param $dictionaryArray The dictionary array.
     * @param $i The start index value.
     * @returns Returns the sum by the given dictionary for the given temporary codice fiscale.
     */
    private function calculateSumByDictionary($temporaryCodiceFiscale, $dictionaryArray, $i)
    {
        $sum = 0;
        for (; $i < 15; $i = $i + 2) {
            $k = $temporaryCodiceFiscale{$i};
            $sum = $sum + $dictionaryArray[$k];
        }

        return $sum;
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
            $omocodiaLevelApplied = 0;
            for ($i = strlen($temporaryCodiceFiscale) - 1; $i > 0; --$i) {
                $k = $temporaryCodiceFiscale{$i};
                if ($omocodiaLevelApplied < $this->omocodiaLevel && is_numeric($k)) {
                    $newChar = $this->omocodiaCodes[$k];
                    $temporaryCodiceFiscale{$i}
                    = $newChar;
                    ++$omocodiaLevelApplied;
                }
            }
        }

        return $temporaryCodiceFiscale;
    }
}
