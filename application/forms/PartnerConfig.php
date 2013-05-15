<?php

namespace application\forms;

class PartnerConfig extends \Zend_Form
{
	public function init()
	{
		$formName = new \Zend_Form_Element_Hidden('form_name');
		$formName->setValue('register');
		
		$addresses = new \Zend_Form_Element_Radio('addresses');
		$addresses->setRequired()
		          ->setLabel('Gekocht wird bei')
		          ->getDecorator('Label')->setOption('escape', false);
		
		$submit = new \Zend_Form_Element_Submit('submit');
		$submit->setLabel('Speichern');
		
		$this->addElements(array($formName, $addresses, $submit));
	}
}