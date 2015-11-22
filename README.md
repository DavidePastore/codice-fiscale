codice-fiscale
======

[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]


A PHP library to calculate and check the italian tax code (codice fiscale).


Install
-------

You can install the library using [composer](https://getcomposer.org/). Add these lines in your composer.json:

```json
"require" : {
	"davidepastore/codice-fiscale" : "v0.1.0"
}
```

How to use
----------

### Calculate a codice fiscale

Use the calculator to calculate the codice fiscale.

```php
use DavidePastore\CodiceFiscale\Calculator;
use DavidePastore\CodiceFiscale\Subject;

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
use DavidePastore\CodiceFiscale\Calculator;
use DavidePastore\CodiceFiscale\Subject;

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
use DavidePastore\CodiceFiscale\Calculator;
use DavidePastore\CodiceFiscale\Subject;

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
use DavidePastore\CodiceFiscale\Checker;
use DavidePastore\CodiceFiscale\Subject;

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
use DavidePastore\CodiceFiscale\Checker;
use DavidePastore\CodiceFiscale\Subject;

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




Issues
-------

If you have issues, just open one [here](https://github.com/DavidePastore/codice-fiscale/issues).



[ico-travis]: https://travis-ci.org/DavidePastore/codice-fiscale.svg?branch=master
[ico-code-quality]: https://img.shields.io/scrutinizer/g/davidepastore/codice-fiscale.svg?style=flat-square

[link-travis]: https://travis-ci.org/DavidePastore/codice-fiscale
[link-code-quality]: https://scrutinizer-ci.com/g/DavidePastore/codice-fiscale
