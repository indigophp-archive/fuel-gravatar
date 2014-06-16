<?php

/*
 * This file is part of the Fuel Gravatar package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Fuel;

/**
 * Gravatar class
 *
 * @author Tamás Barta <barta.tamas.d@gmail.com>
 */
class Gravatar extends \Facade
{
	use \Indigo\Core\Util\Config;

	protected static $_config = 'gravatar';

	/**
	 * Holds the email set for this instance
	 *
	 * @var string
	 */
	protected $email = null;

	/**
	 * Hashed email
	 *
	 * @var string
	 */
	protected $hash = null;

	/**
	 * Make it instantiable
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct() {}

	/**
	 * Gravatar forge
	 *
	 * @param  string   $email  Email address
	 * @param  array    $config Config array
	 * @return Gravatar
	 */
	public static function forge($email, array $config = array())
	{
		$config = \Arr::merge(\Config::get('gravatar', array()), $config);
		$instance = new static();
		$instance->setConfig($config)->setEmail($email);

		return static::newInstance($email, $instance);
	}

	/**
	 * Returns the email
	 *
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Sets the email and hash
	 *
	 * @param string $email
	 *
	 * @return Gravatar
	 */
	public function setEmail($email)
	{
		$email = strtolower(trim($email));
		$this->email = $email;
		$this->hash = md5($email);

		return $this;
	}

	/**
	 * Creates a URL
	 *
	 * @param array  $query Array of query elements
	 * @param string $url   URL
	 *
	 * @return string
	 */
	protected function url($url, array $query = array())
	{
		$protocol = $this->getConfig('protocol', false);

		if (isset($protocol) === false)
		{
			$protocol = strtolower(\Input::protocol());
		}

		$url = $protocol . '://' . $url;

		return \Uri::create($url, array(), $query);
	}

	/**
	 * Creates an avatar
	 *
	 * @param boolean $img  Return HTML img
	 * @param array   $attr HTML img attributes
	 *
	 * @return string URL of image or HTML img tag
	 */
	public function avatar($img = false, array $attr = array())
	{
		$config = array(
			's' => $this->getConfig('size'),
			'd' => $this->getConfig('default'),
			'f' => $this->getConfig('force', false) !== true ? null : 'y',
			'r' => strtolower($this->getConfig('rating'))
		);

		$config = array_filter($config);
		$url = $this->url('www.gravatar.com/avatar/' . $this->hash, $config);

		if ($img === true)
		{
			$attr = \Arr::merge(array(
				'width'  => \Arr::get($config, 's', 80),
				'height' => \Arr::get($config, 's', 80),
				'alt'    => 'Gravatar'
			), $attr);

			return \Html::img($url, $attr);
		}

		return $url;
	}

	/**
	 * Gets profile from Gravatar
	 *
	 * @return array
	 */
	public function profile()
	{
		$format = $this->getConfig('format', 'xml') ?: 'xml';
		$config = array();

		$format == 'json' and \Arr::set($config, 'c', $this->getConfig('callback'));

		$url = $this->url('www.gravatar.com/' . $this->hash . '.' . $format);

		$request = \Request::forge($url, 'curl');
		$format == 'php' and $format = 'serialize';
		$request->add_param($config);

		try
		{
			$request->execute();
		}
		catch (\RequestException $e) { }

		$response = $request->response();

		switch ($response->status)
		{
			case 200:
				if ($this->getConfig('auto_format', true) === true)
				{
					$response->body = \Format::forge($response->body, $format)->to_array();
					$format == 'serialize' and $response->body['entry'] = reset($response->body['entry']);
					$response->body = reset($response->body);
				}
				return $response->body;
				break;
			case 403:
				throw new \FuelException('Gravatar: Access to service Forbidden');
				break;
			case 404:
				throw new \FuelException('Gravatar: User not found (' . $this->email . ')');
				break;
			default:
				throw new \FuelException('Gravatar: Unknown error');
				break;
		}
	}

	/**
	 * Creates a QR-code
	 *
	 * @param boolean $img  Return HTML img
	 * @param array   $attr HTML img attributes
	 *
	 * @return string
	 */
	public function qr($img = false, array $attr = array())
	{
		$config = array_filter(array('s' => $this->getConfig('size')));
		$url = $this->url('www.gravatar.com/' . $this->hash . '.qr', $config);

		if ($img === true)
		{
			$attr = \Arr::merge(array(
				'width'  => \Arr::get($config, 's', 80),
				'height' => \Arr::get($config, 's', 80),
				'alt'    => 'Gravatar QR-code'
			), $attr);
			return \Html::img($url, $attr);
		}

		return $url;
	}

	/**
	 * Creates a VCF link
	 *
	 * @param boolean $anchor Return HTML anchor
	 * @param string  $title  HTML anchor title
	 * @param array   $attr   HTML anchor attributes
	 *
	 * @return string
	 */
	public function vcf($anchor = false, $title = '', array $attr = array())
	{
		$url = $this->url('www.gravatar.com/' . $this->hash . '.vcf');

		if ($anchor === true)
		{
			return \Html::anchor($url, $title, $attr);
		}

		return $url;
	}
}
