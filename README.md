codice-fiscale
======

[![Latest version][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

[![Build Status][ico-phpeye]][link-phpeye]
[![PSR2 Conformance][ico-styleci]][link-styleci]

A PHP library to calculate and check the italian tax code (codice fiscale).


Install
-------

You can install the library using [composer](https://getcomposer.org/):

```sh
$ composer require davidepastore/codice-fiscale
```

How to use
----------

### Calculate a codice fiscale

Use the calculator to calculate the codice fiscale.

```php
use CodiceFiscale\Calculator;
use CodiceFiscale\Subject;

$subject = new Subject(
  array(
    "name" => "Mario",
    "surname" => "Rossi",
    "birthDate" => "1985-12-10",
    "gender" => "M",
    "belfioreCode" => "A562"
  )
);

$calculator = new Calculator($subject);
$codiceFiscale = $calculator->calculate();
echo $codiceFiscale; //"RSSMRA85T10A562S"
```


You can also add an array for additional configuration for the `Calculator`. Available array keys are:
- "omocodiaLevel": specifies the level of omocodia that the `Calculator` should consider.

```php
use CodiceFiscale\Calculator;
use CodiceFiscale\Subject;

$subject = new Subject(
  array(
    "name" => "Mario",
    "surname" => "Rossi",
    "birthDate" => "1985-12-10",
    "gender" => "M",
    "belfioreCode" => "A562"
  )
);

$calculator = new Calculator($subject, array(
  "omocodiaLevel" => $omocodiaLevel
));
$codiceFiscale = $calculator->calculate();
echo $codiceFiscale; //"RSSMRA85T10A562S"
```


You can also calculate all codici fiscali that a subject could have (using all 8 available levels).

```php
use CodiceFiscale\Calculator;
use CodiceFiscale\Subject;

$subject = new Subject(
  array(
    "name" => "Mario",
    "surname" => "Rossi",
    "birthDate" => "1985-12-10",
    "gender" => "M",
    "belfioreCode" => "A562"
  )
);

$calculator = new Calculator($subject);
$codiciFiscali = $calculator->calculateAllPossibilities();
print_r($codiciFiscali);
/*
array(
  "RSSMRA85T10A562S",
  "RSSMRA85T10A56NH",
  "RSSMRA85T10A5SNT",
  "RSSMRA85T10ARSNO",
  "RSSMRA85T1LARSNR",
  "RSSMRA85TMLARSNC",
  "RSSMRA8RTMLARSNO",
  "RSSMRAURTMLARSNL"
)
*/
```




### Check if it is valid

Use the checker to check if the given codice fiscale is ok for the given `Subject` and additional configuration. The additional configuration array for the `Checker` has the given available keys:
- "codiceFiscaleToCheck": the codice fiscale to check;
- "omocodiaLevel": the omocodia level to use to check.

```php
use CodiceFiscale\Checker;
use CodiceFiscale\Subject;

$subject = new Subject(
  array(
    "name" => "Mario",
    "surname" => "Rossi",
    "birthDate" => "1985-12-10",
    "gender" => "M",
    "belfioreCode" => "A562"
  )
);

$checker = new Checker($subject, array(
  "codiceFiscaleToCheck" => "RSSMRA85T10A562S",
  "omocodiaLevel" => 0
));

$response = $checker->check();
echo $response; // true
```


You can also provide as `omocodiaLevel` key, the value of `Checker::ALL_OMOCODIA_LEVELS` that will check for all the possibilities for the given `Subject`.
In the following example, the `Subject` would not be valid for the given codice fiscale, but it will be so, cause the check will iterate over all the possibilities for the different omocodia levels.

```php
use CodiceFiscale\Checker;
use CodiceFiscale\Subject;

$subject = new Subject(
  array(
    "name" => "Roberto",
    "surname" => "Santi",
    "birthDate" => "1963-05-08",
    "gender" => "M",
    "belfioreCode" => "H501"
  )
);

$checker = new Checker($subject, array(
  "codiceFiscaleToCheck" => "SNTRRT63E08H50ML",
  "omocodiaLevel" => Checker::ALL_OMOCODIA_LEVELS
));

$response = $checker->check();
echo $response; // true
```

### Formal validation

Use the Validator to verify if the given codice fiscale is formally valid. The additional configuration array for the `Validator` has the given available keys:
- "omocodiaAllowed": whether to allow or not omocodia, defaults to true;
- "century": for people over 100 years old, it is not possibile to derive unambiguously birth year, so you can specify the century (for example '18' for a person birth in 1899). It allows to check birth date existence. Defaults to null (auto calculation of the century).

```php
use CodiceFiscale\Validator;

$codiceFiscale = "RSSMRA85T10A562S";

$validator = new Validator($codiceFiscale);

$response = $validator->isFormallyValid();
echo $response; // true
```

### Inverse calculation

Use the InverseCalculator to extract birth date, gender and the belfiore code from the given codice fiscale. The additional configuration array for the `InverseCalculator` has the keys already described for the `Validator`.

```php
use CodiceFiscale\InverseCalculator;

$codiceFiscale = "RSSMRA85T10A562S";

$inverseCalculator = new InverseCalculator($codiceFiscale);

$subject = $inverseCalculator->getSubject();
var_dump($response);
// object(CodiceFiscale\Subject)[449]
//   private 'name' => null
//   private 'surname' => null
//   private 'birthDate' => 
//     object(DateTime)[452]
//       public 'date' => string '1985-12-10 00:00:00.000000' (length=26)
//       public 'timezone_type' => int 3
//       public 'timezone' => string 'Europe/Berlin' (length=13)
//   private 'gender' => string 'M' (length=1)
//   private 'belfioreCode' => string 'A562' (length=4)
```

Test
----

``` bash
$ phpunit
```


Issues
-------

If you have issues, just open one [here](https://github.com/DavidePastore/codice-fiscale/issues).



[ico-version]: https://img.shields.io/packagist/v/DavidePastore/codice-fiscale.svg?style=flat-square
[ico-travis]: https://travis-ci.org/DavidePastore/codice-fiscale.svg?branch=master
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/DavidePastore/codice-fiscale.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/davidepastore/codice-fiscale.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/DavidePastore/codice-fiscale.svg?style=flat-square
[ico-phpeye]: http://php-eye.com/badge/DavidePastore/codice-fiscale/tested.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/46665960/shield

[link-packagist]: https://packagist.org/packages/DavidePastore/codice-fiscale
[link-travis]: https://travis-ci.org/DavidePastore/codice-fiscale
[link-scrutinizer]: https://scrutinizer-ci.com/g/DavidePastore/codice-fiscale/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/DavidePastore/codice-fiscale
[link-downloads]: https://packagist.org/packages/DavidePastore/codice-fiscale
[link-phpeye]: http://php-eye.com/package/DavidePastore/codice-fiscale
[link-styleci]: https://styleci.io/repos/46665960/
