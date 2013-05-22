<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	
    /**
     * Autoloader registrieren
     * 
     * Der Doctrine-Classloader ersetzt den Zend-Autoloader.
     */
    protected function _initAutoload()
    {
        require_once 'Doctrine/Common/ClassLoader.php';

        // disable Zend Autoloader
        spl_autoload_unregister(array('Zend_Loader_Autoloader', 'autoload'));

        // ZendFramework laden
        $autoloader = new \Doctrine\Common\ClassLoader('Zend');
        $autoloader->setNamespaceSeparator('_');
        $autoloader->register();

        // Doctrine laden
        $autoloader = new \Doctrine\Common\ClassLoader('Doctrine');
        $autoloader->register();

        // StuV-Library laden
        $autoloader = new \Doctrine\Common\ClassLoader('StuV');
        $autoloader->register();

        // Models laden
        $autoloader = new \Doctrine\Common\ClassLoader(
            'application',
            realpath(APPLICATION_PATH . '/..')
        );
        $autoloader->register();
    }
	
	protected function _initSession()
	{
		\Zend_Session::start();
	}

    protected function _initDoctrine()
    {
        $config = new \Doctrine\ORM\Configuration();

        $cache = new \Doctrine\Common\Cache\ArrayCache;
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);

        $driver = $config->newDefaultAnnotationDriver(
            array(APPLICATION_PATH . '/models',
 //                APPLICATION_PATH . '/modules/scheduling/models',
 //                APPLICATION_PATH . '/modules/trips/models',
            )
        );
        $config->setMetadataDriverImpl($driver);

        // set the proxy dir and set some options
        $config->setProxyDir(APPLICATION_PATH . '/models/Proxies');
        $config->setAutoGenerateProxyClasses(false);
        $config->setProxyNamespace('App\Proxies');

        // now create the entity manager and use the connection
        // settings we defined in our application.ini
        $connectionSettings = $this->getOption('doctrine');
        $conn = array('driver' => $connectionSettings['conn']['driv'],
            'user' => $connectionSettings['conn']['user'],
            'password' => $connectionSettings['conn']['pass'],
            'dbname' => $connectionSettings['conn']['dbname'],
            'host' => $connectionSettings['conn']['host']
        );
        $entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);

        // push the entity manager into our registry for later use
        $registry = Zend_Registry::getInstance();
        $registry->entitymanager = $entityManager;

        return $entityManager;
    }
    
    protected function _initLocale()
    {
	    $locale = new \Zend_Locale('de_DE');
	    \Zend_Registry::set('Zend_Locale', $locale);
    }
    
    /**
     * Konfiguration verfügbar machen
     */
    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $config);
    }
    
    /**
     * Plugins initialisieren
     * 
     * * Plugin zur Authentifizierungskontrolle
     */
    protected function _initPlugins()
    {
        //require_once('StuV/Controller/Plugin/ClientAuthPlugin.php');
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(
            new \StuV\Controller\Plugin\AuthPlugin()
        );
        $front->registerPlugin(
            new \StuV\Controller\Plugin\MessengerPlugin()
        );
    }
}
