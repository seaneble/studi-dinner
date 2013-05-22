<?php

class EventController extends Zend_Controller_Action
{
	
	protected $em;
	protected $currentUser;
	protected $event;
	protected $team;
	
	public function init()
	{
		$registry = \Zend_Registry::getInstance();
		$this->em = $registry->entitymanager;
		
		$user = \Zend_Auth::getInstance();
		$user = $user->getIdentity();
		$user = $user['object'];
		$this->currentUser = $this->em->getRepository('\application\models\Person')->findOneById($user->getId());
		
		$eventId = $this->getRequest()->getParam('id');
		$this->event = $this->em->getRepository('\application\models\Event')->findOneById($eventId);
	}
	
	public function indexAction()
	{
		// Nach vorhandenen Teams zum aktuellen Benutzer suchen
		$host = $this->em->getRepository('\application\models\Team')->findOneBy(
			array(
				'event' => $this->event->getId(),
				'host_person' => $this->currentUser->getId(),
			)
		);
		$partner = $this->em->getRepository('\application\models\Team')->findOneBy(
			array(
				'event' => $this->event->getId(),
				'partner_person' => $this->currentUser->getId(),
			)
		);
        
        if($host === null && $partner === null)
        {
	        // Noch nicht in einem Team
            $this->notInTeam();
            return;
        }
	    else
	    {
    	    if($host === null)
    	        $this->team = $partner;
            else
                $this->team = $host;
	    }
	    
	    if($this->team->getRandomPartner())
	    {
	        // Möchte Partnerbörse nutzen
            $this->randomPartner();
        }
	    else
	    {
	        // Möchte mit festem Partner kochen
            $this->chosenPartner();
        }
    }
    	
	public function partnersucheAction()
	{
		if($this->getRequest()->getParam('divorce'))
		{
			$team = $this->em->getRepository('\application\models\Team')->findOneBy(array('host_person' => $this->currentUser));
			$this->em->remove($team);
			$this->em->flush();
		}
		else
		{
			$team = new \application\models\Team();
			$team->setHostPerson($this->currentUser)
			     ->setRandomPartner(true)
			     ->setEvent($this->event);
			
			$this->em->persist($team);
			$this->em->flush();
		}
		
		$this->_helper->redirector('index', 'event', null, array('id' => $this->event->getId()));
	}
	
