<?php
/**
 * Fuel Gravatar
 *
 * @package 	Fuel
 * @subpackage	Gravatar
 * @version		0.3
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
	/**
	* Protocol for request
	*
	* Valid values: true (https), false (http), null (\Input::protocol())
	* Default is \Input::protocol().
	*/
	'protocol' => null,

	/**
	* Image and QR-code size
	*
	* Valid values are integers between 1 and 2048 for avatar and 1 and 500 for QR code
	* Specifies the size of the image in pixels.
	* Please consider that the most users have low-quality images.
	*/
	'size'     => null,

	/**
	* Default image
	*
	* Valid values: 404, mm, identicon, monsterid, wavatar, retro, blank
	* Other valid values: URL, null
	* If URL passed, it should NOT be urlencoded
	*
	* @link http://hu.gravatar.com/site/implement/images/#default-image
	*/
	'default'  => null,

	/**
	* Force default picture
	*
	* Valid values: true, flase
	* Return the default image, even if the user is found
	*/
	'force'    => false,

	/**
	* Image rating
	*
	* Valid values are: G, PG, R, X
	*
	* @link http://hu.gravatar.com/site/implement/images/#rating
	*/
	'rating'   => null,

	/**
	* Format of the returned profile
	*
	* Valid values: xml, json, php
	*/
	'format'   => 'xml',

	/**
	 * Autoformat the response
	 *
	 * Valid values: true, false
	 */
	'auto'     => true,

	/**
	* JSON callback
	*
	* Valid values are any JavaScript functions.
	* Function wrapped around JSON result
	*/
	'callback' => null,
);
