<?php

namespace CodiceFiscale;

use DateTime;
use Exception;

/**
 * Description of Validator
 *
 * @author Antonio Turdo <antonio.turdo@gmail.com>
 * @author davidepastore
 */
class Validator extends AbstractCalculator
{
    private $regexs = array(
        0 => '/^[a-z]{6}[0-9]{2}[a-z][0-9]{2}[a-z][0-9]{3}[a-z]$/i', //RSSMRA85T10A562S
        1 => '/^[a-z]{6}[0-9]{2}[a-z][0-9]{2}[a-z][0-9]{2}[a-z]{2}$/i', //RSSMRA85T10A56NH
        2 => '/^[a-z]{6}[0-9]{2}[a-z][0-9]{2}[a-z][0-9][a-z][0-9][a-z]$/i', //RSSMRA85T10A5S2E
        3 => '/^[a-z]{6}[0-9]{2}[a-z][0-9]{2}[a-z][0-9][a-z]{3}$/i', //RSSMRA85T10A5SNT
        4 => '/^[a-z]{6}[0-9]{2}[a-z][0-9]{2}[a-z]{2}[0-9]{2}[a-z]$/i', //RSSMRA85T10AR62N
        5 => '/^[a-z]{6}[0-9]{2}[a-z][0-9]{2}[a-z]{2}[0-9][a-z]{2}$/i', //RSSMRA85T10AR6NC
        6 => '/^[a-z]{6}[0-9]{2}[a-z][0-9]{2}[a-z]{3}[0-9][a-z]$/i', //RSSMRA85T10ARS2Z
        7 => '/^[a-z]{6}[0-9]{2}[a-z][0-9]{2}[a-z]{5}$/i', //RSSMRA85T10ARSNO
        8 => '/^[a-z]{6}[0-9]{2}[a-z][0-9][a-z]{2}[0-9]{3}[a-z]$/i', //RSSMRA85T1LA562V
        9 => '/^[a-z]{6}[0-9]{2}[a-z][0-9][a-z]{2}[0-9]{2}[a-z]{2}$/i', //RSSMRA85T1LA56NK
        10 => '/^[a-z]{6}[0-9]{2}[a-z][0-9][a-z]{2}[0-9][a-z][0-9][a-z]$/i', //RSSMRA85T1LA5S2H
        11 => '/^[a-z]{6}[0-9]{2}[a-z][0-9][a-z]{2}[0-9][a-z]{3}$/i', //RSSMRA85T1LA5SNW
        12 => '/^[a-z]{6}[0-9]{2}[a-z][0-9][a-z]{3}[0-9]{2}[a-z]$/i', //RSSMRA85T1LAR62Q
        13 => '/^[a-z]{6}[0-9]{2}[a-z][0-9][a-z]{3}[0-9][a-z]{2}$/i', //RSSMRA85T1LAR6NF
        14 => '/^[a-z]{6}[0-9]{2}[a-z][0-9][a-z]{4}[0-9][a-z]$/i', //RSSMRA85T1LARS2C
        15 => '/^[a-z]{6}[0-9]{2}[a-z][0-9][a-z]{6}$/i', //RSSMRA85T1LARSNR
        16 => '/^[a-z]{6}[0-9]{2}[a-z]{2}[0-9][a-z][0-9]{3}[a-z]$/i', //RSSMRA85TM0A562D
        17 => '/^[a-z]{6}[0-9]{2}[a-z]{2}[0-9][a-z][0-9]{2}[a-z]{2}$/i', //RSSMRA85TM0A56NS
        18 => '/^[a-z]{6}[0-9]{2}[a-z]{2}[0-9][a-z][0-9][a-z][0-9][a-z]$/i', //RSSMRA85TM0A5S2P
        19 => '/^[a-z]{6}[0-9]{2}[a-z]{2}[0-9][a-z][0-9][a-z]{3}$/i', //RSSMRA85TM0A5SNE
        20 => '/^[a-z]{6}[0-9]{2}[a-z]{2}[0-9][a-z]{2}[0-9]{2}[a-z]$/i', //RSSMRA85TM0AR62Y
        21 => '/^[a-z]{6}[0-9]{2}[a-z]{2}[0-9][a-z]{2}[0-9][a-z]{2}$/i', //RSSMRA85TM0AR6NN
        22 => '/^[a-z]{6}[0-9]{2}[a-z]{2}[0-9][a-z]{3}[0-9][a-z]$/i', //RSSMRA85TM0ARS2K
        23 => '/^[a-z]{6}[0-9]{2}[a-z]{2}[0-9][a-z]{5}$/i', //RSSMRA85TM0ARSNZ
        24 => '/^[a-z]{6}[0-9]{2}[a-z]{4}[0-9]{3}[a-z]$/i', //RSSMRA85TMLA562G
        25 => '/^[a-z]{6}[0-9]{2}[a-z]{4}[0-9]{2}[a-z]{2}$/i', //RSSMRA85TMLA56NV
        26 => '/^[a-z]{6}[0-9]{2}[a-z]{4}[0-9][a-z][0-9][a-z]$/i', //RSSMRA85TMLA5S2S
        27 => '/^[a-z]{6}[0-9]{2}[a-z]{4}[0-9][a-z]{3}$/i', //RSSMRA85TMLA5SNH
        28 => '/^[a-z]{6}[0-9]{2}[a-z]{5}[0-9]{2}[a-z]$/i', //RSSMRA85TMLAR62B
        29 => '/^[a-z]{6}[0-9]{2}[a-z]{5}[0-9][a-z]{2}$/i', //RSSMRA85TMLAR6NQ
        30 => '/^[a-z]{6}[0-9]{2}[a-z]{6}[0-9][a-z]$/i', //RSSMRA85TMLARS2N
        31 => '/^[a-z]{6}[0-9]{2}[a-z]{8}$/i', //RSSMRA85TMLARSNC
        32 => '/^[a-z]{6}[0-9][a-z]{2}[0-9]{2}[a-z][0-9]{3}[a-z]$/i', //RSSMRA8RT10A562E
        33 => '/^[a-z]{6}[0-9][a-z]{2}[0-9]{2}[a-z][0-9]{2}[a-z]{2}$/i', //RSSMRA8RT10A56NT
        34 => '/^[a-z]{6}[0-9][a-z]{2}[0-9]{2}[a-z][0-9][a-z][0-9][a-z]$/i', //RSSMRA8RT10A5S2Q
        35 => '/^[a-z]{6}[0-9][a-z]{2}[0-9]{2}[a-z][0-9][a-z]{3}$/i', //RSSMRA8RT10A5SNF
        36 => '/^[a-z]{6}[0-9][a-z]{2}[0-9]{2}[a-z]{2}[0-9]{2}[a-z]$/i', //RSSMRA8RT10AR62Z
        37 => '/^[a-z]{6}[0-9][a-z]{2}[0-9]{2}[a-z]{2}[0-9][a-z]{2}$/i', //RSSMRA8RT10AR6NO
        38 => '/^[a-z]{6}[0-9][a-z]{2}[0-9]{2}[a-z]{3}[0-9][a-z]$/i', //RSSMRA8RT10ARS2L
        39 => '/^[a-z]{6}[0-9][a-z]{2}[0-9]{2}[a-z]{5}$/i', //RSSMRA8RT10ARSNA
        40 => '/^[a-z]{6}[0-9][a-z]{2}[0-9][a-z]{2}[0-9]{3}[a-z]$/i', //RSSMRA8RT1LA562H
        41 => '/^[a-z]{6}[0-9][a-z]{2}[0-9][a-z]{2}[0-9]{2}[a-z]{2}$/i', //RSSMRA8RT1LA56NW
        42 => '/^[a-z]{6}[0-9][a-z]{2}[0-9][a-z]{2}[0-9][a-z][0-9][a-z]$/i', //RSSMRA8RT1LA5S2T
        43 => '/^[a-z]{6}[0-9][a-z]{2}[0-9][a-z]{2}[0-9][a-z]{3}$/i', //RSSMRA8RT1LA5SNI
        44 => '/^[a-z]{6}[0-9][a-z]{2}[0-9][a-z]{3}[0-9]{2}[a-z]$/i', //RSSMRA8RT1LAR62C
        45 => '/^[a-z]{6}[0-9][a-z]{2}[0-9][a-z]{3}[0-9][a-z]{2}$/i', //RSSMRA8RT1LAR6NR
        46 => '/^[a-z]{6}[0-9][a-z]{2}[0-9][a-z]{4}[0-9][a-z]$/i', //RSSMRA8RT1LARS2O
        47 => '/^[a-z]{6}[0-9][a-z]{2}[0-9][a-z]{6}$/i', //RSSMRA8RT1LARSND
        48 => '/^[a-z]{6}[0-9][a-z]{3}[0-9][a-z][0-9]{3}[a-z]$/i', //RSSMRA8RTM0A562P
        49 => '/^[a-z]{6}[0-9][a-z]{3}[0-9][a-z][0-9]{2}[a-z]{2}$/i', //RSSMRA8RTM0A56NE
        50 => '/^[a-z]{6}[0-9][a-z]{3}[0-9][a-z][0-9][a-z][0-9][a-z]$/i', //RSSMRA8RTM0A5S2B
        51 => '/^[a-z]{6}[0-9][a-z]{3}[0-9][a-z][0-9][a-z]{3}$/i', //RSSMRA8RTM0A5SNQ
        52 => '/^[a-z]{6}[0-9][a-z]{3}[0-9][a-z]{2}[0-9]{2}[a-z]$/i', //RSSMRA8RTM0AR62K
        53 => '/^[a-z]{6}[0-9][a-z]{3}[0-9][a-z]{2}[0-9][a-z]{2}$/i', //RSSMRA8RTM0AR6NZ
        54 => '/^[a-z]{6}[0-9][a-z]{3}[0-9][a-z]{3}[0-9][a-z]$/i', //RSSMRA8RTM0ARS2W
        55 => '/^[a-z]{6}[0-9][a-z]{3}[0-9][a-z]{5}$/i', //RSSMRA8RTM0ARSNL
        56 => '/^[a-z]{6}[0-9][a-z]{5}[0-9]{3}[a-z]$/i', //RSSMRA8RTMLA562S
        57 => '/^[a-z]{6}[0-9][a-z]{5}[0-9]{2}[a-z]{2}$/i', //RSSMRA8RTMLA56NH
        58 => '/^[a-z]{6}[0-9][a-z]{5}[0-9][a-z][0-9][a-z]$/i', //RSSMRA8RTMLA5S2E
        59 => '/^[a-z]{6}[0-9][a-z]{5}[0-9][a-z]{3}$/i', //RSSMRA8RTMLA5SNT
        60 => '/^[a-z]{6}[0-9][a-z]{6}[0-9]{2}[a-z]$/i', //RSSMRA8RTMLAR62N
        61 => '/^[a-z]{6}[0-9][a-z]{6}[0-9][a-z]{2}$/i', //RSSMRA8RTMLAR6NC
        62 => '/^[a-z]{6}[0-9][a-z]{7}[0-9][a-z]$/i', //RSSMRA8RTMLARS2Z
        63 => '/^[a-z]{6}[0-9][a-z]{9}$/i', //RSSMRA8RTMLARSNO
        64 => '/^[a-z]{7}[0-9][a-z][0-9]{2}[a-z][0-9]{3}[a-z]$/i', //RSSMRAU5T10A562P
        65 => '/^[a-z]{7}[0-9][a-z][0-9]{2}[a-z][0-9]{2}[a-z]{2}$/i', //RSSMRAU5T10A56NE
        66 => '/^[a-z]{7}[0-9][a-z][0-9]{2}[a-z][0-9][a-z][0-9][a-z]$/i', //RSSMRAU5T10A5S2B
        67 => '/^[a-z]{7}[0-9][a-z][0-9]{2}[a-z][0-9][a-z]{3}$/i', //RSSMRAU5T10A5SNQ
        68 => '/^[a-z]{7}[0-9][a-z][0-9]{2}[a-z]{2}[0-9]{2}[a-z]$/i', //RSSMRAU5T10AR62K
        69 => '/^[a-z]{7}[0-9][a-z][0-9]{2}[a-z]{2}[0-9][a-z]{2}$/i', //RSSMRAU5T10AR6NZ
        70 => '/^[a-z]{7}[0-9][a-z][0-9]{2}[a-z]{3}[0-9][a-z]$/i', //RSSMRAU5T10ARS2W
        71 => '/^[a-z]{7}[0-9][a-z][0-9]{2}[a-z]{5}$/i', //RSSMRAU5T10ARSNL
        72 => '/^[a-z]{7}[0-9][a-z][0-9][a-z]{2}[0-9]{3}[a-z]$/i', //RSSMRAU5T1LA562S
        73 => '/^[a-z]{7}[0-9][a-z][0-9][a-z]{2}[0-9]{2}[a-z]{2}$/i', //RSSMRAU5T1LA56NH
        74 => '/^[a-z]{7}[0-9][a-z][0-9][a-z]{2}[0-9][a-z][0-9][a-z]$/i', //RSSMRAU5T1LA5S2E
        75 => '/^[a-z]{7}[0-9][a-z][0-9][a-z]{2}[0-9][a-z]{3}$/i', //RSSMRAU5T1LA5SNT
        76 => '/^[a-z]{7}[0-9][a-z][0-9][a-z]{3}[0-9]{2}[a-z]$/i', //RSSMRAU5T1LAR62N
        77 => '/^[a-z]{7}[0-9][a-z][0-9][a-z]{3}[0-9][a-z]{2}$/i', //RSSMRAU5T1LAR6NC
        78 => '/^[a-z]{7}[0-9][a-z][0-9][a-z]{4}[0-9][a-z]$/i', //RSSMRAU5T1LARS2Z
        79 => '/^[a-z]{7}[0-9][a-z][0-9][a-z]{6}$/i', //RSSMRAU5T1LARSNO
        80 => '/^[a-z]{7}[0-9][a-z]{2}[0-9][a-z][0-9]{3}[a-z]$/i', //RSSMRAU5TM0A562A
        81 => '/^[a-z]{7}[0-9][a-z]{2}[0-9][a-z][0-9]{2}[a-z]{2}$/i', //RSSMRAU5TM0A56NP
        82 => '/^[a-z]{7}[0-9][a-z]{2}[0-9][a-z][0-9][a-z][0-9][a-z]$/i', //RSSMRAU5TM0A5S2M
        83 => '/^[a-z]{7}[0-9][a-z]{2}[0-9][a-z][0-9][a-z]{3}$/i', //RSSMRAU5TM0A5SNB
        84 => '/^[a-z]{7}[0-9][a-z]{2}[0-9][a-z]{2}[0-9]{2}[a-z]$/i', //RSSMRAU5TM0AR62V
        85 => '/^[a-z]{7}[0-9][a-z]{2}[0-9][a-z]{2}[0-9][a-z]{2}$/i', //RSSMRAU5TM0AR6NK
        86 => '/^[a-z]{7}[0-9][a-z]{2}[0-9][a-z]{3}[0-9][a-z]$/i', //RSSMRAU5TM0ARS2H
        87 => '/^[a-z]{7}[0-9][a-z]{2}[0-9][a-z]{5}$/i', //RSSMRAU5TM0ARSNW
        88 => '/^[a-z]{7}[0-9][a-z]{4}[0-9]{3}[a-z]$/i', //RSSMRAU5TMLA562D
        89 => '/^[a-z]{7}[0-9][a-z]{4}[0-9]{2}[a-z]{2}$/i', //RSSMRAU5TMLA56NS
        90 => '/^[a-z]{7}[0-9][a-z]{4}[0-9][a-z][0-9][a-z]$/i', //RSSMRAU5TMLA5S2P
        91 => '/^[a-z]{7}[0-9][a-z]{4}[0-9][a-z]{3}$/i', //RSSMRAU5TMLA5SNE
        92 => '/^[a-z]{7}[0-9][a-z]{5}[0-9]{2}[a-z]$/i', //RSSMRAU5TMLAR62Y
        93 => '/^[a-z]{7}[0-9][a-z]{5}[0-9][a-z]{2}$/i', //RSSMRAU5TMLAR6NN
        94 => '/^[a-z]{7}[0-9][a-z]{6}[0-9][a-z]$/i', //RSSMRAU5TMLARS2K
        95 => '/^[a-z]{7}[0-9][a-z]{8}$/i', //RSSMRAU5TMLARSNZ
        96 => '/^[a-z]{9}[0-9]{2}[a-z][0-9]{3}[a-z]$/i', //RSSMRAURT10A562B
        97 => '/^[a-z]{9}[0-9]{2}[a-z][0-9]{2}[a-z]{2}$/i', //RSSMRAURT10A56NQ
        98 => '/^[a-z]{9}[0-9]{2}[a-z][0-9][a-z][0-9][a-z]$/i', //RSSMRAURT10A5S2N
        99 => '/^[a-z]{9}[0-9]{2}[a-z][0-9][a-z]{3}$/i', //RSSMRAURT10A5SNC
        100 => '/^[a-z]{9}[0-9]{2}[a-z]{2}[0-9]{2}[a-z]$/i', //RSSMRAURT10AR62W
        101 => '/^[a-z]{9}[0-9]{2}[a-z]{2}[0-9][a-z]{2}$/i', //RSSMRAURT10AR6NL
        102 => '/^[a-z]{9}[0-9]{2}[a-z]{3}[0-9][a-z]$/i', //RSSMRAURT10ARS2I
        103 => '/^[a-z]{9}[0-9]{2}[a-z]{5}$/i', //RSSMRAURT10ARSNX
        104 => '/^[a-z]{9}[0-9][a-z]{2}[0-9]{3}[a-z]$/i', //RSSMRAURT1LA562E
        105 => '/^[a-z]{9}[0-9][a-z]{2}[0-9]{2}[a-z]{2}$/i', //RSSMRAURT1LA56NT
        106 => '/^[a-z]{9}[0-9][a-z]{2}[0-9][a-z][0-9][a-z]$/i', //RSSMRAURT1LA5S2Q
        107 => '/^[a-z]{9}[0-9][a-z]{2}[0-9][a-z]{3}$/i', //RSSMRAURT1LA5SNF
        108 => '/^[a-z]{9}[0-9][a-z]{3}[0-9]{2}[a-z]$/i', //RSSMRAURT1LAR62Z
        109 => '/^[a-z]{9}[0-9][a-z]{3}[0-9][a-z]{2}$/i', //RSSMRAURT1LAR6NO
        110 => '/^[a-z]{9}[0-9][a-z]{4}[0-9][a-z]$/i', //RSSMRAURT1LARS2L
        111 => '/^[a-z]{9}[0-9][a-z]{6}$/i', //RSSMRAURT1LARSNA
        112 => '/^[a-z]{10}[0-9][a-z][0-9]{3}[a-z]$/i', //RSSMRAURTM0A562M
        113 => '/^[a-z]{10}[0-9][a-z][0-9]{2}[a-z]{2}$/i', //RSSMRAURTM0A56NB
        114 => '/^[a-z]{10}[0-9][a-z][0-9][a-z][0-9][a-z]$/i', //RSSMRAURTM0A5S2Y
        115 => '/^[a-z]{10}[0-9][a-z][0-9][a-z]{3}$/i', //RSSMRAURTM0A5SNN
        116 => '/^[a-z]{10}[0-9][a-z]{2}[0-9]{2}[a-z]$/i', //RSSMRAURTM0AR62H
        117 => '/^[a-z]{10}[0-9][a-z]{2}[0-9][a-z]{2}$/i', //RSSMRAURTM0AR6NW
        118 => '/^[a-z]{10}[0-9][a-z]{3}[0-9][a-z]$/i', //RSSMRAURTM0ARS2T
        119 => '/^[a-z]{10}[0-9][a-z]{5}$/i', //RSSMRAURTM0ARSNI
        120 => '/^[a-z]{12}[0-9]{3}[a-z]$/i', //RSSMRAURTMLA562P
        121 => '/^[a-z]{12}[0-9]{2}[a-z]{2}$/i', //RSSMRAURTMLA56NE
        122 => '/^[a-z]{12}[0-9][a-z][0-9][a-z]$/i', //RSSMRAURTMLA5S2B
        123 => '/^[a-z]{12}[0-9][a-z]{3}$/i', //RSSMRAURTMLA5SNQ
        124 => '/^[a-z]{13}[0-9]{2}[a-z]$/i', //RSSMRAURTMLAR62K
        125 => '/^[a-z]{13}[0-9][a-z]{2}$/i', //RSSMRAURTMLAR6NZ
        126 => '/^[a-z]{14}[0-9][a-z]$/i', //RSSMRAURTMLARS2W
        127 => '/^[a-z]{16}$/i', //RSSMRAURTMLARSNL
    );

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
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * Validates length
     *
     * @throws Exception
     */
    private function validateLength()
    {
        // check empty
        if (empty($this->codiceFiscale)) {
            throw new Exception('The codice fiscale to validate is empty');
        }

        // check length
        if (strlen($this->codiceFiscale) !== 16) {
            throw new Exception('The codice fiscale to validate has an invalid length');
        }
    }

