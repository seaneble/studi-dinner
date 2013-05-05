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
		
		$register_form = new \application\forms\Register();
		$login_form = new \application\forms\Login();
		
		if($request->isPost() && $register_form->isValid($data = $request->getPost()))
		{
			$person = new \application\models\Person();
			$person->setFirstName($data['firstname'])
			       ->setLastName($data['lastname'])
			       ->setAddressStreet($data['address_street'])
			       ->setAddressNumber($data['address_number'])
			       ->setAddressZip($data['address_zip'])
			       ->setAddressCity($data['address_city'])
			       ->setAddressDetails($data['address_details'])
			       ->setPhone($data['phone'])
			       ->setEmail($data['email'])
			       ->setPassword($data['password'])
				   ->setActive(false);
//			       ->set($data[''])
			
			$this->em->persist($person);
			$this->em->flush();
		}
		
		$this->view->register_form = $register_form;
		$this->view->login_form = $login_form;

	}
}