<?php
class SearchController extends Zend_Controller_Action
{
	public function init()
	{
		/* @var $json KontorX_Controller_Action_Helper_Json */
		$json = $this->_helper->getHelper('Json');
		$json->setCallback($this->_getParam('callback'));

		$this->_helper->viewRenderer->setNoRender(true);
	}

    public function indexAction()
    {
    }

    /**
     * 
     */
    public function nearAction()
    {
    	$address  = $this->_getParam('address');
    	$distance = $this->_getParam('distance');

    	$search = new Application_Model_SearchProxy();
    	
    	
    	$data = array(
    		'success' =>'OK',
    		'results' => $search->near($address, $distance)
    	);
    	
    	$this->_helper->json($data);
    }
}