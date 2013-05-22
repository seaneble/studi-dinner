<?php

namespace application\forms;

class Login extends \Zend_Form
{
	public function init()
	{
		$notEmpty = new \Zend_Validate_NotEmpty();
		$notEmpty->setMessage('Feld muss ausgefüllt werden!');
		$emailAddress = new \Zend_Validate_EmailAddress();
		$emailAddress->setMessage('Dies ist keine gültige E-Mail-Adresse');
		
		$formName = new \Zend_Form_Element_Hidden('form_name');
		$formName->setValue('login');
		
		$email = new \Zend_Form_Element_Text('username');
		$email->setLabel('E-Mail')
		      ->addValidator($notEmpty)
		      ->addValidator($emailAddress)
		      ->setAttrib('required', true)
		      ->setRequired(true);
				
		$password = new \Zend_Form_Element_Password('login_password');
		$password->setLabel('Passwort')
				 ->addValidator($notEmpty)
		         ->setAttrib('required', true)
				 ->setRequired(true);
			
		$submit = new \Zend_Form_Element_Submit('submit_login');
		$submit->setLabel('Anmelden');
		
		$this->addElements(array($formName, $email, $password, $submit));
	}
}