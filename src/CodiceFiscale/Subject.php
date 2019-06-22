<?php

namespace CodiceFiscale;

use DateTime;
use DateTimeInterface;
use Exception;

/**
 * The subject of the codice fiscale.
 *
 * @author davidepastore
 */
class Subject
{
    private $name;
    private $surname;
    private $birthDate;
    private $gender;
    private $belfioreCode;

    /**
     * Create a Codice Fiscale instance.
     *
     * @param array $properties An array with all the properties.
     *                          Supported keys are:
     *                          - name: the name;
     *                          - surname: the surname;
     *                          - birthDate: the birth date;
     *                          - gender: the gender;
     *                          - belfioreCode: the Belfiore code.
     * @throws Exception
     */
    public function __construct($properties)
    {
        //Set properties
        if (array_key_exists('name', $properties)) {
            $this->name = $properties['name'];
        }

        if (array_key_exists('surname', $properties)) {
            $this->surname = $properties['surname'];
        }

        if (array_key_exists('birthDate', $properties)) {
            $this->setBirthDate($properties['birthDate']);
        }

        if (array_key_exists('gender', $properties)) {
            $this->gender = $properties['gender'];
        }

        if (array_key_exists('belfioreCode', $properties)) {
            $this->belfioreCode = $properties['belfioreCode'];
        }
    }

    /**
     * Get the name.
     *
     * @return string The name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name.
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the name.
     *
     * @return string The name.
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set the surname.
     *
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Get the birthDate.
     *
     * @return DateTime The birthDate.
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set the birthDate.
     * @param mixed $birthDate
     * @throws Exception
     */
    public function setBirthDate($birthDate)
    {
        if (!$birthDate instanceof DateTimeInterface) {
            $birthDate = new DateTime($birthDate);
        }

        $this->birthDate = $birthDate;
    }

    /**
     * Get the gender.
     *
     * @return string The gender.
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set the gender.
     *
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Get the belfioreCode.
     *
     * @return string The belfioreCode.
     */
    public function getBelfioreCode()
    {
        return $this->belfioreCode;
    }

    /**
     * Set the belfioreCode.
     * @param string $belfioreCode
     */
    public function setBelfioreCode($belfioreCode)
    {
        $this->belfioreCode = $belfioreCode;
    }
}
