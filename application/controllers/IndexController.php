<?php

class IndexController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$this->view->date = date('d.m.Y');
	}
}