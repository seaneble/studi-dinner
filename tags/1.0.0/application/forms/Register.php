<?php

namespace application\forms;

class Register extends \Zend_Form
{
	public function init()
	{
		$vorname = array(
			'Kerstin',
			'Michael',
			'Doris',
			'Steffen',
			'Marie'
		);
		$nachname = array(
		    'Schmidt',
		    'Graskorn',
		    'Schneider',
		    'Herzog',
		    'Ladwig',
		);
		$str = array(
		    'Hauptstraße',
		    'Gaißgasse',
		    'Am Friedhof',
		    'Erdbeeräcker',
		    'Schlossallee',
		);
		$stadt = array(
		    'Esslingen',
		    'Stuttgart',
		    'Fellbach',
		    'Filterstadt',
		    'Ludwigsburg',
		);
		$anm = array(
		    'Hinterhof',
		    '',
		    'Eingang auf der Rückseite',
		    '',
		    'Zugang durch den Laden',
		);
		$domains = array(
			'gmx.de',
			'web.de',
			'gmail.com',
			'hotmail.com',
			'yahoo.de',
		);
		
		$buchstaben = new \Zend_Validate_Alpha(true);
		$buchstaben->setMessage('Es dürfen nur Buchstaben eingegeben werden');
		
		$notEmpty = new \Zend_Validate_NotEmpty();
		$notEmpty->setMessage('Feld muss ausgefüllt werden!');
		
		$postcode = new \Zend_Validate_PostCode();
		$postcode->setMessage('Gültige Postleitzahl eingeben!');
		
		$formName = new \Zend_Form_Element_Hidden('form_name');
		$formName->setValue('register');
		
		$emailAddress = new \Zend_Validate_EmailAddress();
		$emailAddress->setMessage('Dies ist keine gültige E-Mail-Adresse');
		
		$laenge = new \Zend_Validate_StringLength(array('min' => 8));
		$laenge->setMessage('Das Passwort muss mindestens acht Zeichen lang sein.');
		
		$gleichheit = new \Zend_Validate_Identical(array('token' => 'password'));
		$gleichheit->setMessage('Die Passwörter stimmen nicht überein.');
		
		$firstname = new \Zend_Form_Element_Text('firstname');
		$firstname->setLabel('Vorname')
		          ->addValidator($buchstaben)
		          ->setAttrib('placeholder', $vorname[rand(0,4)])
		          ->addValidator($notEmpty)
		          ->setAttrib('required', true)
		          ->setRequired(true);
		
		$lastname = new \Zend_Form_Element_Text('lastname');
		$lastname->setLabel('Nachname')
		         ->addValidator($buchstaben)
		         ->setAttrib('placeholder', $nachname[rand(0,4)])
		         ->addValidator($notEmpty)
		         ->setRequired(true);
		
		$address_street = new \Zend_Form_Element_Text('address_street');
		$address_street->setLabel('Straße')
					   ->setAttrib('placeholder', $str[rand(0,4)])
					   ->addValidator($notEmpty)
					   ->setAttrib('required', true)
		               ->setRequired(true);
		
		$address_number = new \Zend_Form_Element_Text('address_number');
		$address_number->setLabel('Hausnummer')
		               ->setAttrib('placeholder', rand(0,120))
		               ->addValidator($notEmpty)
		               ->setAttrib('required', true)
		               ->setRequired(true);
		
		$address_zip = new \Zend_Form_Element_Text('address_zip');
		$address_zip->setLabel('Postleitzahl')
		            ->setAttrib('placeholder', sprintf("70%03d", rand(0,999)))
		            ->addValidator($notEmpty)
		            ->setAttrib('required', true)
		            ->setRequired(true)
		            ->addValidator($postcode);
		
		$address_city = new \Zend_Form_Element_Text('address_city');
		$address_city->setLabel('Stadt')
		             ->setAttrib('placeholder', $stadt[rand(0,4)])
		             ->addValidator($notEmpty)
		             ->setAttrib('required', true)
		             ->setRequired(true);
		
		$address_details = new \Zend_Form_Element_Text('address_details');
		$address_details->setLabel('Adresszusatz')
		                ->setAttrib('placeholder', $anm[rand(0,4)]);
		
		$phone = new \Zend_Form_Element_Text('phone');
		$phone->setLabel('Telefon')
		      ->setAttrib('placeholder', sprintf("+49 1%d %08d", rand(50,79), rand(0,99999999)))
		      ->addValidator($notEmpty)
		      ->setAttrib('required', true)
		      ->setRequired(true);
		
		$email = new \Zend_Form_Element_Text('email');
		$email->setLabel('E-Mail')
		      ->setAttrib('placeholder', sprintf("%s.%s@%s", strtolower($firstname->getAttrib('placeholder')), strtolower($lastname->getAttrib('placeholder')), $domains[rand(0,4)]))
		      ->addValidator($notEmpty)
		      ->setRequired(true)
		      ->setAttrib('required', true)
		      ->addValidator($emailAddress);
		
		$password = new \Zend_Form_Element_Password('password');
		$password->setLabel('Passwort')
		         ->setAttrib('placeholder', '••••••••')
		         ->addValidator($laenge)
		         ->addValidator($notEmpty)
		         ->setAttrib('required', true)
		         ->setRequired(true);
				
		$passwordcheck = new \Zend_Form_Element_Password('passwordcheck');
		$passwordcheck->setLabel('Passwort-Überprüfung')
		              ->setRequired(true)
		              ->setAttrib('placeholder', '••••••••')
		              ->addValidator($notEmpty)
		              ->setAttrib('required', true)
		              ->addValidator($gleichheit);
				
		$submit = new \Zend_Form_Element_Submit('submit');
		$submit->setLabel('Registrieren');
		
		$this->addElements(array($formName, $firstname, $lastname, $address_street, $address_number, 
				$address_zip, $address_city, $address_details, $phone, $email, $password, $passwordcheck, $submit));
	}
}