<?php

namespace CodiceFiscale;

use Exception;

/**
 * Description of InverseCalculator
 *
 * @author Antonio Turdo <antonio.turdo@gmail.com>
 */
class InverseCalculator extends Validator
{
    private $belfioreCode = null;

    /**
     * Create an InverseCalculator instance.
     *
     * @param string $codiceFiscale the codice fiscale to validate
     * @param array $properties An array with additional properties.
     */
    public function __construct($codiceFiscale, $properties = [])
    {
        parent::__construct($codiceFiscale, $properties);

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
    public function getBelfioreCode()
    {
        return $this->belfioreCode;
    }

    /**
     * Return the Subject calculated from codice fiscale
     *
     * @return Subject
     * @throws Exception
     */
    public function getSubject()
    {
        return new Subject(array(
            "gender" => $this->getGender(),
            "birthDate" => $this->getBirthDate(),
            "belfioreCode" => $this->getBelfioreCode()
                ));
    }
}
