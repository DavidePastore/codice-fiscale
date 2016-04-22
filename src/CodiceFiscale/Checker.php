<?php

namespace CodiceFiscale;

/**
 * Codice Fiscale checker.
 *
 * @author davidepastore
 */
class Checker
{
    private $subject;
    private $omocodiaLevel = self::ALL_OMOCODIA_LEVELS;
    private $codiceFiscaleToCheck;

    /**
     * Constant to check for all the omocodia levels.
     */
    const ALL_OMOCODIA_LEVELS = -1;

    /**
     * Create a Codice Fiscale instance.
     *
     * @param Subject $subject The subject.
     * @param $properties Array of additional properties.
     */
    public function __construct(Subject $subject, $properties = array())
    {
        $this->subject = $subject;

        if (array_key_exists('codiceFiscaleToCheck', $properties)) {
            $this->codiceFiscaleToCheck = $properties['codiceFiscaleToCheck'];
        }

        if (array_key_exists('omocodiaLevel', $properties)) {
            $this->omocodiaLevel = $properties['omocodiaLevel'];
        }
    }

    /**
     * Check if the given data is ok for the given codice fiscale.
     *
     * @returns Returns true if the codice fiscale is ok, false otherwise.
     */
    public function check()
    {
        $calculator = new Calculator($this->subject, array(
            'omocodiaLevel' => $this->omocodiaLevel,
        ));
        if ($this->omocodiaLevel == self::ALL_OMOCODIA_LEVELS) {
            $values = $calculator->calculateAllPossibilities();
        } else {
            $values = array($calculator->calculate());
        }

        return in_array($this->codiceFiscaleToCheck, $values);
    }
}
