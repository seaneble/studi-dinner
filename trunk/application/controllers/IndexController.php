<?php

class IndexController extends Zend_Controller_Action
{
	
	protected $messenger;
	
	public function init()
	{
		$registry = \Zend_Registry::getInstance();
		$this->em = $registry->entitymanager;
		
		$this->messenger = $this->_helper->getHelper('FlashMessenger');
		$this->view->messages = $this->messenger->getMessages();
	}
	
	public function indexAction()
	{
		// Nächste Veranstaltungen anzeigen
		$result = $this->em->getRepository('\application\models\Event')->findAll();
		$this->view->events = $result;
		
		// Formular persönliche Daten anzeigen
		
	}
	
	public function loginAction()
	{		
		$request = $this->getRequest();
		$registry = \Zend_Registry::getInstance();
		
		$register_form = new \application\forms\Register();
		$login_form = new \application\forms\Login();
		
		$this->view->register_form = $register_form;
		$this->view->login_form = $login_form;
		
		if($request->isPost())
		{
			$data = $request->getPost();
			
			// Anmeldeformular auswerten
			if($data['form_name'] == 'login' && $login_form->isValid($data = $request->getPost()))
			{
				$auth = \Zend_Auth::getInstance();
				
				$adapter = new \StuV\Auth\Adapter(
                	$this->em->getRepository('\application\models\Person'),
                	$data['username'],
                	$data['login_password']
                );
                
				// Daten mit Adapter prüfen
				$result = $auth->authenticate($adapter);
				
				// Fehlermeldung ausgeben
				if (!$result->isValid())
		        {
		        	$login_form->getElement('username')->addError('Die Zugangsdaten stimmen nicht.');
		        	return false;
		        }
	            
				// Session anlegen
				else
				{
				    $user = array('username' => $adapter->getUser()->getEmail());
	                $user['timestamp'] = time();
	                $auth->getStorage()->write($user);
	                $this->_helper->redirector('index', 'index');
		        }
			}
			elseif($data['form_name'] == 'register' && $register_form->isValid($data = $request->getPost()))
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
				       ->setPassword(\StuV\Auth\Adapter::encryptPassword($data['password']))
				       ->setActive(false)
				       ->setToken($token = \StuV\Auth\Adapter::encryptPassword($data['email']));
	//			       ->set($data[''])
				
				$this->em->persist($person);
				$this->em->flush();
				
				// E-Mail mit Bestätigungslink verschicken
				$config = array(
					'auth' => 'login',
					'username' => $registry->config->smtp->username,
					'password' => $registry->config->smtp->password,
				);
				 
				$transport = new Zend_Mail_Transport_Smtp($registry->config->smtp->server, $config);
				
				$mail = new \Zend_Mail();
				$mail->setBodyText(
"Hallo\n
\n
Vielen Dank für deine Registrierung. Um den Prozess abzuschließen, besuch' bitte http://dinner.local.sebastianleitz.de/index/token?token=' . $token. Danach kannst du dich anmelden.\n
\n
Wir freuen uns auf deine Teilnahme an einer unserer Veranstaltungen!\n
\n
Das Team vom Studi-Dinner.");
				$mail->setFrom('dinner@stuv-stuttgart.de', 'Dinner-Team');
				$mail->addTo($person->getEmail(), $person->getFirstName() . ' ' . $person->getLastName());
				$mail->setSubject('Deine Anmeldung - E-Mail-Adresse verifizieren');
				$mail->send($transport);
				
				$this->_helper->redirector('token', 'index');
			}
		}
	}
	
	public function tokenAction()
	{
		$request = $this->getRequest();
			
		if($token = $request->getParam('token'))
		{
			$this->view->token_value = true;
			
			$person = $this->em->getRepository('\application\models\Person')->findOneBy(array('token' => $token));
			if($person === null)
			{
				$this->view->token_exists = false;
				return;
			}
			$this->view->token_exists = true;
			
			$person->setToken(null)
			       ->setActive(true);
			
			$this->em->persist($person);
			$this->em->flush();
			
			$this->_helper->redirector('index', 'index');
		}
		else
		{
			$this->view->token_value = false;
		}
	}
	
	/**
     * Benutzer abmelden und auf Login-Seite weiterleiten
     */
    public function logoutAction()
    {
        \Zend_Auth::getInstance()->clearIdentity();
        
        $this->_helper->FlashMessenger('Du wurdest erfolgreich abgemeldet.');
        $this->_helper->redirector('index');
    }

}
