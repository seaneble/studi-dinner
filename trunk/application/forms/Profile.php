<?php

namespace application\forms;

class Profile extends \Zend_Form
{
	public function init()
	{
		$anm = array(
		    'Hinterhof',
		    '',
		    'Eingang auf der Rückseite',
		    '',
		    'Zugang durch den Laden',
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
		          ->addValidator($notEmpty)
		          ->setAttrib('required', true)
		          ->setRequired(true);
		
		$lastname = new \Zend_Form_Element_Text('lastname');
		$lastname->setLabel('Nachname')
		         ->addValidator($buchstaben)
		         ->addValidator($notEmpty)
		         ->setRequired(true);
		
		$address_street = new \Zend_Form_Element_Text('address_street');
		$address_street->setLabel('Straße')
					   ->addValidator($notEmpty)
					   ->setAttrib('required', true)
		               ->setRequired(true);
		
		$address_number = new \Zend_Form_Element_Text('address_number');
		$address_number->setLabel('Hausnummer')
		               ->addValidator($notEmpty)
		               ->setAttrib('required', true)
		               ->setRequired(true);
		
		$address_zip = new \Zend_Form_Element_Text('address_zip');
		$address_zip->setLabel('Postleitzahl')
		            ->addValidator($notEmpty)
		            ->setAttrib('required', true)
		            ->setRequired(true)
		            ->addValidator($postcode);
		
		$address_city = new \Zend_Form_Element_Text('address_city');
		$address_city->setLabel('Stadt')
		             ->addValidator($notEmpty)
		             ->setAttrib('required', true)
		             ->setRequired(true);
		
		$address_details = new \Zend_Form_Element_Text('address_details');
		$address_details->setLabel('Adresszusatz')
		                ->setAttrib('placeholder', $anm[rand(0,4)]);
		
		$phone = new \Zend_Form_Element_Text('phone');
		$phone->setLabel('Telefon')
		      ->addValidator($notEmpty)
		      ->setAttrib('required', true)
		      ->setRequired(true);
		
		$email = new \Zend_Form_Element_Text('email');
		$email->setLabel('E-Mail')
		      ->addValidator($notEmpty)
		      ->setRequired(true)
		      ->setAttrib('required', true)
		      ->addValidator($emailAddress);
        
        $dislike = new \Zend_Form_Element_Multiselect('dislikes');
        $dislike->setLabel('Ich mag nicht')
                ->setOptions(array('class' => 'chosen'));
        
        $zutat = new \Zend_Form_Element_Text('zutat');
        $zutat->setLabel('Neue Zutat hinzufügen')
		      ->setAttrib('placeholder', 'Kommagetrennte Liste')
		      ->setDescription('Allergien, die nicht in der Liste auftauchen, bitte hier eintragen.');
		
		$password = new \Zend_Form_Element_Password('password');
		$password->setLabel('Passwort')
		         ->addValidator($laenge);
        
		$passwordcheck = new \Zend_Form_Element_Password('passwordcheck');
		$passwordcheck->setLabel('Passwort-Überprüfung')
		              ->addValidator($gleichheit);
        
		$submit = new \Zend_Form_Element_Submit('submit');
		$submit->setLabel('Daten speichern');
		
		$this->addElements(array($formName, $dislike, $zutat, $firstname, $lastname, $address_street, $address_number, 
				$address_zip, $address_city, $address_details, $phone, $email, $password, $passwordcheck, $submit));
	}
}