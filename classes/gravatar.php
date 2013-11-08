<?php
/**
 * Fuel Gravatar
 *
 * @package 	Fuel
 * @subpackage	Gravatar
 * @version		0.3
 * @author 		Tamás Barta <barta.tamas.d@gmail.com>
 * @license 	MIT License
 * @link 		https://github.com/indigo-soft
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
	 * @param	string		$email	Email address
	 * @param	array 		$config	Config array
	 * @return	Gravatar 			Instance
	 */
	public static function forge($email, array $config = array())
	{
		$config = \Arr::merge(\Config::get('gravatar', array()), $config);
		$instance = new static($config);
		$instance->set_email($email);
		return $instance;
	}

	/**
	* Get a driver config setting
	*
	* @param	string|null		$key		Config key
	* @param	mixed			$default	Default value
	* @return	mixed						Config setting value or the whole config array
	*/
	public function get_config($key = null, $default = null)
	{
		return is_null($key) ? $this->config : \Arr::get($this->config, $key, $default);
	}

	/**
	* Set a driver config setting
	*
	* @param	string|array	$key		Config key or array of key-value pairs
	* @param	mixed			$value		New config value
	* @return	$this						$this for chaining
	*/
	public function set_config($key, $value = null)
	{
		// Merge config or just set an element
		if (is_array($key))
		{
			// Set default values and merge config reverse order
			if ($value === true)
			{
				$this->config = \Arr::merge($key, $this->config);
			}
			else
			{
				$this->config = \Arr::merge($this->config, $key);
			}
		}
		else
		{
			\Arr::set($this->config, $key, $value);
		}

		return $this;
	}

	/**
	 * Get email
	 *
	 * @return	string	Email address
	 */
	public function get_email()
	{
		return $this->email;
	}

	/**
	 * Set email and hash
	 *
	 * @param 	string	$email	Email address
	 * @return	$this			$this for chaining
	 */
	public function set_email($email)
	{
		$email = strtolower(trim($email));
		$this->email = $email;
		$this->hash = md5($email);
		return $this;
	}

	/**
	 * Create URL
	 *
	 * @param	array	$query	Array of query elements
	 * @param	string	$url	URL
	 * @return	string			URL of resource
	 */
	protected function url($url, array $query = array())
	{
		$protocol = $this->get_config('protocol', false);

		if (is_bool($protocol))
		{
			$protocol = $protocol ? 'https' : 'http';
		}
		else
		{
			$protocol = strtolower(\Input::protocol());
		}

		$url = $protocol . '://' . $url;

		return \Uri::create($url, array(), $query);
	}

	/**
	 * Create avatar
	 *
	 * @param	boolean	$img	Return HTML img
	 * @param	array	$attr	HTML img attributes
	 * @return	string			URL of image or HTML img tag
	 */
	public function avatar($img = false, array $attr = array())
	{
		$config = array(
			's' => $this->get_config('size'),
			'd' => $this->get_config('default'),
			'f' => $this->get_config('force', false) !== true ? null : 'y',
			'r' => strtolower($this->get_config('rating'))
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
	 * Get profile from Gravatar
	 *
	 * @return array		Profile
	 */
	public function profile()
	{
		$format = $this->get_config('format', 'xml') ?: 'xml';
		$config = array();

		$format == 'json' and \Arr::set($config, 'c', $this->get_config('callback'));

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
				if ($this->get_config('auto', true) === true)
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
	 * Create QR-code
	 *
	 * @param	boolean	$img	Return HTML img
	 * @param	array	$attr	HTML img attributes
	 * @return	string			URL of image or HTML img tag
	 */
	public function qr($img = false, array $attr = array())
	{
		$config = array_filter(array('s' => $this->get_config('size')));
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
	 * Create VCF link
	 *
	 * @param	boolean	$a			Return HTML anchor
	 * @param	string	$title		HTML anchor title
	 * @param	array	$attr		HTML anchor attributes
	 * @return	string				URL of VCF or HTML anchor tag
	 */
	public function vcf($a = false, $title = '', array $attr = array())
	{
		$url = $this->url('www.gravatar.com/' . $this->hash . '.vcf');

		if ($a === true)
		{
			return \Html::anchor($url, $title, $attr);
		}

		return $url;
	}
}
