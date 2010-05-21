<?php
class Application_Model_SearchProxy
{
	CONST GEOCODE_URL = 'http://maps.google.com/maps/api/geocode/json';
	
	/**
	 * @param string $address
	 * @return array
	 */
	public function near($address, $distance = null)
	{
		$return = array(
			'lekarze.krakow.pl' => array(),
			'prawnicy.krakow.pl' => array(),
			'stomatolodzy.krakow.pl' => array(),
			'fryzjerzy.krakow.pl' => array(),
			'weterynarze.krakow.pl' => array()
		);

		$geocode = new Application_Model_Geocode($address);
		if(null === ($latlng = $geocode->getLatLng()))
		{
			return $return;
		}
		
		list($lat, $lng) = $latlng;

		$return = array(
			'lekarze.krakow.pl' 	 => Application_Model_InfoportaleAPI::getInstance(Application_Model_InfoportaleAPI::LEKARZE)->near($lat, $lng, $distance),
			'prawnicy.krakow.pl' 	 => Application_Model_InfoportaleAPI::getInstance(Application_Model_InfoportaleAPI::PRAWNICY)->near($lat, $lng, $distance),
			'stomatolodzy.krakow.pl' => Application_Model_InfoportaleAPI::getInstance(Application_Model_InfoportaleAPI::STOMATOLODZY)->near($lat, $lng, $distance),
			'fryzjerzy.krakow.pl' 	 => Application_Model_InfoportaleAPI::getInstance(Application_Model_InfoportaleAPI::FRYZJERZY)->near($lat, $lng, $distance),
			'weterynarze.krakow.pl'  => Application_Model_InfoportaleAPI::getInstance(Application_Model_InfoportaleAPI::WETERYNARZE)->near($lat, $lng, $distance),
		);
		
		return $return;
	}
}