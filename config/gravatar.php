<?php

/**
 *
 * FuelPHP Gravatar package configuration file.
 *
 * @package 	Fuel
 * @subpackage	Gravatar
 * @version		0.1
 * @author 		Tamás Barta <barta.tamas.d@gmail.com>
 * @license 	MIT License
 */

return array(
	// Can be http, https, or null. If null, the protocol of the current
	// request is used
	'protocol'		=> null,

	// Valid values are integers, or null. Specifies the size parameter passed
	// to Gravatar, if none is set on a Gravatar class instance.
	'size'	=> null,

	// Valid values are full URLs, or null. Specifies the default image
	// parameter passed to Gravatar, if none is set on a Gravatar class
	// instance.
	'default_image'	=> null,

	// Valid values are "G", "PG", "R", "X", null. Specifies the size parameter
	// passed to Gravatar, if none is set on a Gravatar class instance.
	'rating'		=> null,
);