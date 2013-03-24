<?php

class IndexController extends Zend_Controller_Action
{
	public function init()
	{
		$registry = \Zend_Registry::getInstance();
		$this->em = $registry->entitymanager;
	}
	
	public function indexAction()
	{
		$result = $this->em->getRepository('\application\models\Event')->findAll();
		$this->view->events = $result;
		
		$request = $this->getRequest();
		
		$form = new \application\forms\Register;
		
		if($request->isPost() && $form->isValid($request->getPost()))
		{
			$person = new \application\model\Person($form->getValues());
			$this->em->persist($person);
		}
		
		$this->view->form = $form;
	}
}
