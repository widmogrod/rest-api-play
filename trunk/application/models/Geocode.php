<?php
class Application_Model_Geocode
{
	/**
	 * Adres do usługi Google Geocode
	 * @var string
	 */
	CONST GEOCODE_URL = 'http://maps.google.com/maps/api/geocode/json';
	
	/**
	 * Adres jakiego lokalizacja jest szukana
	 * @var string
	 */
	protected $_address = null;
	
	/**
	 * Zbiór informacji o adresach, które były szukane
	 * @var array
	 */
	protected $_addresses = array();

	/**
	 * @param string $address
	 */
	public function __construct($address)
	{
		$this->setAddress($address);
	}

	/**
	 * @var Zend_Http_Client
	 */
	protected $_client;
	
	/**
	 * @return Zend_Http_Client 
	 */
	public function getHttpClinet()
	{
		if (null === $this->_client)
		{
			$this->_client = new Zend_Http_Client(self::GEOCODE_URL);
		}
		return $this->_client;
	}
	
	/**
	 * @return void
	 */
	protected function _request()
	{
		if (!isset($this->_addresses[$this->_address]))
		{
			$http = $this->getHttpClinet();
			$http->resetParameters(true);
			$http->setParameterGet('address', $this->_address);
			$http->setParameterGet('sensor', 'false');
			
			try {
				/* @var $response Zend_Http_Response */
				$response = $http->request(Zend_Http_Client::GET);
			} catch (Zend_Http_Client_Exception $e) {
				return;
			}
			
			if ($response->getStatus() != 200)
			{
				return;
			}
			
			$json = $response->getBody();
			$json = Zend_Json::decode($json);
			$this->_addresses[$this->_address] = $json;
		}
		
		return $this->_addresses[$this->_address];
	}

	/**
	 * Położenie geograficzne adresu
	 * @return array
	 */
	public function getLatLng()
	{
		if (null === ($data = $this->_request()))
		{
			return;
		}

		$latlng = $data['results'][0]['geometry']['location'];
		// dodanie pól przydatnych przy wywoływaniu funkcji @see list();
		$latlng[0] = $latlng['lat'];
		$latlng[1] = $latlng['lng'];

		return $latlng;
	}

	/**
	 * @param string $address
	 */
	public function setAddress($address)
	{
		$this->_address = $address;
	}

	/**
	 * @return string
	 */
	public function getAddress()
	{
		return $this->_address;
	}
}