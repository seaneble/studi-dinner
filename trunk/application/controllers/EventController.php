<?php

class EventController extends Zend_Controller_Action
{
	public function init()
	{
		$registry = \Zend_Registry::getInstance();
		$this->em = $registry->entitymanager;
	}
}
