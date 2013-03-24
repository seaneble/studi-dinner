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
		
		if($request->isPost() && $form->isValid($data = $request->getPost()))
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
			       ->setPassword($data['password']);
//			       ->set($data[''])
			
			$this->em->persist($person);
			$this->em->flush();
		}
		
		$this->view->form = $form;
	}
}
