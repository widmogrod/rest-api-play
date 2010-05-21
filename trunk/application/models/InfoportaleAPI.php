<?php
/**
 * @author $Author$
 * @version $Id$
 */
class Application_Model_InfoportaleAPI
{
	const LEKARZE 		= 'http://lekarze.krakow.pl/api/';
	const WETERYNARZE 	= 'http://weterynarze.krakow.pl/api/';
	const STOMATOLODZY 	= 'http://stomatolodzy.krakow.pl/api/';
	const PRAWNICY 		= 'http://prawnicy.krakow.pl/api/';
	const FRYZJERZY 	= 'http://fryzjerzy.krakow.pl/api/';

	/**
	 * @var array
	 */
	protected static $_infoportale = array(
		self::LEKARZE,
		self::WETERYNARZE,
		self::STOMATOLODZY,
		self::PRAWNICY,
		self::FRYZJERZY
	);
	
	/**
	 * @var array
	 */
	protected static $_instance = array();
	
	protected $_url;
	
	/**
	 * @param string $type
	 * @return InfoportaleAPI
	 */
	public static function getInstance($type)
	{
		if (!in_array($type, self::$_infoportale))
		{
			return;
		}

		if (!isset(self::$_instance[$type]))
		{
			self::$_instance[$type] = new self($type);
		}
		
		return self::$_instance[$type];
	}
	
	/**
	 * @param string $url
	 */
	protected function __construct($url)
	{
		$this->_url = $url;
	}

	/**
	 * @var Zend_Http_Client
	 */
	protected $_client;
	
	/**
	 * @param bool $reset
	 * @return Zend_Http_Client
	 */
	public function getHttpClinet($reset = false)
	{
		if (null === $this->_client)
		{
			$this->_client = new Zend_Http_Client($this->_url);
		}
		
		if (true === $reset)
		{
			$this->_client->resetParameters(true);
		}

		return $this->_client;
	}

	/**
	 * @var string
	 */
	protected $_action;
	
	/**
	 * @return null|mixed
	 */
	protected function _requestJson2Array()
	{
		$http = $this->getHttpClinet();

		// prezyowanie URL do akcji
		$http->setUri($this->_url . trim($this->_action,'/'));
		
		try {
			/* @var $response Zend_Http_Response */
			$response = $http->request(Zend_Http_Client::GET);
		} catch (Zend_Http_Client_Exception $e) {
			return null;
		}
		
		if ($response->getStatus() != 200)
		{
			return null; 
		}

		$json = $response->getBody();
		return Zend_Json::decode($json);
	}
	
	/**
	 * URI: /api/search/near?lat=&lng=&distance=
	 * 
	 * @param float $lat
	 * @param float $lng
	 * @param float $distance
	 * @return array
	 */
	public function near($lat, $lng, $distance)
	{
		$this->_action = 'search/near';

		$http = $this->getHttpClinet(true);
		$http->setParameterGet('lat', $lat);
		$http->setParameterGet('lng', $lng);
		$http->setParameterGet('distance', $distance);

		$array = (array) $this->_requestJson2Array();
		return $array;
	}
}