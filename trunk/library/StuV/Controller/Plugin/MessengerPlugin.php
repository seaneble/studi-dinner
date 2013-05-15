<?php

namespace StuV\Controller\Plugin;

class MessengerPlugin extends \Zend_Controller_Plugin_Abstract
{
	public function postDispatch()
	{
		$view = \Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;
		
		$messenger = \Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
		$view->messages = array_merge(
			$messenger->getMessages(),
			$messenger->getCurrentMessages()
		);
		$messenger->clearCurrentMessages();
	}
}