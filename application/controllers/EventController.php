<?php

class EventController extends Zend_Controller_Action
{
	
	protected $em;
	protected $currentUser;
	protected $event;
	
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
		$this->view->no_partner = true;
		$this->view->confirmation_partner = false;
		$this->view->random_partner = false;
		
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
		
		// Wenn keine Teams gefunden wurden, kann der Benutzer sich noch frei entscheiden.
		
		// Wenn der Benutzer als Gastgeber eingetragen wurde…
		if($host !== null)
		{
			$team = $host;
			
			// …und eine zufällige Auslosung vereinbart wurde, biete Rücktritt von der Option an.
			if($team->getRandomPartner())
			{
				$this->view->no_partner = false;
				$this->view->random_partner = true;
				return;
			}
			
			// Die Anfrage wurde noch nicht angenommen.
			if(!$team->getConfirmed())
			{
				$this->view->form = '<p><strong>Dein Wunschpartner hat deine Anfrage noch nicht angenommen.</strong></p>';
				$this->view->no_partner = false;
				return;
			}
			
			// Gib das Team-Formular aus.
			$form = new \application\forms\PartnerConfig();
			
			$this->view->form = $form;
			$this->view->no_partner = false;
			$this->view->confirm_partner = true;
		}
		elseif($partner !== null)
		{
			$team = $partner;
			
			// Die Anfrage wurde noch nicht angenommen.
			if(!$team->getConfirmed())
			{
				$this->view->form = "
<p>" . $team->getHostPerson()->getFirstname() . " " . $team->getHostPerson()->getLastname() . " möchte mit dir an dieser Veranstaltung teilnehmen.</p>
<p>Bitte wähle eine der folgenden Optionen:</p>
<ul>
<li><a href=\"" . $this->view->getHelper('Url')->url(array('controller'=>'event','action'=>'index','id' => $this->event->getId(), 'partner' => 'yes'), null, FALSE) . "\">Ja, da mache ich mit.</a></li>
<li><a href=\"" . $this->view->getHelper('Url')->url(array('controller'=>'event','action'=>'index','id' => $this->event->getId(), 'partner' => 'no'), null, FALSE) . "\">Nein danke.</a></li>
</ul>";
				$this->view->no_partner = false;
				
				// Werte aus, ob der Benutzer gerade eine Aktion ausführt.
				$partnerParam = $this->getRequest()->getParam('partner');
				$partner_person = $this->em->getRepository('\application\models\Person')->findOneById($team->getHostPerson());
				
				// Der Benutzer möchte das Team bestätigen
				if($partnerParam === 'yes')
				{
					$team->setConfirmed(true);
					$this->em->persist($team);
					$this->em->flush();
					
					// Bestätigung an anfragende Person schicken
					$this->sendMail(
						$partner_person,
						$this->event->getName() . ': Team-Anfrage angenommen',
"Hallo,

" . $this->currentUser->getFirstname() . " " . $this->currentUser->getLastname() . " hat deine Teamanfrage für die Veranstaltung " . $this->event->getName() . " angenommen. Nun geht es auf der Seite http://dinner.local.sebastianleitz.de/event/index/id/" . $this->event->getId() . " weiter. Viel Spaß zusammen!

Das Team vom Studi-Dinner."
					);
					
					$this->_helper->FlashMessenger('Herzlichen Glückwunsch, ihr seid nun ein Team!');
					$this->_helper->redirector('index', 'event', null, array('id' => $this->event->getId()));
				}
				// Der Benutzer möchte das Team ablehnen
				elseif($partnerParam === 'no')
				{
					$this->em->remove($team);
					$this->em->flush();
					
					$this->sendMail(
						$partner_person,
						$this->event->getName() . ': Team-Anfrage abgelehnt',
"Hallo,

" . $this->currentUser->getFirstname() . " " . $this->currentUser->getLastname() . " hat deine Teamanfrage für die Veranstaltung " . $this->event->getName() . " abgelehnt. Das tut uns leid, aber du kannst einen anderen Partner suchen, oder dich von uns vermitteln lassen! Geh dazu auf die Seite http://dinner.local.sebastianleitz.de/event/index/id/" . $this->event->getId() . ".

Das Team vom Studi-Dinner."
					);
					
					$this->_helper->FlashMessenger('Die Anfrage wurde abgelehnt.');
					$this->_helper->redirector('index', 'event', null, array('id' => $this->event->getId()));
				}
				return;
			}
			
			// Gib das Team-Formular aus.
			$form = new \application\forms\PartnerConfig();
			
			$this->view->form = $form;
			$this->view->no_partner = false;
			$this->view->confirm_partner = true;
		}
		
		// Jetzt hat der Benutzer die volle Wahl
		else
		{
			$form = new \application\forms\PartnerSearch();
			$this->view->form = $form;
			
			if($this->getRequest()->isPost() && $form->isValid($data = $this->getRequest()->getPost()))
			{
				if($this->event->getId() === null)
				{
					$form->getElement('name')->addError('Die Veranstaltung existiert nicht.');
					return;
				}
				
				$name = explode(' ', $data['name']);
				if(count($name) !== 2)
				{
					$form->getElement('name')->addError('Vorname und Nachname angeben!');
					return;
				}
				
				$partner_person = $this->em->getRepository('\application\models\Person')->findOneBy(array('firstname' => $name[0], 'lastname' => $name[1]));
				if($partner_person === null)
				{
					$form->getElement('name')->addError('Diese Person konnte nicht gefunden werden.');
					return;
				}
				if($partner_person->getId() == $this->currentUser->getId())
				{
					$form->getElement('name')->addError('Mit dir selbst kannst du kein Team bilden.');
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

" . $this->currentUser->getFirstname() . " " . $this->currentUser->getLastname() . " möchte mit dir am " . $this->event->getName() . " teilnehmen. Auf der folgenden Seite kannst du die Anfrage annehmen: http://dinner.local.sebastianleitz.de/event/index/id/" . $this->event->getId() . ".

Das Team vom Studi-Dinner."
				);
				
				$this->_helper->FlashMessenger('An deinen Wunschpartner wurde eine E-Mail verschickt.');
				$this->_helper->redirector('index', 'event', null, array('id' => $this->event->getId()));
			}
			
			return;
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
