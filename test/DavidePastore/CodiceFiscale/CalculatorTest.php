<?php

namespace DavidePastore\CodiceFiscale;

/**
 * Test for the Calculator class.
 *
 * @author davidepastore
 */
class CalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test for calculate.
     *
     * @dataProvider calculateProvider
     */
    public function testCalculate($subject, $omocodiaLevel, $expectedCodiceFiscale)
    {
        $calculator = new Calculator($subject, array(
            'omocodiaLevel' => $omocodiaLevel,
        ));
        $actual = $calculator->calculate();
        $this->assertEquals($expectedCodiceFiscale, $actual);
    }

    /**
     * Test for calculateAllPossibilities.
     *
     * @dataProvider calculateAllPossibilitiesProvider
     */
    public function testCalculateAllPossibilities($subject, $expected)
    {
        $calculator = new Calculator($subject);
        $actual = $calculator->calculateAllPossibilities();
        $this->assertEquals($expected, $actual);
    }

    /**
     * The calculate data provider.
     */
    public function calculateProvider()
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
            0,
            'RSSMRA85T10A562S',
          ),
          array(
            new Subject(
              array(
                'name' => 'Roberto',
                'surname' => 'Santini',
                'birthDate' => '1963-05-08',
                'gender' => 'M',
                'belfioreCode' => 'H501',
              )
            ),
            0,
            'SNTRRT63E08H501T',
          ),
          array(
            new Subject(
              array(
                'name' => 'Maria',
                'surname' => 'Montessori',
                'birthDate' => '1870-08-31',
                'gender' => 'F',
                'belfioreCode' => 'C615',
              )
            ),
            0,
            'MNTMRA70M71C615I',
          ),
          array(
            new Subject(
              array(
                'name' => 'Mario',
                'surname' => 'Rossi',
                'birthDate' => new \DateTime('1985-12-10'),
                'gender' => 'M',
                'belfioreCode' => 'A562',
              )
            ),
            0,
            'RSSMRA85T10A562S',
          ),
          array(
            new Subject(
              array(
                'name' => 'Gianfranco',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
              )
            ),
            0,
            'RSSGFR85T10A562I',
          ),
          array(
            new Subject(
              array(
                'name' => 'Mario',
                'surname' => 'Fo',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
              )
            ),
            0,
            'FOXMRA85T10A562G',
          ),
          array(
            new Subject(
              array(
                'name' => '',
                'surname' => 'Rossi',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
              )
            ),
            0,
            'RSSXXX85T10A562R',
          ),
          array(
            new Subject(
              array(
                'name' => 'Mario',
                'surname' => '',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
              )
            ),
            0,
            'XXXMRA85T10A562B',
          ),
          array(
            new Subject(
              array(
                'name' => '',
                'surname' => '',
                'birthDate' => '1985-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
              )
            ),
            0,
            'XXXXXX85T10A562A',
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
            1,
            'SNTRRT63E08H50ML',
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
            1,
            'RSSMRA85T10A56NH',
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
            3,
            'RSSMRA85T10ARSNO',
          ),
        );
    }

    /**
     * The calculate all possibilities data provider.
     */
    public function calculateAllPossibilitiesProvider()
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
            array(
              'RSSMRA85T10A562S',
              'RSSMRA85T10A56NH',
              'RSSMRA85T10A5SNT',
              'RSSMRA85T10ARSNO',
              'RSSMRA85T1LARSNR',
              'RSSMRA85TMLARSNC',
              'RSSMRA8RTMLARSNO',
              'RSSMRAURTMLARSNL',
            ),
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
            array(
              'SNTRRT63E08H501T',
              'SNTRRT63E08H50ML',
              'SNTRRT63E08H5LMW',
              'SNTRRT63E08HRLMR',
              'SNTRRT63E0UHRLMO',
              'SNTRRT63ELUHRLMZ',
              'SNTRRT6PELUHRLML',
              'SNTRRTSPELUHRLMI',
            ),
          ),
        );
    }
}
