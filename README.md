Fuel Gravatar 0.2.1
===================

Fuel package to get Avatar, Profile data, QR-code and VCF from Gravatar

## Installation
* Clone this repository to your ````PKGPATH````
* Add ````gravatar```` to always_load (or load it manually)

## Usage
````
$gravatar = \Gravatar::forge('YOUR@EMAILADDRESS.COM');
````

### Configuration
* ````protocol```` (true | false | __null__): use https (true) or http (false). Leaving unset or setting to null means ````\Input::protocol()```` will be used.
* ````size```` (integer | __null__): Size of avatar (1-2048 px) or QR-code (1-500 px). The default value is 80px.
* ````default````: Default picture in case of none found (Values: 404, mm, identicon, monsterid, wavatar, retro, __blank__, URL)
* ````force```` (true | __false__): Return the default image, even if the user is found
* ````rating```` (__G__ | PG | R | X): Image rating. See [this](http://hu.gravatar.com/site/implement/images/#rating) link
* ````format```` (__xml__, json, php): Format of returned data from server
* ````auto```` (__true__ | false): Autoformat response
* ````callback```` (string): Function wrapped around JSON result

### Functions

````avatar````: Return avatar

* $img		true | __false__		Return HTML img or plain URL
* $attr		array				Array of HTML attributes

````profile````: Get profile data

````qr````: Return QR-code

* $img		true | __false__		Return HTML img or plain URL
* $attr		array				Array of HTML attributes

````vcf````: Return VCF

* $a		true | __false__		Return HTML anchor or plain URL
* $attr		array				Array of HTML attributes