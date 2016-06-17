<?php

namespace CodiceFiscale;

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
        $calculator = new Calculator($subject, [
            'omocodiaLevel' => $omocodiaLevel,
        ]);
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
        return [
          [ new Subject([
            'name'          => 'Mario',
            'surname'       => 'Rossi',
            'birthDate'     => '1985-12-10',
            'gender'        => 'M',
            'belfioreCode'  => 'A562',
          ]), 0, 'RSSMRA85T10A562S' ],
          [ new Subject([
            'name'          => 'Roberto',
            'surname'       => 'Santini',
            'birthDate'     => '1963-05-08',
            'gender'        => 'M',
            'belfioreCode'  => 'H501',
          ]), 0, 'SNTRRT63E08H501T' ],
          [ new Subject([
            'name'          => 'Davide',
            'surname'       => 'Rossi',
            'birthDate'     => new \DateTime('1989-12-10'),
            'gender'        => 'M',
            'belfioreCode'  => 'A562',
          ]), 0, 'RSSDVD89T10A562S' ],
          [ new Subject([
            'name'          => 'Maria',
            'surname'       => 'Montessori',
            'birthDate'     => '1870-08-31',
            'gender'        => 'F',
            'belfioreCode'  => 'C615',
          ]), 0, 'MNTMRA70M71C615I' ],
          [ new Subject([
            'name'          => 'Mario',
            'surname'       => 'Rossi',
            'birthDate'     => '1985-12-10',
            'gender'        => 'M',
            'belfioreCode'  => 'A562',
          ]), 0, 'RSSMRA85T10A562S' ],
          [ new Subject([
            'name'          => 'Gianfranco',
            'surname'       => 'Rossi',
            'birthDate'     => '1985-12-10',
            'gender'        => 'M',
            'belfioreCode'  => 'A562',
          ]), 0, 'RSSGFR85T10A562I' ],
          [ new Subject([
            'name'          => 'Mario',
            'surname'       => 'Fo',
            'birthDate'     => '1985-12-10',
            'gender'        => 'M',
            'belfioreCode'  => 'A562',
          ]), 0, 'FOXMRA85T10A562G' ],
          [ new Subject([
            'name'          => '',
            'surname'       => 'Rossi',
            'birthDate'     => '1985-12-10',
            'gender'        => 'M',
            'belfioreCode'  => 'A562',
          ]), 0, 'RSSXXX85T10A562R' ],
          [ new Subject([
            'name'          => 'Mario',
            'surname'       => '',
            'birthDate'     => '1985-12-10',
            'gender'        => 'M',
            'belfioreCode'  => 'A562',
          ]), 0, 'XXXMRA85T10A562B' ],
          [ new Subject([
            'name'          => '',
            'surname'       => '',
            'birthDate'     => '1985-12-10',
            'gender'        => 'M',
            'belfioreCode'  => 'A562',
          ]), 0, 'XXXXXX85T10A562A' ],
          [ new Subject([
            'name'          => 'Roberto',
            'surname'       => 'Santi',
            'birthDate'     => '1963-05-08',
            'gender'        => 'M',
            'belfioreCode'  => 'H501',
          ]), 1, 'SNTRRT63E08H50ML' ],
          [ new Subject([
            'name'          => 'Mario',
            'surname'       => 'Rossi',
            'birthDate'     => '1985-12-10',
            'gender'        => 'M',
            'belfioreCode'  => 'A562',
          ]), 1, 'RSSMRA85T10A56NH' ],
          [ new Subject([
            'name'          => 'Mario',
            'surname'       => 'Rossi',
            'birthDate'     => '1985-12-10',
            'gender'        => 'M',
            'belfioreCode'  => 'A562',
          ]), 3, 'RSSMRA85T10ARSNO' ],
          [ new Subject([
            'name'          => 'Mario',
            'surname'       => 'De Rossi',
            'birthDate'     => '1990-01-01',
            'gender'        => 'M',
            'belfioreCode'  => 'F839',
          ]), Checker::ALL_OMOCODIA_LEVELS, 'DRSMRA90A01F839W' ],
          [ new Subject([
            'name'          => 'Mario',
            'surname'       => "D'Ossi",
            'birthDate'     => '1990-01-01',
            'gender'        => 'M',
            'belfioreCode'  => 'F839',
          ]), Checker::ALL_OMOCODIA_LEVELS, 'DSSMRA90A01F839X' ],
          [ new Subject([
            'name'          => 'Anna Paola',
            'surname'       => 'Rossi',
            'birthDate'     => '1990-01-01',
            'gender'        => 'F',
            'belfioreCode'  => 'F839',
          ]), Checker::ALL_OMOCODIA_LEVELS, 'RSSNPL90A41F839J' ],
        ];
    }

    /**
     * The calculate all possibilities data provider.
     */
    public function calculateAllPossibilitiesProvider()
    {
        return [
          [ new Subject([
              'name'          => 'Mario',
              'surname'       => 'Rossi',
              'birthDate'     => '1985-12-10',
              'gender'        => 'M',
              'belfioreCode'  => 'A562',
            ]), [
              'RSSMRA85T10A562S',
              'RSSMRA85T10A56NH',
              'RSSMRA85T10A5SNT',
              'RSSMRA85T10ARSNO',
              'RSSMRA85T1LARSNR',
              'RSSMRA85TMLARSNC',
              'RSSMRA8RTMLARSNO',
              'RSSMRAURTMLARSNL',
            ],
          ],
          [ new Subject([
              'name'          => 'Roberto',
              'surname'       => 'Santi',
              'birthDate'     => '1963-05-08',
              'gender'        => 'M',
              'belfioreCode'  => 'H501',
            ]), [
              'SNTRRT63E08H501T',
              'SNTRRT63E08H50ML',
              'SNTRRT63E08H5LMW',
              'SNTRRT63E08HRLMR',
              'SNTRRT63E0UHRLMO',
              'SNTRRT63ELUHRLMZ',
              'SNTRRT6PELUHRLML',
              'SNTRRTSPELUHRLMI',
            ],
          ],
        ];
    }
}
