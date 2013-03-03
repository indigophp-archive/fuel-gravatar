<?php

/**
 * 
 */
class Gravatar
{
	/**
	 * Holds the email set for this instance
	 * @var string
	 */
	protected $email = null;

	protected $config = array(
		'size'          => null,
		'default_image' => null,
		'protocol'      => null,
		'rating'        => null,
	);

	function __construct(array $config = array()) {
		$this->config = \Arr::merge(\Arr::merge($this->config, \Config::load('gravatar')), $config);
	}

	public static function forge($email, array $config = array())
	{
		$instance = new static($config);
		$instance->set_email($email);
		return $instance;
	}

	public function set_email($email)
	{
		$this->email = $email;
		return $this;
	}

	public function get_email()
	{
		return $this->email;
	}

	public function get_config($key = null)
	{
		return \Arr::get($this->config, $key, null);
	}

	public function set_config($key, $value)
	{
		\Arr::set($this->config, $key, $value);
		return $this;
	}

	public function url()
	{
		$protocol = $this->config['protocol'];
		$config = array(
			's' => $this->config['size'],
			'd' => $this->config['default_image'],
			'r' => strtolower($this->config['rating']),
		);
		return $protocol . '://www.gravatar.com/avatar/' . md5( $this->email ) . '?' . http_build_query($config);
	}

	public function img($attributes)
	{
		$default_attributes = array(
			'width' => $this->config['size'],
			'height' => $this->config['size'],
		);
		$attributes = \Arr::merge($default_attributes, $attributes);
		return \Html::img($this->url(), $attributes);
	}

}