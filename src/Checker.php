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
    public const ALL_OMOCODIA_LEVELS = -1;

    /**
     * Checker constructor.
     *
     * @param Subject $subject The subject.
     * @param array $properties Additional properties.
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
     * @return bool True if the codice fiscale is ok, false otherwise.
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
