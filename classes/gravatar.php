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

class Gravatar
{
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
	 * Config array
	 *
	 * @var array
	 */
	protected $config = array();


	public function __construct(array $config = array()) {
		$this->config = $config;
	}

	/**
	 * Init function
	 *
	 * @return void
	 */
	public static function _init()
	{
		\Config::load('gravatar', true);
	}

	/**
	 * Gravatar forge
	 *
	 * @param  string $email  Email address
	 * @param  array  $config Config array
	 * @return Gravatar       Instance
	 */
	public static function forge($email, array $config = array())
	{
		$config = \Arr::merge(\Config::get('gravatar', array()), $config);
		$instance = new static($config);
		$instance->set_email($email);
		return $instance;
	}

	/**
	* Get a driver config setting.
	*
	* @param string $key the config key
	* @param mixed  $default the default value
	* @return mixed the config setting value
	*/
	public function get_config($key, $default = null)
	{
		return \Arr::get($this->config, $key, $default);
	}

	/**
	* Set a driver config setting.
	*
	* @param string $key the config key
	* @param mixed $value the new config value
	* @return object $this for chaining
	*/
	public function set_config($key, $value)
	{
		\Arr::set($this->config, $key, $value);

		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string Email address
	 */
	public function get_email()
	{
		return $this->email;
	}

	/**
	 * Set email and hash
	 *
	 * @param  string $email Email address
	 * @return $this
	 */
	public function set_email($email)
	{
		$email = strtolower(trim($email));
		$this->email = $email;
		$this->hash = md5($email);
		return $this;
	}

	public function avatar($img = true)
	{
		$config = array_filter($this->get_config('avatar', array()));
		$keys = array_map(function($value) { return substr($value, 0, 1); }, array_keys($config));
		$config = array_combine($keys, $config);
		\Arr::get($config, 'f', false) === true and \Arr::set($config, 'f', 'y');
		$url = $this->url($config, 'www.gravatar.com/avatar/');

		if ($img === true)
		{
			return \Html::img($url);
		}

		return $url;
	}

	public function url(array $query = array(), $url = 'www.gravatar.com')
	{
		$protocol = $this->get_config('protocol', strtolower(\Input::protocol()));
		in_array($protocol, array('http', 'https')) or $protocol = strtolower(\Input::protocol());
		$url = trim($url, '/');
		$url = $protocol . '://' . $url . '/' . $this->hash;

		return http_build_url($url . '?' . http_build_query($query));
	}

	public function img(array $attributes = array())
	{
		$default_attributes = array(
			'width'  => $this->config['avatar']['size'],
			'height' => $this->config['avatar']['size'],
			'alt'    => 'Gravatar',
		);
		$attributes = \Arr::merge($default_attributes, $attributes);
		return \Html::img($this->url(), $attributes);
	}

}