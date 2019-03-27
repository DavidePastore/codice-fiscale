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
    public function testValidationTrue($codiceFiscaleToValidate, $omocodiaAllowed, $secular, $isFormallyValid, $isOmocodia = false)
    {
        $validator = new Validator($codiceFiscaleToValidate,
            array('omocodiaAllowed' => $omocodiaAllowed,
                  'secular' => $secular)
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
    public function validatorProvider()
    {
        return array(
            // valid
          array(
            'RSSMRA85T10A562S',
            true,
            false,
            true,
          ),
            // valid altough omocodia not allowed
          array(
            'RSSMRA85T10A562S',
            false,
            false,
            true,
          ),
           // check digit
          array(
            'RSSMRA85T10A562T',
            true,
            false,
            false,
          ),
            // valid with omocodia
          array(
            'SNTRRT63E08H50ML',
            true,
            false,
            true,
            true
          ),
            // empty
          array(
            '',
            true,
            false,
            false,
          ),
            // length
          array(
            'RSSMRA85T10A562',
            true,
            false,
            false,
          ),
            // regexp
          array(
            'RS3MRA85T10A562S',
            true,
            false,
            false,
          ),
            // birth day
          array(
            'RSSMRA85T99A562U',
            true,
            false,
            false,
          ),
            // omocodia character
          array(
            'RSSMRA85T10A56AO',
            true,
            false,
            false,
          ),
            // month
          array(
            'RSSMRA85Z10A562B',
            true,
            false,
            false,
          ),
            // date
          array(
            'RSSMRA85B30A562G',
            true,
            false,
            false,
          ),
        );
    }
}