	protected function notInTeam()
	{
    	$this->_helper->viewRenderer->setRender('index_none');
    	
		$form = new \application\forms\PartnerSearch();			
		foreach($form->getElements() as $element)
			$element->removeDecorator('Errors');
		
		$this->view->form = $form;
        
		if($this->getRequest()->isPost())
		{
			if($form->isValid($data = $this->getRequest()->getPost()))
		 	{				
				$name = explode(' ', $data['name']);
				if(count($name) !== 2)
				{
					$form->getElement('name')->addError('Vorname und Nachname angeben!');
					
				    $this->_helper->FlashMessenger('Im Suchformular traten Fehler auf.');
				    
				    foreach($form->getMessages() as $field => $message)
				    {
				    	$this->_helper->FlashMessenger('Das Feld <strong>' . $form->getElement($field)->getLabel() . '</strong> meldet: ' . implode(', ', $message));
				    	
				    	$form->getElement($field)->getDecorator('Label')->setTagClass('error');
					}
				    
					return;
				}
				
				$partner_person = $this->em->getRepository('\application\models\Person')->findOneBy(array('firstname' => $name[0], 'lastname' => $name[1]));
				if($partner_person === null)
				{
					$form->getElement('name')->addError('Diese Person konnte nicht gefunden werden.');
					
				    $this->_helper->FlashMessenger('Im Suchformular traten Fehler auf.');
				    
				    foreach($form->getMessages() as $field => $message)
				    {
				    	$this->_helper->FlashMessenger('Das Feld <strong>' . $form->getElement($field)->getLabel() . '</strong> meldet: ' . implode(', ', $message));
				    	
				    	$form->getElement($field)->getDecorator('Label')->setTagClass('error');
					}
				    
					return;
				}
				if($partner_person->getId() == $this->currentUser->getId())
				{
					$form->getElement('name')->addError('Mit dir selbst kannst du kein Team bilden.');
					
				    $this->_helper->FlashMessenger('Im Suchformular traten Fehler auf.');
				    
				    foreach($form->getMessages() as $field => $message)
				    {
				    	$this->_helper->FlashMessenger('Das Feld <strong>' . $form->getElement($field)->getLabel() . '</strong> meldet: ' . implode(', ', $message));
				    	
				    	$form->getElement($field)->getDecorator('Label')->setTagClass('error');
					}
				    
					return;
				}
				
				$q = $this->em->createQuery("select t from \application\models\Team t where t.host_person = :person or t.partner_person = :person");
				$q->setParameter('person', $partner_person);
				if(count($q->getResult()))
				{
					$form->getElement('name')->addError('Die Person ist schon vergeben.');
					
				    $this->_helper->FlashMessenger('Im Suchformular traten Fehler auf.');
				    
				    foreach($form->getMessages() as $field => $message)
				    {
				    	$this->_helper->FlashMessenger('Das Feld <strong>' . $form->getElement($field)->getLabel() . '</strong> meldet: ' . implode(', ', $message));
				    	
				    	$form->getElement($field)->getDecorator('Label')->setTagClass('error');
					}
				    
					return;
				}
				
				$team = new \application\models\Team();
				$team->setHostPerson($this->currentUser)
				     ->setPartnerPerson($partner_person)
				     ->setEvent($this->event);
				
				$this->em->persist($team);
				$this->em->flush();
				
				$this->sendMail(
					$partner_person,
					$this->event->getName() . ': Ein Team bilden',
"Hallo,

" . $this->currentUser->getFirstname() . " " . $this->currentUser->getLastname() . " möchte mit dir am " . $this->event->getName() . " teilnehmen. Auf der folgenden Seite kannst du die Anfrage annehmen: " . \Zend_Registry::getInstance()->config->mail->baseUrl . "/event/index/id/" . $this->event->getId() . ".

Das Team vom Studi-Dinner."
				);
				
				$this->_helper->FlashMessenger('An deinen Wunschpartner wurde eine E-Mail verschickt.');
				$this->_helper->redirector('index', 'event', null, array('id' => $this->event->getId()));
			}
			else
			{
			    $this->_helper->FlashMessenger('Im Suchformular traten Fehler auf.');
			    
			    foreach($form->getMessages() as $field => $message)
			    {
			    	$this->_helper->FlashMessenger('Das Feld <strong>' . $form->getElement($field)->getLabel() . '</strong> meldet: ' . implode(', ', $message));
			    	
			    	$form->getElement($field)->getDecorator('Label')->setTagClass('error');
				}
			    
				return;
			}
		}
	}
	
	protected function chosenPartner()
	{
    	$this->_helper->viewRenderer->setRender('index_chosen');
    	$this->view->confirmed = false;
    	
	    // Wenn der Wunschpartner angenommen hat
	    if($this->team->getConfirmed())
	    {
            $this->outputTeamConfigForm();
        }
        // Team wurde noch nicht bestätigt
        else
        {
            // Meldung für anfordernden Benutzer anzeigen
            if($this->currentUser->getId() === $this->team->getHostPerson()->getId())
            {
                $this->view->owner = true;
                return;
            }
            else
            {
                $this->view->owner = false;
                $this->view->eventId = $this->event->getId();
                $this->getConfirmTeamDialog();
            }
        }
	}
	
	protected function randomPartner()
	{
    	$this->_helper->viewRenderer->setRender('index_random');
	}
	
