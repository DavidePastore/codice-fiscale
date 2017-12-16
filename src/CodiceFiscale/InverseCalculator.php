<?php

namespace CodiceFiscale;

/**
 * Description of InverseCalculator
 *
 * @author Antonio Turdo <antonio.turdo@gmail.com>
 */
class InverseCalculator extends Validator {
    
    private $belfioreCode = null;
    
    /**
     * Create an InverseCalculator instance.
     * 
     * @param string $codiceFiscale
     * @param boolean $omocodiaAllowed
     * @param boolean $secular
     */
    public function __construct($codiceFiscale, $omocodiaAllowed = true, $secular = false) {
        parent::__construct($codiceFiscale, $omocodiaAllowed, $secular);
        
        if ($this->isFormallyValid()) {
            $codiceFiscaleWithoutOmocodia = $this->getCodiceFiscaleWithoutOmocodia();
            
            // calculate belfiore code
            $this->belfioreCode = substr($codiceFiscaleWithoutOmocodia, 11, 4);
        }
    }

    /**
     * Return the belfiore code
     * 
     * @return string
     */
    public function getBelfioreCode() {
        return $this->belfioreCode;
    }

    /**
     * Return the Subject calculated from codice fiscale 
     * 
     * @return \CodiceFiscale\Subject
     */
    public function getSubject(){
        return new Subject(array(
            "gender" => $this->getGender(),
            "birthDate" => $this->getBirthDate(),
            "belfioreCode" => $this->getBelfioreCode()
                ));
    }   
    
}
