<?php

namespace CodiceFiscale;

/**
 * Test for the Validator class.
 *
 * @author Antonio Turdo <antonio.turdo@gmail.com>
 */
class ValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test for validator.
     *
     * @dataProvider validatorProvider
     */
    public function testValidationTrue(
        string $codiceFiscaleToValidate,
        bool $omocodiaAllowed,
        bool $secular,
        bool $isFormallyValid,
        bool $isOmocodia = false
    ): void {
        $validator = new Validator(
            $codiceFiscaleToValidate,
            ['omocodiaAllowed' => $omocodiaAllowed,
                  'secular' => $secular]
        );

        $actual = $validator->isFormallyValid();
        $this->assertEquals($isFormallyValid, $actual);

        $this->{$isFormallyValid ? "assertEquals" : "assertNotEquals"}(null, $validator->getError());

        if ($isFormallyValid && $isOmocodia) {
            $this->assertEquals($isOmocodia, $validator->isOmocodia());
        }
    }

    /**
     * The validator provider.
     */
    public static function validatorProvider(): array
    {
        return [
          'valid' => [
            'RSSMRA85T10A562S',
            true,
            false,
            true,
          ],
          'valid although omocodia not allowed' => [
            'RSSMRA85T10A562S',
            false,
            false,
            true,
          ],
          'check digit' => [
            'RSSMRA85T10A562T',
            true,
            false,
            false,
          ],
          'valid with omocodia' => [
            'SNTRRT63E08H50ML',
            true,
            false,
            true,
            true
          ],
          'empty' => [
            '',
            true,
            false,
            false,
          ],
          'length' => [
            'RSSMRA85T10A562',
            true,
            false,
            false,
          ],
          'regexp' => [
            'RS3MRA85T10A562S',
            true,
            false,
            false,
          ],
          'birthday' => [
            'RSSMRA85T99A562U',
            true,
            false,
            false,
          ],
          'omocodia character' => [
            'RSSMRA85T10A56AO',
            true,
            false,
            false,
          ],
          'month' => [
            'RSSMRA85Z10A562B',
            true,
            false,
            false,
          ],
          'date' => [
            'RSSMRA85B30A562G',
            true,
            false,
            false,
          ],
        ];
    }
}