    /**
     * Validates format
     *
     * @throws Exception
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
            throw new Exception('The codice fiscale to validate has an invalid format');
        }
    }

    /**
     * Validates check digit
     *
     * @throws Exception
     */
    private function validateCheckDigit()
    {
        $checkDigit = $this->calculateCheckDigit($this->codiceFiscale);
        if ($checkDigit != $this->codiceFiscale[15]) {
            throw new Exception('The codice fiscale to validate has an invalid control character');
        }
    }

    /**
     * Validates omocodia and replace with matching chars
     *
     * @throws Exception
     */
    private function validateAndReplaceOmocodia()
    {
        // check and replace omocodie
        $this->codiceFiscaleWithoutOmocodia = $this->codiceFiscale;
        $this->replaceOmocodiaSection(2, 1, 1, $this->omocodiaPositions[0]);
        $this->replaceOmocodiaSection(4, 2, 3, $this->omocodiaPositions[1]);
        $this->replaceOmocodiaSection(8, 4, 7, $this->omocodiaPositions[2]);
        $this->replaceOmocodiaSection(16, 8, 15, $this->omocodiaPositions[3]);
        $this->replaceOmocodiaSection(32, 16, 31, $this->omocodiaPositions[4]);
        $this->replaceOmocodiaSection(64, 32, 63, $this->omocodiaPositions[5]);
        $this->replaceOmocodiaSection(128, 64, 127, $this->omocodiaPositions[6]);
    }

