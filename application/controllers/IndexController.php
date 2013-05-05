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
			       ->setPassword(hash('sha256', $data['password']))
			       ->setActive(false)
			       ->setToken($token = hash('sha256', $data['email'] . 'sepp'));
//			       ->set($data[''])
			
			$this->em->persist($person);
			$this->em->flush();
			
			// E-Mail mit Bestätigungslink verschicken
			$config = array('auth' => 'login',
					'username' => 'm027acc5',
					'password' => 'PUustq4r7pDT2tvc');
			 
			$transport = new Zend_Mail_Transport_Smtp('smtp.stuv-stuttgart.de', $config);
			
			$mail = new \Zend_Mail();
			$mail->setBodyText('http://dinner.local.sebastianleitz.de/token?token=' . $token);
			$mail->setFrom('dinner@stuv-stuttgart.de', 'Dinner-Team');
			$mail->addTo($person->getEmail(), $person->getFirstName() . ' ' . $person->getLastName());
			$mail->setSubject('Deine Anmeldung - E-Mail-Adresse verifizieren');
			$mail->send($transport);
			
			$this->_helper->redirector->_redirect(array('controller' => 'index', 'action' => 'token_explanation'));
			
		}
		
		$this->view->register_form = $register_form;
		$this->view->login_form = $login_form;

	}
}
