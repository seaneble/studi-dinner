<?php

namespace application\forms;

class Register extends \Zend_Form
{
	public function init()
	{
		$firstname = new \Zend_Form_Element_Text('firstname');
		$firstname->setLabel('Vorname');
		$firstname->setRequired(true);
		
		$lastname = new \Zend_Form_Element_Text('lastname');
		$lastname->setLabel('Nachname');
		
		$address_street = new \Zend_Form_Element_Text('address_street');
		$address_street->setLabel('Straße');
		$address_street->setRequired(true);
		
		$address_number = new \Zend_Form_Element_Text('address_number');
		$address_number->setLabel('Hausnummer');
		$address_number->setRequired(true);
		
		$address_zip = new \Zend_Form_Element_Text('address_zip');
		$address_zip->setLabel('Postleitzahl');
		$address_zip->setRequired(true);
		
		$address_city = new \Zend_Form_Element_Text('address_city');
		$address_city->setLabel('Stadt');
		$address_city->setRequired(true);
		
		$address_details = new \Zend_Form_Element_Text('address_details');
		$address_details->setLabel('Adresszusatz');
		
		$phone = new \Zend_Form_Element_Text('phone');
		$phone->setLabel('Telefon');
		$phone->setRequired(true);
		
		$email = new \Zend_Form_Element_Text('email');
		$email->setLabel('E-Mail');
		$email->setRequired(true);
		
		$password = new \Zend_Form_Element_Text('password');
		$password->setLabel('Passwort');
		$password->setRequired(true);
			
		$submit = new \Zend_Form_Element_Submit('submit');
		$submit->setLabel('Anmelden');
		
		$this->addElements(array($firstname, $lastname, $address_street, $address_number, 
				$address_zip, $address_city, $address_details, $phone, $email, $password $submit));
	}
}