    /**
     * Replace a section of the omocodia.
     *
     * @param int $divider The divider.
     * @param int $startingIndex The starting index.
     * @param int $endingIndex The ending index.
     * @param int $characterIndex The index to use to make the substitutions on the $codiceFiscaleWithoutOmocodia.
     * @throws Exception
     */
    private function replaceOmocodiaSection($divider, $startingIndex, $endingIndex, $characterIndex)
    {
        if (
            $this->foundOmocodiaLevel % $divider >= $startingIndex &&
            $this->foundOmocodiaLevel % $divider <= $endingIndex
        ) {
            $charToCheck = $this->codiceFiscaleWithoutOmocodia[$characterIndex];
            if (!in_array($charToCheck, $this->omocodiaCodes)) {
                throw new Exception('The codice fiscale to validate has an invalid character');
            }
            $newChar = array_search($charToCheck, $this->omocodiaCodes);
            $this->codiceFiscaleWithoutOmocodia[$characterIndex] = $newChar;
        }
    }

    /**
     * Validates birthdate and gender
     *
     * @throws Exception
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
            throw new Exception('The codice fiscale to validate has invalid characters for birth day');
        }

        // check month
        $monthChar = substr($this->codiceFiscaleWithoutOmocodia, 8, 1);
        if (!in_array($monthChar, $this->months)) {
            throw new Exception('The codice fiscale to validate has an invalid character for birth month');
        }

        // calculate month, year and century
        $month = array_search($monthChar, $this->months);
        $year = substr($this->codiceFiscaleWithoutOmocodia, 6, 2);
        $century = $this->calculateCentury($year);

        // validate and calculate birth date
        if (!checkdate($month, $day, $century . $year)) {
            throw new Exception('The codice fiscale to validate has an non existent birth date');
        }

        $this->birthDate = new DateTime();
        $this->birthDate->setDate($century . $year, $month, $day)->setTime(0, 0, 0)->format('Y-m-d');
    }

    /**
     *
     * @param string $year
     * @return string
     * @throws Exception
     */
    private function calculateCentury($year)
    {
        $currentDate = new DateTime();
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
     * @return DateTime
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
