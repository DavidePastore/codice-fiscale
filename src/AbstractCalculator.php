<?php

namespace CodiceFiscale;

/**
 * Codice Fiscale abstract calculator.
 *
 * @author Antonio Turdo <antonio.turdo@gmail.com>
 * @author davidepastore
 */
abstract class AbstractCalculator
{
    // women char
    protected const CHR_WOMEN = 'F';

    // male char
    protected const CHR_MALE = 'M';

    /**
     * Array of all available months.
     */
    protected $months = array(
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
    protected $odd = array(
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
    protected $even = array(
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
    protected $omocodiaCodes = array(
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
     * Array of all avaialable omocodia positions.
     */
    protected $omocodiaPositions = array(14, 13, 12, 10, 9, 7, 6);

    /**
     * Calculate the sum by the given dictionary for the given temporary codice fiscale.
     *
     * @param string $temporaryCodiceFiscale The temporary codice fiscale.
     * @param array $dictionaryArray The dictionary array
     * @param int $i The start index value.
     * @return int
     */
    protected function calculateSumByDictionary($temporaryCodiceFiscale, $dictionaryArray, $i)
    {
        $sum = 0;
        for (; $i < 15; $i = $i + 2) {
            $k = $temporaryCodiceFiscale[$i];
            $sum = $sum + $dictionaryArray[$k];
        }

        return $sum;
    }

    /**
     * Calculate the check digit.
     *
     * @param string $temporaryCodiceFiscale The first part of the codice fiscale.
     * @return string The check digit part of the codice fiscale.
     */
    protected function calculateCheckDigit($temporaryCodiceFiscale)
    {
        $sumEven = $this->calculateSumByDictionary($temporaryCodiceFiscale, $this->even, 1);
        $sumOdd = $this->calculateSumByDictionary($temporaryCodiceFiscale, $this->odd, 0);

        return chr(($sumOdd + $sumEven) % 26 + 65);
    }
}
