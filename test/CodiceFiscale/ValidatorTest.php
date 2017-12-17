<?php

namespace CodiceFiscale;

/**
 * Test for the Validator class.
 *
 * @author Antonio Turdo <antonio.turdo@gmail.com>
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test for validator.
     *
     * @dataProvider validatorProvider
     */
    public function testValidationTrue($codiceFiscaleToValidate, $omocodiaAllowed, $secular, $expected)
    {
        $validator = new Validator($codiceFiscaleToValidate,
            array('omocodiaAllowed' => $omocodiaAllowed,
                  'secular' => $secular)
                );
        
        $actual = $validator->isFormallyValid();
        $this->assertEquals($expected, $actual);
    }

    /**
     * The validator provider.
     */
    public function validatorProvider()
    {
        return array(
          array(
            'RSSMRA85T10A562S',
            true,
            false,
            true,
          ),
          array(
            'RSSMRA85T10A562T',
            true,
            false,
            false,
          ),
          array(
            'SNTRRT63E08H50ML',
            true,
            false,
            true,
          ),
        );
    }
}
