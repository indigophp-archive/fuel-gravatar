# Fuel Gravatar

[![Latest Stable Version](https://poser.pugx.org/indigophp/fuel-gravatar/v/stable.png)](https://packagist.org/packages/indigophp/fuel-gravatar)
[![Total Downloads](https://poser.pugx.org/indigophp/fuel-gravatar/downloads.png)](https://packagist.org/packages/indigophp/fuel-gravatar)
[![License](https://poser.pugx.org/indigophp/fuel-gravatar/license.png)](https://packagist.org/packages/indigophp/fuel-gravatar)

**Fuel package to get Avatar, Profile data, QR-code and VCF from Gravatar**


## Install

Via Composer

``` json
{
    "require": {
        "indigophp/fuel-gravatar": "@stable"
    }
}
```


## Usage

``` php
$gravatar = \Gravatar::forge('YOUR@EMAILADDRESS.COM');

// Return plain URL of avatar
$gravatar->avatar();

// Return HTML img tag
$gravatar->avatar(true, array('class' => 'gravatar'));


// Return profile as array
$gravatar->profile();


// Return plain URL of QR code
$gravatar->qr();

// Return HTML img tag
$gravatar->qr(true, array('class' => 'gravatar'));


// Return plain URL of VCF
$gravatar->vcf();

// Return HTML anchor tag
$gravatar->vcf(true, 'Link to VCF', array('class' => 'gravatar'));
```


## Configuration

* ```protocol``` (http | https | __null__): use https or http. Leaving unset or setting to null means ```\Input::protocol()``` will be used.
* ```size``` (integer | __null__): Size of avatar (1-2048 px) or QR-code (1-500 px). The default value is 80px.
* ```default```: Default picture in case of none found (Values: 404, mm, identicon, monsterid, wavatar, retro, __blank__, URL)
* ```force``` (true | __false__): Return the default image, even if the user is found
* ```rating``` ( __G__ | PG | R | X): Image rating. See [this](http://hu.gravatar.com/site/implement/images/#rating) link
* ```format``` ( __xml__, json, php): Format of returned data from server
* ```auto``` ( __true__ | false): Autoformat response
* ```callback``` (string): Function wrapped around JSON result


## Credits

- [Tamás Barta](https://github.com/TamasBarta)
- [Márk Sági-Kazár](https://github.com/sagikazarmark)
- [All Contributors](https://github.com/indigophp/fuel-gravatar/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/indigophp/fuel-gravatar/blob/develop/LICENSE) for more information.
