<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Przygotowanie cache całej aplikacji
	 */
	public function _initCache() 
	{
		$frontendOptions = array(
			// cache for 24 houers
		   'lifetime' => 86400,
		   'debug_header' => false, // for debugging
			// Disable caching by default for all URLs
			'default_options' => array(
				'cache' => false
			),
			'memorize_headers' => array(
				'Content-type',
				'Content-encoding'
			),
		    'regexps' => array(
		       // cache whole site..
		       '^/' => array(
					'cache' => true,
					'make_id_with_get_variables' => true,
					'cache_with_get_variables' => true,
					'make_id_with_cookie_variables' => true,
					'cache_with_cookie_variables' => true,
				)
		   )
		);
	
		$backendOptions = array(
		    'cache_dir' => dirname(APPLICATION_PATH) . '/temp'
		);

		// getting a Zend_Cache_Frontend_Page object
		require_once 'Zend/Cache.php';
		/* @var $cache Zend_Cache_Frontend_Page */
		$cache = Zend_Cache::factory('Page',
		                             'File',
		                             $frontendOptions,
		                             $backendOptions);
		
		$cache->start();
		
		// drobna sztuczka z czyszczeniem cache site
		if (array_key_exists('__cache', $_GET))
		{
			switch ($_GET['__cache'])
			{
				case 'all': $cache->clean(Zend_Cache::CLEANING_MODE_ALL); break;
				case 'cancel': $cache->cancel(); break;
			}
		}
	}
}