<?php

namespace CodiceFiscale;

/**
 * Description of Validator
 *
 * @author Antonio Turdo <antonio.turdo@gmail.com>
 */
class Validator {
    // omocodia max level
    const ALL_OMOCODIA_LEVELS = 7;
    
    // women char
    const CHR_WOMEN = 'F';
    
    // male char
    const CHR_MALE = 'M';    
    
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
    
    private $omocodiaMapping = array(0 => "L", 1 => "M", 2 => "N", 3 => "P", 4 => "Q", 5 => "R", 6 => "S", 7 => "T", 8 => "U", 9 => "V");
    
    private $omocodiaLevel = null;
    
    private $codiceFiscaleWithoutOmocodia = null;
    private $day = null;
    private $sex = null;
    
    private $isValid = false;

    /**
     * Weight even char
     * @var array
     */
    private $listEvenChar = array('0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, 'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7, 'I' => 8, 'J' => 9, 'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15, 'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19, 'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23, 'Y' => 24, 'Z' => 25);
    
    /**
     * Weight odd char
     * @var array
     */
    private $listOddChar = array('0' => 1, '1' => 0, '2' => 5, '3' => 7, '4' => 9, '5' => 13, '6' => 15, '7' => 17, '8' => 19, '9' => 21, 'A' => 1, 'B' => 0, 'C' => 5, 'D' => 7, 'E' => 9, 'F' => 13, 'G' => 15, 'H' => 17, 'I' => 19, 'J' => 21, 'K' => 2, 'L' => 4, 'M' => 18, 'N' => 20, 'O' => 11, 'P' => 3, 'Q' => 6, 'R' => 8, 'S' => 12, 'T' => 14, 'U' => 16, 'V' => 10, 'W' => 22, 'X' => 25, 'Y' => 24, 'Z' => 23);
    
    /**
     * Control code (char 16)
     * @var array
     */
    private $listCtrlCode = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M', 13 => 'N', 14 => 'O', 15 => 'P', 16 => 'Q', 17 => 'R', 18 => 'S', 19 => 'T', 20 => 'U', 21 => 'V', 22 => 'W', 23 => 'X', 24 => 'Y', 25 => 'Z');
    
    /**
     * Month code
     * @var array
     */
    protected $listDecMonth = array('A' => '01', 'B' => '02', 'C' => '03', 'D' => '04', 'E' => '05', 'H' => '06', 'L' => '07', 'M' => '08', 'P' => '09', 'R' => '10', 'S' => '11', 'T' => '12');
    
    /**
     * Create a Codice Fiscale instance.
     *
     * @param string $codiceFiscale the codice fiscale to validate
     * @param integer $omocodiaLevel the omocodia level to take into account, default to all
     */    
    public function __construct($codiceFiscale, $omocodiaLevel = self::ALL_OMOCODIA_LEVELS) {
        try {
            // check omocodia level
            if (!is_int($omocodiaLevel) || $omocodiaLevel < 0 || $omocodiaLevel > self::ALL_OMOCODIA_LEVELS) {
                throw new \Exception('omocodiaLevel');
            }
            
            // check empty
            if (empty($codiceFiscale)) {
                throw new \Exception('empty');
            }
            
            // check length
            if (strlen($codiceFiscale) !== 16) {
                throw new \Exception('length');
            }
            
            // check regexp
            $regexpValid = false;
            for ($currentOmocodiaLevel = 0; $currentOmocodiaLevel < $omocodiaLevel; $currentOmocodiaLevel++) {
                if (preg_match($this->regexs[$currentOmocodiaLevel], $codiceFiscale)) {
                    $this->omocodiaLevel = $currentOmocodiaLevel;
                    $regexpValid = true;
                    break;
                }
            }
            
            if (!$regexpValid) {
                throw new \Exception('format');
            }
            
            // uppercase and split for better manipulation
            $codiceFiscale = strtoupper($codiceFiscale);
            $cFCharList = str_split($codiceFiscale);  

            // initialize odd and even sum
            $evenSum = 0;
            $oddSum = $this->listOddChar[$cFCharList[14]];
            
            // loop first 14 char, step 2
            for ($i = 0; $i < 13; $i += 2) {
                $oddSum = $oddSum + $this->listOddChar[$cFCharList[$i]];
                $evenSum = $evenSum + $this->listEvenChar[$cFCharList[$i + 1]];
            }
            
            // verify first 15 char with checksum char (char 16)
            if (!($this->listCtrlCode[($evenSum + $oddSum) % 26] === $cFCharList[15])) {
                throw new \Exception('checksum');
            }  
            
            // check and replace omocodie
            for ($omocodiaCheck = 0; $omocodiaCheck < $this->omocodiaLevel; $omocodiaCheck++) {
                $positionToCheck = $this->omocodiaPositions[$omocodiaCheck];
                $charToCheck = $cFCharList[$positionToCheck];
                if (!in_array($charToCheck, $this->omocodiaMapping)) {
                    throw new \Exception('omocodia');
                }
                $cFCharList[$positionToCheck] = array_search($charToCheck, $this->omocodiaMapping);
            }
            
            // implode chars again in string
            $this->codiceFiscaleWithoutOmocodia = implode($cFCharList);            
            
            // calculate day and sex
            $this->day = (int)substr($this->codiceFiscaleWithoutOmocodia, 9, 2);
            $this->sex = $this->day > 40 ? self::CHR_WOMEN : self::CHR_MALE;
            
            if ($this->sex === self::CHR_WOMEN) {
                $this->day -= 40;
            }
            
            // check day
            if ($this->day > 31) {
                throw new \Exception('day');
            }
            
            // check month
            $monthChar = substr($this->codiceFiscaleWithoutOmocodia, 8, 1);
            if (!array_key_exists($monthChar, $this->listDecMonth)) {
                throw new \Exception('month');
            }
            
            $this->isValid = true;          
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }
    
    public function getError() {
        return $this->error;
    }
    
    public function isFormallyValid() {
        return $this->isValid;
    }
    
    protected function getCodiceFiscaleWithoutOmocodia() {
        return $this->codiceFiscaleWithoutOmocodia;
    }

    protected function getDay() {
        return $this->day;
    }

    public function getSex() {
        return $this->sex;
    }

}
