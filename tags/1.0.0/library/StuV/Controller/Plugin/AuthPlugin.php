<?php

namespace StuV\Controller\Plugin;

class AuthPlugin extends \Zend_Controller_Plugin_Abstract
{
	
	public function dispatchLoopStartup(\Zend_Controller_Request_Abstract $request)
    {
        if($request->getControllerName() == 'index' && ($request->getActionName() == 'login' || $request->getActionName() == 'token' || $request->getActionName() == 'info'))
        {
            return;
        }

        $auth = \Zend_Auth::getInstance();
        if($auth->hasIdentity())
        {
            $user = $auth->getIdentity();
            $time = time();
            
            if ($time - $user['timestamp'] > \Zend_Registry::get('config')->sessionDuration)
            {
                return $this->logoutAndRedirectWithMessage($request);
            }

            $user['timestamp'] = $time;
            $auth->getStorage()->write($user);
        }
        else
        {
            return $this->logoutAndRedirect($request);
        }
    }
    
    public function postDispatch()
    {
		$view = \Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;
		
		$auth = \Zend_Auth::getInstance();
        if($auth->hasIdentity())
			$view->login = true;
		else
			$view->login = false;
    }
    
    protected function logoutAndRedirectWithMessage(\Zend_Controller_Request_Abstract $request)
    {
        $messenger = \Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        $messenger->addMessage('Aufgrund zu langer Inaktivität wurdest du automatisch abgemeldet.');
        
        $this->logoutAndRedirect($request);
    }
    
    protected function logoutAndRedirect(\Zend_Controller_Request_Abstract $request)
    {
    	\Zend_Auth::getInstance()->clearIdentity();
        
        $request->setControllerName('index')
                ->setActionName('login');
        return;
    }
}