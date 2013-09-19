<?php
/**
 * Fuel Gravatar
 *
 * @package 	Fuel
 * @subpackage	Gravatar
 * @version		0.1
 * @author 		Tamás Barta <barta.tamas.d@gmail.com>
 * @license 	MIT License
 */

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
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