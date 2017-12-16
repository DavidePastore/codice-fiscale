<?php

namespace CodiceFiscale;

/**
 * Description of InverseCalculator
 *
 * @author Antonio Turdo <antonio.turdo@gmail.com>
 */
class InverseCalculator extends Validator {
    
    private $birthDate = null;
    
    private $belfioreCode = null;
    
    public function __construct($codiceFiscale, $omocodiaLevel = self::ALL_OMOCODIA_LEVELS, $secular = false) {
        parent::__construct($codiceFiscale, $omocodiaLevel);
        
        if ($this->isFormallyValid()) {
            $codiceFiscaleWithoutOmocodia = $this->getCodiceFiscaleWithoutOmocodia();
            
            // calculate month
            $month = $this->listDecMonth[substr($codiceFiscaleWithoutOmocodia, 8, 1)];
            
            // calculate year
            $year = substr($codiceFiscaleWithoutOmocodia, 6, 2);
            
            // calculate century
            $currentDate = new \DateTime();
            $currentYear = $currentDate->format('y');
            $currentCentury = substr($currentDate->format('Y'), 0, 2);
            $century = $year < $currentYear && !$secular ? $currentCentury : $currentCentury - 1;
            
            // calculate birth date
            $this->birthDate = new \DateTime();
            $this->birthDate->setDate($century.$year, $month, $this->getDay());
            $this->birthDate->setTime(0, 0, 0);
           
            // calculate belfiore code
            $this->belfioreCode = substr($codiceFiscaleWithoutOmocodia, 11, 4);
        }
    }
    
    public function getBirthDate() {
        return $this->birthDate;
    }

    public function getBelfioreCode() {
        return $this->belfioreCode;
    }

    public function getAllData(){
        return array(
            "sex" => $this->getSex(),
            "birthDate" => $this->getBirthDate(),
            "befioreCode" => $this->getBelfioreCode()
                );
    }   
    
}
