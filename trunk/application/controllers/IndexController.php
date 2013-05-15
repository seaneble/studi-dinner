<?php

class IndexController extends Zend_Controller_Action
{
	
	protected $messenger;
	
	public function init()
	{
		$registry = \Zend_Registry::getInstance();
		$this->em = $registry->entitymanager;
	}
	
	public function indexAction()
	{
		// Nächste Veranstaltungen anzeigen
		$q = $this->em->createQuery("select e from \application\models\Event e where e.date >= :today")
    ->setParameter('today', new \DateTime());
		$this->view->events = $q->getResult();
		
		// Formular persönliche Daten anzeigen
		
	}
	
	public function loginAction()
	{		
		$request = $this->getRequest();
		$registry = \Zend_Registry::getInstance();
		
		$register_form = new \application\forms\Register();
		$login_form = new \application\forms\Login();
		
		foreach($register_form->getElements() as $element)
			$element->removeDecorator('Errors');
		
		$this->view->register_form = $register_form;
		$this->view->login_form = $login_form;
		
		if($request->isPost())
		{
			$data = $request->getPost();
			
			// Anmeldeformular auswerten
			if($data['form_name'] == 'login')
			{
				if($login_form->isValid($data = $request->getPost()))
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
			        	$this->_helper->FlashMessenger('Im Anmeldeformular trat ein Fehler auf.');
			        	return false;
			        }
		            
					// Session anlegen
					else
					{
					    $user = array('object' => $adapter->getUser());
		                $user['timestamp'] = time();
		                $auth->getStorage()->write($user);
		                $this->_helper->redirector('index', 'index');
			        }
			    }
			    else
			    {
				    $this->_helper->FlashMessenger('Im Anmeldeformular trat ein Fehler auf.');
				    return;
			    }
			}
			elseif($data['form_name'] == 'register')
			{
				if($register_form->isValid($data = $request->getPost()))
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
					       ->setToken($token = \StuV\Auth\Adapter::encryptPassword($data['email'] . rand() . rand()));
		//			       ->set($data[''])
					
					try
					{
						$this->em->persist($person);
						$this->em->flush();
					}
					catch (Exception $e)
					{
						$register_form->getElement('email')->addError('Diese E-Mail-Adresse ist bereits in Verwendung.');
							
					    $this->_helper->FlashMessenger('Im Registrierformular traten Fehler auf.');
					    
					    foreach($register_form->getMessages() as $field => $message)
					    {
					    	$this->_helper->FlashMessenger('Das Feld <strong>' . $register_form->getElement($field)->getLabel() . '</strong> meldet: ' . implode(', ', $message));
					    	
					    	$register_form->getElement($field)->getDecorator('Label')->setTagClass('error');
						}
						return;
					}
					
					// E-Mail mit Bestätigungslink verschicken
					$config = array(
						'auth' => 'login',
						'username' => $registry->config->smtp->username,
						'password' => $registry->config->smtp->password,
					);
					 
					$transport = new Zend_Mail_Transport_Smtp($registry->config->smtp->server, $config);
					
					$mail = new \Zend_Mail();
					$mail->setBodyText(
	"Hallo,
	
	Vielen Dank für deine Registrierung. Um den Prozess abzuschließen, besuch' bitte http://dinner.local.sebastianleitz.de/index/token?token=" . $token . ". Danach kannst du dich anmelden.
	
	Wir freuen uns auf deine Teilnahme an einer unserer Veranstaltungen!
	
	Das Team vom Studi-Dinner.");
					$mail->setFrom('dinner@stuv-stuttgart.de', 'Dinner-Team');
					$mail->addTo($person->getEmail(), $person->getFirstName() . ' ' . $person->getLastName());
					$mail->setSubject('Deine Anmeldung - E-Mail-Adresse verifizieren');
					$mail->send($transport);
					
					$this->_helper->redirector('token', 'index');
				}
				else
				{
				    $this->_helper->FlashMessenger('Im Registrierformular traten Fehler auf.');
				    
				    foreach($register_form->getMessages() as $field => $message)
				    {
				    	$this->_helper->FlashMessenger('Das Feld <strong>' . $register_form->getElement($field)->getLabel() . '</strong> meldet: ' . implode(', ', $message));
				    	
				    	$register_form->getElement($field)->getDecorator('Label')->setTagClass('error');
					}
				    
					return;
				}
			}
		}
	}
	
	public function tokenAction()
	{
		$request = $this->getRequest();
			
		if($token = $request->getParam('token'))
		{
			$person = $this->em->getRepository('\application\models\Person')->findOneBy(array('token' => $token));
			if($person === null)
			{
				$this->_helper->FlashMessenger('Der Anmeldelink ist wurde schon bestätigt. Du kannst dich unten anmelden.');
				$this->_helper->redirector('index', 'index');
			}
			
			$person->setToken(null)
			       ->setActive(true);
			
			$this->em->persist($person);
			$this->em->flush();
			
			$this->_helper->FlashMessenger('Vielen Dank für die Bestätigung. Du kannst dich jetzt anmelden.');
			$this->_helper->redirector('index', 'index');
		}
		else
		{
			$this->_helper->FlashMessenger('Du hast eine E-Mail mit einem Bestätigungslink erhalten.');
			$this->_helper->redirector('index', 'index');
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
