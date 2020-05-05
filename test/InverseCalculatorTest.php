<?php

namespace CodiceFiscale;

/**
 * Test for the InverseValidator class.
 *
 * @author Antonio Turdo <antonio.turdo@gmail.com>
 * @author davidepastore
 */
class InverseCalculatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test for inverse calculate.
     *
     * @dataProvider calculateProvider
     */
    public function testInverseCalculate($codiceFiscale, $omocodiaAllowed, $century, $expectedSubject)
    {
        $inverseCalculator = new InverseCalculator($codiceFiscale, array(
            'omocodiaAllowed' => $omocodiaAllowed,
            'century' => $century
        ));
        $actual = $inverseCalculator->getSubject();
        $this->assertEquals($expectedSubject->getBelfioreCode(), $actual->getBelfioreCode());
        $this->assertEquals($expectedSubject->getBirthDate(), $actual->getBirthDate());
        $this->assertEquals($expectedSubject->getGender(), $actual->getGender());
        $this->assertEquals(null, $actual->getName());
        $this->assertEquals(null, $actual->getSurname());
    }


    /**
     * The calculate data provider.
     */
    public function calculateProvider()
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

        return array_merge(
          array(
            array(
              'RSSMRA85T10A562S',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                )
              ),
            ),
            array(
              'SNTRRT63E08H501T',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1963-05-08',
                  'gender' => 'M',
                  'belfioreCode' => 'H501',
                )
              )
            ),
            array(
              'RSSDVD89T10A562S',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1989-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                )
              )
            ),
            array(
              'MNTMRA70M71C615I',
              true,
              18,
              new Subject(
                array(
                  'birthDate' => '1870-08-31',
                  'gender' => 'F',
                  'belfioreCode' => 'C615',
                )
              )
            ),
            array(
              'RSSMRA85T10A562S',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                )
              ),
            ),
            array(
              'RSSGFR85T10A562I',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                )
              ),
            ),
            array(
              'FOXMRA85T10A562G',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                )
              ),
            ),
            array(
              'RSSXXX85T10A562R',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                )
              )
            ),
            array(
              'SNTRRT63E08H50ML',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1963-05-08',
                  'gender' => 'M',
                  'belfioreCode' => 'H501',
                )
              )
            ),
            array(
              'RSSMRA85T10A56NH',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                )
              )
            ),
            array(
              'RSSMRA85T10ARSNO',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                )
              ),
            ),
            array(
              'DRSMRA90A01F839W',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1990-01-01',
                  'gender' => 'M',
                  'belfioreCode' => 'F839',
                )
              )
            ),
            array(
              'RSSNPL90A41F839J',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1990-01-01',
                  'gender' => 'F',
                  'belfioreCode' => 'F839',
                )
              )
            ),
            array(
              'DSSMRA90A01F839X',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1990-01-01',
                  'gender' => 'M',
                  'belfioreCode' => 'F839',
                )
              ),
            ),
            array(
              'DRALYU90A01F839U',
              true,
              null,
              new Subject(
                array(
                  'birthDate' => '1990-01-01',
                  'gender' => 'M',
                  'belfioreCode' => 'F839',
                )
              )
            ),
          ),
          // Handle all the different omocodia levels
          $this->generateAllOmocodiaLevels($subject)
        );
    }

    /**
     * Generate all the codici fiscali for the given $subject.
     * @param $subject The subject of which generate all the codici fiscali.
     * @returns Returns all the codici fiscali for the given $subject.
     */
    private function generateAllOmocodiaLevels($subject)
    {
        $allOmocodiaLevels = array();
        for ($omocodiaLevel = 0; $omocodiaLevel < 128; $omocodiaLevel++) {
            $calculator = new Calculator($subject, array(
              'omocodiaLevel' => $omocodiaLevel,
            ));
            $allOmocodiaLevels[] = array(
                $calculator->calculate(),
                true,
                null,
                new Subject(
                  array(
                    'birthDate' => $subject->getBirthDate(),
                    'gender' => $subject->getGender(),
                    'belfioreCode' => $subject->getBelfioreCode(),
                  )
                )
            );
        }

        return $allOmocodiaLevels;
    }
}
