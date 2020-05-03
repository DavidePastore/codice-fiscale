<?php

namespace CodiceFiscale;

/**
 * Test for the Checker class.
 *
 * @author davidepastore
 */
class CheckerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test for check.
     *
     * @dataProvider checkerProvider
     */
    public function testAllLevels($subject, $codiceFiscaleToCheck, $omocodiaLevel, $expected)
    {
        $checker = new Checker($subject, array(
            'codiceFiscaleToCheck' => $codiceFiscaleToCheck,
            'omocodiaLevel' => $omocodiaLevel,
        ));
        $actual = $checker->check();
        $this->assertEquals($expected, $actual);
    }

    /**
     * The checker provider.
     */
    public function checkerProvider()
    {
        return array(
          array(
            new Subject(
              array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
              )
            ),
            'RSSMRA85T10A562S',
            0,
            true,
          ),
          array(
            new Subject(
              array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
              )
            ),
            'RSSMRA85T10A562S',
            2,
            false,
          ),
          array(
            new Subject(
              array(
                'name' => 'Roberto',
                'surname' => 'Santi',
                'birthDate' => '1963-05-08',
                'gender' => 'M',
                'belfioreCode' => 'H501',
              )
            ),
            'SNTRRT63E08H50ML',
            Checker::ALL_OMOCODIA_LEVELS,
            true,
          ),
          array(
            new Subject(
              array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
              )
            ),
            'RSSMRAURTMLAR6NZ',
            Checker::ALL_OMOCODIA_LEVELS,
            true,
          ),
          array(
            new Subject(
              array(
                'name' => "Annalisa",
                'surname' => "Lisà",
                'birthDate' => '1980-04-04',
                'gender' => 'F',
                'belfioreCode' => 'H501',
              )
            ),
            'LSINLS80D44H501F',
            -1,
            true,
          ),
        );
    }
}
