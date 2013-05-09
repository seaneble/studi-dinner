<?php

namespace application\forms;

class Login extends \Zend_Form
{
	public function init()
	{
		$formName = new \Zend_Form_Element_Hidden('form_name');
		$formName->setValue('login');
		
		$email = new \Zend_Form_Element_Text('username');
		$email->setLabel('E-Mail');
		$email->setRequired(true);
				
		$password = new \Zend_Form_Element_Password('login_password');
		$password->setLabel('Passwort');
		$password->setRequired(true);
			
		$submit = new \Zend_Form_Element_Submit('submit_login');
		$submit->setLabel('Anmelden');
		
		$this->addElements(array($formName, $email, $password, $submit));
	}
}