	protected function outputTeamConfigForm()
	{
	    if($this->team->getHostPerson()->getId() === $this->currentUser->getId())
        {
	        $this->view->partner = $this->team->getPartnerPerson();
        }
        else
        {
	        $this->view->partner = $this->team->getHostPerson();
        }
        
        $form = new \application\forms\PartnerConfig();
        $form->getElement('addresses')->setMultiOptions(array(
            $this->team->getHostPerson()->getId() => $this->team->getHostPerson()->getFirstname() . ' ' . $this->team->getHostPerson()->getLastname(),
            $this->team->getPartnerPerson()->getId() => $this->team->getPartnerPerson()->getFirstname() . ' ' . $this->team->getPartnerPerson()->getLastname(),
        ))->setValue($this->team->getHostPerson()->getId());
        $form->getElement('name')->setValue($this->team->getName());
        
        $this->view->form = $form;
        $this->view->confirmed = true;
        
        if($this->getRequest()->isPost())
		{
			if($form->isValid($data = $this->getRequest()->getPost()))
			{
		 		if($data['addresses'] != $this->team->getHostPerson()->getId())
		 		{
		 			$newHost = $this->team->getPartnerPerson();
		 			$newPartner = $this->team->getHostPerson();
		 			$this->team->setHostPerson($newHost);
		 			$this->team->setPartnerPerson($newPartner);
                }
			    $this->team->setName($data['name']);
			    
			    try
			    {
		 			$this->em->persist($this->team);
		 			$this->em->flush();
	 			}
	 			catch (Exception $e)
	 			{
		 			$form->getElement('name')->addError('Der Name ist bereits vergeben');
		 			return;
	 			}
	 			
			    $this->_helper->FlashMessenger('Eure Team-Informationen wurden gespeichert.');
                $this->_helper->redirector('index', 'event', null, array('id' => $this->event->getId()));
            }
            
			$form->populate($data);
		    $this->_helper->FlashMessenger('Im Formular traten Fehler auf.');
		    
		    foreach($form->getMessages() as $field => $message)
		    {
		    	$this->_helper->FlashMessenger('Das Feld <strong>' . $form->getElement($field)->getLabel() . '</strong> meldet: ' . implode(', ', $message));
		    	
		    	$form->getElement($field)->getDecorator('Label')->setTagClass('error');
			}
        }
    }
	
	protected function getConfirmTeamDialog()
	{
        if($this->team->getHostPerson()->getId() === $this->currentUser->getId())
        {
	        $this->view->partner = $this->team->getPartnerPerson();
        }
        else
        {
	        $this->view->partner = $this->team->getHostPerson();
        }
        
        // Werte aus, ob der Benutzer gerade eine Aktion ausführt.
		$partnerParam = $this->getRequest()->getParam('partner');
		
		// Der Benutzer möchte das Team bestätigen
		if($partnerParam === 'yes')
		{
			$this->team->setConfirmed(true);
			$this->em->persist($this->team);
			$this->em->flush();
			
			// Bestätigung an anfragende Person schicken
			$this->sendMail(
				$this->team->getPartnerPerson(),
				$this->event->getName() . ': Team-Anfrage angenommen',
"Hallo,

" . $this->currentUser->getFirstname() . " " . $this->currentUser->getLastname() . " hat deine Teamanfrage für die Veranstaltung " . $this->event->getName() . " angenommen. Nun geht es auf der Seite " . \Zend_Registry::getInstance()->config->mail->baseUrl . "/event/index/id/" . $this->event->getId() . " weiter. Viel Spaß zusammen!

Das Team vom Studi-Dinner."
			);
			
			$this->_helper->FlashMessenger('Herzlichen Glückwunsch, ihr seid nun ein Team!');
			$this->_helper->redirector('index', 'event', null, array('id' => $this->event->getId()));
		}
		// Der Benutzer möchte das Team ablehnen
		elseif($partnerParam === 'no')
		{
			$this->em->remove($this->team);
			$this->em->flush();
			
			$this->sendMail(
				$this->team->getPartnerPerson(),
				$this->event->getName() . ': Team-Anfrage abgelehnt',
"Hallo,

" . $this->currentUser->getFirstname() . " " . $this->currentUser->getLastname() . " hat deine Teamanfrage für die Veranstaltung " . $this->event->getName() . " abgelehnt. Das tut uns leid, aber du kannst einen anderen Partner suchen, oder dich von uns vermitteln lassen! Geh dazu auf die Seite " . \Zend_Registry::getInstance()->config->mail->baseUrl . "/event/index/id/" . $this->event->getId() . ".

Das Team vom Studi-Dinner."
			);
			
			$this->_helper->FlashMessenger('Die Anfrage wurde abgelehnt.');
			$this->_helper->redirector('index', 'event', null, array('id' => $this->event->getId()));
		}
	}
	
	/**
	 * Eine E-Mail versenden
	 */
	protected function sendMail($user, $subject, $message)
	{
		$registry = \Zend_Registry::getInstance();
		$config = array(
			'auth' => 'login',
			'username' => $registry->config->smtp->username,
			'password' => $registry->config->smtp->password,
		);
		
		$transport = new Zend_Mail_Transport_Smtp($registry->config->smtp->server, $config);
		
		$mail = new \Zend_Mail();
		$mail->setBodyText($message);
		$mail->setFrom($registry->config->smtp->from, 'Dinner-Team');
		$mail->addTo($user->getEmail(), $user->getFirstName() . ' ' . $user->getLastName());
		$mail->setSubject($subject);
		$mail->send($transport);
	}
}
