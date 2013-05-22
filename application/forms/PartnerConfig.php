<?php

namespace application\forms;

class PartnerConfig extends \Zend_Form
{
	public function init()
	{
		$notEmpty = new \Zend_Validate_NotEmpty();
		$notEmpty->setMessage('Feld muss ausgefüllt werden!');
		
		$formName = new \Zend_Form_Element_Hidden('form_name');
		$formName->setValue('register');
		
		$teamName = new \Zend_Form_Element_Text('name');
		$teamName->setRequired(true)
		         ->setLabel('Teamname')
		         ->addValidator($notEmpty)
		         ->setAttrib('required', true);
		
		$addresses = new \Zend_Form_Element_Radio('addresses');
		$addresses->setRequired()
		          ->setLabel('Gekocht wird bei');
		
		$submit = new \Zend_Form_Element_Submit('submit');
		$submit->setLabel('Speichern');
		
		$this->addElements(array($formName, $teamName, $addresses, $submit));
	}
}
