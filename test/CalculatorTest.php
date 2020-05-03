<?php

namespace CodiceFiscale;

/**
 * Test for the Calculator class.
 *
 * @author davidepastore
 */
class CalculatorTest extends \PHPUnit\Framework\TestCase
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
                'name' => 'Davide',
                'surname' => 'Rossi',
                'birthDate' => '1989-12-10',
                'gender' => 'M',
                'belfioreCode' => 'A562',
              )
            ),
            0,
            'RSSDVD89T10A562S',
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
            'RSSMRA85T10A5SNT',
          ),
          array(
            new Subject(
              array(
                'name' => 'Mario',
                'surname' => 'De Rossi',
                'birthDate' => '1990-01-01',
                'gender' => 'M',
                'belfioreCode' => 'F839',
              )
            ),
            -1,
            'DRSMRA90A01F839W',
          ),
          array(
            new Subject(
              array(
                'name' => 'Anna Paola',
                'surname' => 'Rossi',
                'birthDate' => '1990-01-01',
                'gender' => 'F',
                'belfioreCode' => 'F839',
              )
            ),
            -1,
            'RSSNPL90A41F839J',
          ),
          array(
            new Subject(
              array(
                'name' => 'Mario',
                'surname' => "D'Ossi",
                'birthDate' => '1990-01-01',
                'gender' => 'M',
                'belfioreCode' => 'F839',
              )
            ),
            -1,
            'DSSMRA90A01F839X',
          ),
          array(
            new Subject(
              array(
                'name' => "Lu'ay",
                'surname' => "D'ari",
                'birthDate' => '1990-01-01',
                'gender' => 'M',
                'belfioreCode' => 'F839',
              )
            ),
            -1,
            'DRALYU90A01F839U',
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
            -1,
            'LSINLS80D44H501F',
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
              'RSSMRA85T10A5S2E',
              'RSSMRA85T10A5SNT',
              'RSSMRA85T10AR62N',
              'RSSMRA85T10AR6NC',
              'RSSMRA85T10ARS2Z',
              'RSSMRA85T10ARSNO',
              'RSSMRA85T1LA562V',
              'RSSMRA85T1LA56NK',
              'RSSMRA85T1LA5S2H',
              'RSSMRA85T1LA5SNW',
              'RSSMRA85T1LAR62Q',
              'RSSMRA85T1LAR6NF',
              'RSSMRA85T1LARS2C',
              'RSSMRA85T1LARSNR',
              'RSSMRA85TM0A562D',
              'RSSMRA85TM0A56NS',
              'RSSMRA85TM0A5S2P',
              'RSSMRA85TM0A5SNE',
              'RSSMRA85TM0AR62Y',
              'RSSMRA85TM0AR6NN',
              'RSSMRA85TM0ARS2K',
              'RSSMRA85TM0ARSNZ',
              'RSSMRA85TMLA562G',
              'RSSMRA85TMLA56NV',
              'RSSMRA85TMLA5S2S',
              'RSSMRA85TMLA5SNH',
              'RSSMRA85TMLAR62B',
              'RSSMRA85TMLAR6NQ',
              'RSSMRA85TMLARS2N',
              'RSSMRA85TMLARSNC',
              'RSSMRA8RT10A562E',
              'RSSMRA8RT10A56NT',
              'RSSMRA8RT10A5S2Q',
              'RSSMRA8RT10A5SNF',
              'RSSMRA8RT10AR62Z',
              'RSSMRA8RT10AR6NO',
              'RSSMRA8RT10ARS2L',
              'RSSMRA8RT10ARSNA',
              'RSSMRA8RT1LA562H',
              'RSSMRA8RT1LA56NW',
              'RSSMRA8RT1LA5S2T',
              'RSSMRA8RT1LA5SNI',
              'RSSMRA8RT1LAR62C',
              'RSSMRA8RT1LAR6NR',
              'RSSMRA8RT1LARS2O',
              'RSSMRA8RT1LARSND',
              'RSSMRA8RTM0A562P',
              'RSSMRA8RTM0A56NE',
              'RSSMRA8RTM0A5S2B',
              'RSSMRA8RTM0A5SNQ',
              'RSSMRA8RTM0AR62K',
              'RSSMRA8RTM0AR6NZ',
              'RSSMRA8RTM0ARS2W',
              'RSSMRA8RTM0ARSNL',
              'RSSMRA8RTMLA562S',
              'RSSMRA8RTMLA56NH',
              'RSSMRA8RTMLA5S2E',
              'RSSMRA8RTMLA5SNT',
              'RSSMRA8RTMLAR62N',
              'RSSMRA8RTMLAR6NC',
              'RSSMRA8RTMLARS2Z',
              'RSSMRA8RTMLARSNO',
              'RSSMRAU5T10A562P',
              'RSSMRAU5T10A56NE',
              'RSSMRAU5T10A5S2B',
              'RSSMRAU5T10A5SNQ',
              'RSSMRAU5T10AR62K',
              'RSSMRAU5T10AR6NZ',
              'RSSMRAU5T10ARS2W',
              'RSSMRAU5T10ARSNL',
              'RSSMRAU5T1LA562S',
              'RSSMRAU5T1LA56NH',
              'RSSMRAU5T1LA5S2E',
              'RSSMRAU5T1LA5SNT',
              'RSSMRAU5T1LAR62N',
              'RSSMRAU5T1LAR6NC',
              'RSSMRAU5T1LARS2Z',
              'RSSMRAU5T1LARSNO',
              'RSSMRAU5TM0A562A',
              'RSSMRAU5TM0A56NP',
              'RSSMRAU5TM0A5S2M',
              'RSSMRAU5TM0A5SNB',
              'RSSMRAU5TM0AR62V',
              'RSSMRAU5TM0AR6NK',
              'RSSMRAU5TM0ARS2H',
              'RSSMRAU5TM0ARSNW',
              'RSSMRAU5TMLA562D',
              'RSSMRAU5TMLA56NS',
              'RSSMRAU5TMLA5S2P',
              'RSSMRAU5TMLA5SNE',
              'RSSMRAU5TMLAR62Y',
              'RSSMRAU5TMLAR6NN',
              'RSSMRAU5TMLARS2K',
              'RSSMRAU5TMLARSNZ',
              'RSSMRAURT10A562B',
              'RSSMRAURT10A56NQ',
              'RSSMRAURT10A5S2N',
              'RSSMRAURT10A5SNC',
              'RSSMRAURT10AR62W',
              'RSSMRAURT10AR6NL',
              'RSSMRAURT10ARS2I',
              'RSSMRAURT10ARSNX',
              'RSSMRAURT1LA562E',
              'RSSMRAURT1LA56NT',
              'RSSMRAURT1LA5S2Q',
              'RSSMRAURT1LA5SNF',
              'RSSMRAURT1LAR62Z',
              'RSSMRAURT1LAR6NO',
              'RSSMRAURT1LARS2L',
              'RSSMRAURT1LARSNA',
              'RSSMRAURTM0A562M',
              'RSSMRAURTM0A56NB',
              'RSSMRAURTM0A5S2Y',
              'RSSMRAURTM0A5SNN',
              'RSSMRAURTM0AR62H',
              'RSSMRAURTM0AR6NW',
              'RSSMRAURTM0ARS2T',
              'RSSMRAURTM0ARSNI',
              'RSSMRAURTMLA562P',
              'RSSMRAURTMLA56NE',
              'RSSMRAURTMLA5S2B',
              'RSSMRAURTMLA5SNQ',
              'RSSMRAURTMLAR62K',
              'RSSMRAURTMLAR6NZ',
              'RSSMRAURTMLARS2W',
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
              'SNTRRT63E08H5L1E',
              'SNTRRT63E08H5LMW',
              'SNTRRT63E08HR01O',
              'SNTRRT63E08HR0MG',
              'SNTRRT63E08HRL1Z',
              'SNTRRT63E08HRLMR',
              'SNTRRT63E0UH501Q',
              'SNTRRT63E0UH50MI',
              'SNTRRT63E0UH5L1B',
              'SNTRRT63E0UH5LMT',
              'SNTRRT63E0UHR01L',
              'SNTRRT63E0UHR0MD',
              'SNTRRT63E0UHRL1W',
              'SNTRRT63E0UHRLMO',
              'SNTRRT63EL8H501E',
              'SNTRRT63EL8H50MW',
              'SNTRRT63EL8H5L1P',
              'SNTRRT63EL8H5LMH',
              'SNTRRT63EL8HR01Z',
              'SNTRRT63EL8HR0MR',
              'SNTRRT63EL8HRL1K',
              'SNTRRT63EL8HRLMC',
              'SNTRRT63ELUH501B',
              'SNTRRT63ELUH50MT',
              'SNTRRT63ELUH5L1M',
              'SNTRRT63ELUH5LME',
              'SNTRRT63ELUHR01W',
              'SNTRRT63ELUHR0MO',
              'SNTRRT63ELUHRL1H',
              'SNTRRT63ELUHRLMZ',
              'SNTRRT6PE08H501F',
              'SNTRRT6PE08H50MX',
              'SNTRRT6PE08H5L1Q',
              'SNTRRT6PE08H5LMI',
              'SNTRRT6PE08HR01A',
              'SNTRRT6PE08HR0MS',
              'SNTRRT6PE08HRL1L',
              'SNTRRT6PE08HRLMD',
              'SNTRRT6PE0UH501C',
              'SNTRRT6PE0UH50MU',
              'SNTRRT6PE0UH5L1N',
              'SNTRRT6PE0UH5LMF',
              'SNTRRT6PE0UHR01X',
              'SNTRRT6PE0UHR0MP',
              'SNTRRT6PE0UHRL1I',
              'SNTRRT6PE0UHRLMA',
              'SNTRRT6PEL8H501Q',
              'SNTRRT6PEL8H50MI',
              'SNTRRT6PEL8H5L1B',
              'SNTRRT6PEL8H5LMT',
              'SNTRRT6PEL8HR01L',
              'SNTRRT6PEL8HR0MD',
              'SNTRRT6PEL8HRL1W',
              'SNTRRT6PEL8HRLMO',
              'SNTRRT6PELUH501N',
              'SNTRRT6PELUH50MF',
              'SNTRRT6PELUH5L1Y',
              'SNTRRT6PELUH5LMQ',
              'SNTRRT6PELUHR01I',
              'SNTRRT6PELUHR0MA',
              'SNTRRT6PELUHRL1T',
              'SNTRRT6PELUHRLML',
              'SNTRRTS3E08H501Q',
              'SNTRRTS3E08H50MI',
              'SNTRRTS3E08H5L1B',
              'SNTRRTS3E08H5LMT',
              'SNTRRTS3E08HR01L',
              'SNTRRTS3E08HR0MD',
              'SNTRRTS3E08HRL1W',
              'SNTRRTS3E08HRLMO',
              'SNTRRTS3E0UH501N',
              'SNTRRTS3E0UH50MF',
              'SNTRRTS3E0UH5L1Y',
              'SNTRRTS3E0UH5LMQ',
              'SNTRRTS3E0UHR01I',
              'SNTRRTS3E0UHR0MA',
              'SNTRRTS3E0UHRL1T',
              'SNTRRTS3E0UHRLML',
              'SNTRRTS3EL8H501B',
              'SNTRRTS3EL8H50MT',
              'SNTRRTS3EL8H5L1M',
              'SNTRRTS3EL8H5LME',
              'SNTRRTS3EL8HR01W',
              'SNTRRTS3EL8HR0MO',
              'SNTRRTS3EL8HRL1H',
              'SNTRRTS3EL8HRLMZ',
              'SNTRRTS3ELUH501Y',
              'SNTRRTS3ELUH50MQ',
              'SNTRRTS3ELUH5L1J',
              'SNTRRTS3ELUH5LMB',
              'SNTRRTS3ELUHR01T',
              'SNTRRTS3ELUHR0ML',
              'SNTRRTS3ELUHRL1E',
              'SNTRRTS3ELUHRLMW',
              'SNTRRTSPE08H501C',
              'SNTRRTSPE08H50MU',
              'SNTRRTSPE08H5L1N',
              'SNTRRTSPE08H5LMF',
              'SNTRRTSPE08HR01X',
              'SNTRRTSPE08HR0MP',
              'SNTRRTSPE08HRL1I',
              'SNTRRTSPE08HRLMA',
              'SNTRRTSPE0UH501Z',
              'SNTRRTSPE0UH50MR',
              'SNTRRTSPE0UH5L1K',
              'SNTRRTSPE0UH5LMC',
              'SNTRRTSPE0UHR01U',
              'SNTRRTSPE0UHR0MM',
              'SNTRRTSPE0UHRL1F',
              'SNTRRTSPE0UHRLMX',
              'SNTRRTSPEL8H501N',
              'SNTRRTSPEL8H50MF',
              'SNTRRTSPEL8H5L1Y',
              'SNTRRTSPEL8H5LMQ',
              'SNTRRTSPEL8HR01I',
              'SNTRRTSPEL8HR0MA',
              'SNTRRTSPEL8HRL1T',
              'SNTRRTSPEL8HRLML',
              'SNTRRTSPELUH501K',
              'SNTRRTSPELUH50MC',
              'SNTRRTSPELUH5L1V',
              'SNTRRTSPELUH5LMN',
              'SNTRRTSPELUHR01F',
              'SNTRRTSPELUHR0MX',
              'SNTRRTSPELUHRL1Q',
              'SNTRRTSPELUHRLMI',
            ),
          ),
        );
    }
}
