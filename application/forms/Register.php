<?php

namespace application\forms;

class Register extends \Zend_Form
{
	public function init()
	{
		$firstName = new \Zend_Form_Element_Text('firstname');
		$firstName->setLabel('Vorname');
		$firstName->setRequired(true);
		
		$lastName = new \Zend_Form_Element_Text('lastname');
		$lastName->setLabel('Nachname');
		
		$submit = new \Zend_Form_Element_Submit('submit');
		$submit->setLabel('Anmelden');
		
		$this->addElements(array($firstName, $lastName, $submit));
	}
}