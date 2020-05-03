<?php

namespace CodiceFiscale;

/**
 * Test for the Checker class.
 *
 * @author davidepastore
 */
class SubjectTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test for getName.
     */
    public function testGetName()
    {
        $subject = new Subject(
          array(
            'name' => 'Mario',
            'surname' => 'Rossi',
            'birthDate' => '1985-12-10',
            'gender' => 'M',
            'belfioreCode' => 'A562',
          )
        );
        $actual = $subject->getName();
        $expected = 'Mario';
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test for setName.
     */
    public function testSetName()
    {
        $subject = new Subject(
          array(
            'name' => 'Mario',
            'surname' => 'Rossi',
            'birthDate' => '1985-12-10',
            'gender' => 'M',
            'belfioreCode' => 'A562',
          )
        );
        $subject->setName('Fabrizio');
        $actual = $subject->getName();
        $expected = 'Fabrizio';
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test for getSurname.
     */
    public function testGetSurname()
    {
        $subject = new Subject(
          array(
            'name' => 'Mario',
            'surname' => 'Rossi',
            'birthDate' => '1985-12-10',
            'gender' => 'M',
            'belfioreCode' => 'A562',
          )
        );
        $actual = $subject->getSurname();
        $expected = 'Rossi';
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test for setSurname.
     */
    public function testSetSurname()
    {
        $subject = new Subject(
          array(
            'name' => 'Mario',
            'surname' => 'Rossi',
            'birthDate' => '1985-12-10',
            'gender' => 'M',
            'belfioreCode' => 'A562',
          )
        );
        $subject->setSurname('Russo');
        $actual = $subject->getSurname();
        $expected = 'Russo';
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test for getBirthDate.
     */
    public function testGetBirthDate()
    {
        $subject = new Subject(
          array(
            'name' => 'Mario',
            'surname' => 'Rossi',
            'birthDate' => '1985-12-10',
            'gender' => 'M',
            'belfioreCode' => 'A562',
          )
        );
        $actual = $subject->getBirthDate();
        $expected = new \DateTime('1985-12-10');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test for setBirthDate.
     */
    public function testSetBirthDate()
    {
        $subject = new Subject(
          array(
            'name' => 'Mario',
            'surname' => 'Rossi',
            'birthDate' => '1985-12-10',
            'gender' => 'M',
            'belfioreCode' => 'A562',
          )
        );
        $subject->setBirthDate(new \DateTime('1944-01-10'));
        $actual = $subject->getBirthDate();
        $expected = new \DateTime('1944-01-10');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test for setBirthDate.
     */
    public function testSetBirthDateImmutable()
    {
        $subject = new Subject(
          array(
            'name' => 'Mario',
            'surname' => 'Rossi',
            'birthDate' => '1985-12-10',
            'gender' => 'M',
            'belfioreCode' => 'A562',
          )
        );
        $subject->setBirthDate(new \DateTimeImmutable('1944-01-10'));
        $actual = $subject->getBirthDate();
        $expected = new \DateTimeImmutable('1944-01-10');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test for setBirthDate.
     */
    public function testSetBirthDateFancyConversion()
    {
        $subject = new Subject(
          array(
            'name' => 'Mario',
            'surname' => 'Rossi',
            'birthDate' => '1985-12-10',
            'gender' => 'M',
            'belfioreCode' => 'A562',
          )
        );
        $subject->setBirthDate('1944-01-10');
        $actual = $subject->getBirthDate();
        $expected = new \DateTimeImmutable('1944-01-10');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test for getGender.
     */
    public function testGetGender()
    {
        $subject = new Subject(
          array(
            'name' => 'Mario',
            'surname' => 'Rossi',
            'birthDate' => '1985-12-10',
            'gender' => 'M',
            'belfioreCode' => 'A562',
          )
        );
        $actual = $subject->getGender();
        $expected = 'M';
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test for setGender.
     */
    public function testSetGender()
    {
        $subject = new Subject(
          array(
            'name' => 'Mario',
            'surname' => 'Rossi',
            'birthDate' => '1985-12-10',
            'gender' => 'M',
            'belfioreCode' => 'A562',
          )
        );
        $subject->setGender('F');
        $actual = $subject->getGender();
        $expected = 'F';
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test for getBelfioreCode.
     */
    public function testGetBelfioreCode()
    {
        $subject = new Subject(
          array(
            'name' => 'Mario',
            'surname' => 'Rossi',
            'birthDate' => '1985-12-10',
            'gender' => 'M',
            'belfioreCode' => 'A562',
          )
        );
        $actual = $subject->getBelfioreCode();
        $expected = 'A562';
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test for setGender.
     */
    public function testSetBelfioreCode()
    {
        $subject = new Subject(
          array(
            'name' => 'Mario',
            'surname' => 'Rossi',
            'birthDate' => '1985-12-10',
            'gender' => 'M',
            'belfioreCode' => 'A562',
          )
        );
        $subject->setBelfioreCode('H501');
        $actual = $subject->getBelfioreCode();
        $expected = 'H501';
        $this->assertEquals($expected, $actual);
    }
}
