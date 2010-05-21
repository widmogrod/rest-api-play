<?php
class SearchController extends Zend_Controller_Action
{
	public $contexts = array(
		'near' => array('json')
	);

	public function init()
	{
		$this->_helper->contextSwitch->initContext('json');
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
    	$this->view->success = 'OK';
    	$this->view->results = $search->near($address, $distance);
    }
}