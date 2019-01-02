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

*Read this in [Italian](README-it.md).*


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
echo $codiceFiscale; //"RSSMRA85T10A56NH"
```


You can also calculate all codici fiscali that a subject could have (using all 128 available levels).

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
Array
(
    [0] => RSSMRA85T10A562S
    [1] => RSSMRA85T10A56NH
    [2] => RSSMRA85T10A5S2E
    [3] => RSSMRA85T10A5SNT
    [4] => RSSMRA85T10AR62N
    [5] => RSSMRA85T10AR6NC
    [6] => RSSMRA85T10ARS2Z
    [7] => RSSMRA85T10ARSNO
    [8] => RSSMRA85T1LA562V
    [9] => RSSMRA85T1LA56NK
    [10] => RSSMRA85T1LA5S2H
    [11] => RSSMRA85T1LA5SNW
    [12] => RSSMRA85T1LAR62Q
    [13] => RSSMRA85T1LAR6NF
    [14] => RSSMRA85T1LARS2C
    [15] => RSSMRA85T1LARSNR
    [16] => RSSMRA85TM0A562D
    [17] => RSSMRA85TM0A56NS
    [18] => RSSMRA85TM0A5S2P
    [19] => RSSMRA85TM0A5SNE
    [20] => RSSMRA85TM0AR62Y
    [21] => RSSMRA85TM0AR6NN
    [22] => RSSMRA85TM0ARS2K
    [23] => RSSMRA85TM0ARSNZ
    [24] => RSSMRA85TMLA562G
    [25] => RSSMRA85TMLA56NV
    [26] => RSSMRA85TMLA5S2S
    [27] => RSSMRA85TMLA5SNH
    [28] => RSSMRA85TMLAR62B
    [29] => RSSMRA85TMLAR6NQ
    [30] => RSSMRA85TMLARS2N
    [31] => RSSMRA85TMLARSNC
    [32] => RSSMRA8RT10A562E
    [33] => RSSMRA8RT10A56NT
    [34] => RSSMRA8RT10A5S2Q
    [35] => RSSMRA8RT10A5SNF
    [36] => RSSMRA8RT10AR62Z
    [37] => RSSMRA8RT10AR6NO
    [38] => RSSMRA8RT10ARS2L
    [39] => RSSMRA8RT10ARSNA
    [40] => RSSMRA8RT1LA562H
    [41] => RSSMRA8RT1LA56NW
    [42] => RSSMRA8RT1LA5S2T
    [43] => RSSMRA8RT1LA5SNI
    [44] => RSSMRA8RT1LAR62C
    [45] => RSSMRA8RT1LAR6NR
    [46] => RSSMRA8RT1LARS2O
    [47] => RSSMRA8RT1LARSND
    [48] => RSSMRA8RTM0A562P
    [49] => RSSMRA8RTM0A56NE
    [50] => RSSMRA8RTM0A5S2B
    [51] => RSSMRA8RTM0A5SNQ
    [52] => RSSMRA8RTM0AR62K
    [53] => RSSMRA8RTM0AR6NZ
    [54] => RSSMRA8RTM0ARS2W
    [55] => RSSMRA8RTM0ARSNL
    [56] => RSSMRA8RTMLA562S
    [57] => RSSMRA8RTMLA56NH
    [58] => RSSMRA8RTMLA5S2E
    [59] => RSSMRA8RTMLA5SNT
    [60] => RSSMRA8RTMLAR62N
    [61] => RSSMRA8RTMLAR6NC
    [62] => RSSMRA8RTMLARS2Z
    [63] => RSSMRA8RTMLARSNO
    [64] => RSSMRAU5T10A562P
    [65] => RSSMRAU5T10A56NE
    [66] => RSSMRAU5T10A5S2B
    [67] => RSSMRAU5T10A5SNQ
    [68] => RSSMRAU5T10AR62K
    [69] => RSSMRAU5T10AR6NZ
    [70] => RSSMRAU5T10ARS2W
    [71] => RSSMRAU5T10ARSNL
    [72] => RSSMRAU5T1LA562S
    [73] => RSSMRAU5T1LA56NH
    [74] => RSSMRAU5T1LA5S2E
    [75] => RSSMRAU5T1LA5SNT
    [76] => RSSMRAU5T1LAR62N
    [77] => RSSMRAU5T1LAR6NC
    [78] => RSSMRAU5T1LARS2Z
    [79] => RSSMRAU5T1LARSNO
    [80] => RSSMRAU5TM0A562A
    [81] => RSSMRAU5TM0A56NP
    [82] => RSSMRAU5TM0A5S2M
    [83] => RSSMRAU5TM0A5SNB
    [84] => RSSMRAU5TM0AR62V
    [85] => RSSMRAU5TM0AR6NK
    [86] => RSSMRAU5TM0ARS2H
    [87] => RSSMRAU5TM0ARSNW
    [88] => RSSMRAU5TMLA562D
    [89] => RSSMRAU5TMLA56NS
    [90] => RSSMRAU5TMLA5S2P
    [91] => RSSMRAU5TMLA5SNE
    [92] => RSSMRAU5TMLAR62Y
    [93] => RSSMRAU5TMLAR6NN
    [94] => RSSMRAU5TMLARS2K
    [95] => RSSMRAU5TMLARSNZ
    [96] => RSSMRAURT10A562B
    [97] => RSSMRAURT10A56NQ
    [98] => RSSMRAURT10A5S2N
    [99] => RSSMRAURT10A5SNC
    [100] => RSSMRAURT10AR62W
    [101] => RSSMRAURT10AR6NL
    [102] => RSSMRAURT10ARS2I
    [103] => RSSMRAURT10ARSNX
    [104] => RSSMRAURT1LA562E
    [105] => RSSMRAURT1LA56NT
    [106] => RSSMRAURT1LA5S2Q
    [107] => RSSMRAURT1LA5SNF
    [108] => RSSMRAURT1LAR62Z
    [109] => RSSMRAURT1LAR6NO
    [110] => RSSMRAURT1LARS2L
    [111] => RSSMRAURT1LARSNA
    [112] => RSSMRAURTM0A562M
    [113] => RSSMRAURTM0A56NB
    [114] => RSSMRAURTM0A5S2Y
    [115] => RSSMRAURTM0A5SNN
    [116] => RSSMRAURTM0AR62H
    [117] => RSSMRAURTM0AR6NW
    [118] => RSSMRAURTM0ARS2T
    [119] => RSSMRAURTM0ARSNI
    [120] => RSSMRAURTMLA562P
    [121] => RSSMRAURTMLA56NE
    [122] => RSSMRAURTMLA5S2B
    [123] => RSSMRAURTMLA5SNQ
    [124] => RSSMRAURTMLAR62K
    [125] => RSSMRAURTMLAR6NZ
    [126] => RSSMRAURTMLARS2W
    [127] => RSSMRAURTMLARSNL
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
var_dump($subject);
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
$ vendor\bin\phpunit
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
