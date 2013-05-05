<?php

namespace application\forms;

class Login extends \Zend_Form
{
	public function init()
	{
		$email = new \Zend_Form_Element_Text('username');
		$email->setLabel('E-Mail');
		$email->setRequired(true);
		
		$password = new \Zend_Form_Element_Text('login_password');
		$password->setLabel('Passwort');
		$password->setRequired(true);
			
		$submit = new \Zend_Form_Element_Submit('submit_login');
		$submit->setLabel('Einloggen');
		
		$this->addElements(array($email, $password, $submit));
	}
}