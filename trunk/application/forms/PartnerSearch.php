<?php

namespace application\forms;

class PartnerSearch extends \Zend_Form
{
	public function init()
	{
		
		$notEmpty = new \Zend_Validate_NotEmpty();
		$notEmpty->setMessage('Feld muss ausgefüllt werden!');
		
		$formName = new \Zend_Form_Element_Hidden('form_name');
		$formName->setValue('partnersuche');
		
		$name = new \Zend_Form_Element_Text('name');
		$name->setLabel('Name')
		     ->addValidator($notEmpty)
		     ->setAttrib('required', true)
		     ->setRequired(true);
		
		$submit = new \Zend_Form_Element_Submit('submit_login');
		$submit->setLabel('Suchen');
		
		$this->addElements(array($formName, $name, $submit));
	}
}