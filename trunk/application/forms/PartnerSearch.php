<?php

namespace application\forms;

class PartnerSearch extends \Zend_Form
{
	public function init()
	{
		$formName = new \Zend_Form_Element_Hidden('form_name');
		$formName->setValue('partnersuche');
		
		$name = new \Zend_Form_Element_Text('name');
		$name->setLabel('Name');
		$name->setRequired(true);
		
		$submit = new \Zend_Form_Element_Submit('submit_login');
		$submit->setLabel('Suchen');
		
		$this->addElements(array($formName, $name, $submit));
	}
}