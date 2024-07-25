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
    public function testInverseCalculate(string $codiceFiscale, bool $omocodiaAllowed, ?int $century, Subject $expectedSubject): void
    {
        $inverseCalculator = new InverseCalculator($codiceFiscale, [
            'omocodiaAllowed' => $omocodiaAllowed,
            'century' => $century
        ]);
        $actual = $inverseCalculator->getSubject();
        $this->assertEquals($expectedSubject->getBelfioreCode(), $actual->getBelfioreCode());
        $this->assertEquals($expectedSubject->getBirthDate(), $actual->getBirthDate());
        $this->assertEquals($expectedSubject->getGender(), $actual->getGender());
        $this->assertEquals(null, $actual->getName());
        $this->assertEquals(null, $actual->getSurname());
    }

    public function todotestInverseCalculatorDoesNotThrowWarnings(): void
    {
        $inverseCalculator = new InverseCalculator('RNAMSM74D01H501C');
        $this->assertInstanceOf(Subject::class, $inverseCalculator->getSubject());
    }

    /**
     * The calculate data provider.
     */
    public static function calculateProvider(): array
    {
        $subject = new Subject(
            [
            'name' => 'Mario',
            'surname' => 'Rossi',
            'birthDate' => '1985-12-10',
            'gender' => 'M',
            'belfioreCode' => 'A562',
            ]
        );

        return array_merge(
            [
            [
              'RSSMRA85T10A562S',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                  ]
              ),
            ],
            [
              'SNTRRT63E08H501T',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1963-05-08',
                  'gender' => 'M',
                  'belfioreCode' => 'H501',
                  ]
              )
            ],
            [
              'RSSDVD89T10A562S',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1989-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                  ]
              )
            ],
            [
              'MNTMRA70M71C615I',
              true,
              18,
              new Subject(
                  [
                  'birthDate' => '1870-08-31',
                  'gender' => 'F',
                  'belfioreCode' => 'C615',
                  ]
              )
            ],
            [
              'RSSMRA85T10A562S',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                  ]
              ),
            ],
            [
              'RSSGFR85T10A562I',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                  ]
              ),
            ],
            [
              'FOXMRA85T10A562G',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                  ]
              ),
            ],
            [
              'RSSXXX85T10A562R',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                  ]
              )
            ],
            [
              'SNTRRT63E08H50ML',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1963-05-08',
                  'gender' => 'M',
                  'belfioreCode' => 'H501',
                  ]
              )
            ],
            [
              'RSSMRA85T10A56NH',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                  ]
              )
            ],
            [
              'RSSMRA85T10ARSNO',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1985-12-10',
                  'gender' => 'M',
                  'belfioreCode' => 'A562',
                  ]
              ),
            ],
            [
              'DRSMRA90A01F839W',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1990-01-01',
                  'gender' => 'M',
                  'belfioreCode' => 'F839',
                  ]
              )
            ],
            [
              'RSSNPL90A41F839J',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1990-01-01',
                  'gender' => 'F',
                  'belfioreCode' => 'F839',
                  ]
              )
            ],
            [
              'DSSMRA90A01F839X',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1990-01-01',
                  'gender' => 'M',
                  'belfioreCode' => 'F839',
                  ]
              ),
            ],
            [
              'DRALYU90A01F839U',
              true,
              null,
              new Subject(
                  [
                  'birthDate' => '1990-01-01',
                  'gender' => 'M',
                  'belfioreCode' => 'F839',
                  ]
              )
            ],
            ],
            // Handle all the different omocodia levels
            self::generateAllOmocodiaLevels($subject)
        );
    }

    /**
     * Generate all the codici fiscali for the given $subject.
     * @param Subject $subject The subject of which generate all the codici fiscali.
     * @return array Returns all the codici fiscali for the given $subject.
     */
    private static function generateAllOmocodiaLevels(Subject $subject): array
    {
        $allOmocodiaLevels = [];
        for ($omocodiaLevel = 0; $omocodiaLevel < 128; $omocodiaLevel++) {
            $calculator = new Calculator($subject, [
              'omocodiaLevel' => $omocodiaLevel,
            ]);
            $allOmocodiaLevels[] = [
                $calculator->calculate(),
                true,
                null,
                new Subject(
                    [
                    'birthDate' => $subject->getBirthDate(),
                    'gender' => $subject->getGender(),
                    'belfioreCode' => $subject->getBelfioreCode(),
                    ]
                )
            ];
        }

        return $allOmocodiaLevels;
    }
}
