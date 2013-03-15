<?php

define('APPLICATION_ENV', 'development');

define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

set_include_path(
    implode(
        PATH_SEPARATOR,
        array(realpath(APPLICATION_PATH . '/../library'), get_include_path(),)));

// Doctrine and Symfony Classes
require_once 'Doctrine/Common/ClassLoader.php';
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader(
    'Symfony',
    '/usr/share/php/Doctrine'
);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader(
    'application',
    APPLICATION_PATH . '/..');
$classLoader->register();

$prefixPath = '';
$modulePosition = array_keys($_SERVER['argv'], 'module');
if (!empty($modulePosition))
{
    $moduleName = $_SERVER['argv'][$modulePosition[0] + 1];

    if (is_dir(APPLICATION_PATH . "/modules/$moduleName"))
    {
        echo "Zend module $moduleName will be used.\n";
        $prefixPath = "modules/$moduleName";
    }

    array_pop($_SERVER['argv']);
    array_pop($_SERVER['argv']);
}

$classLoader = new \Doctrine\Common\ClassLoader(
    'Entities',
    APPLICATION_PATH . $prefixPath . '/models');
$classLoader->setNamespaceSeparator('_');
$classLoader->register();

// Zend Components
require_once 'Zend/Application.php';

// Create application
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini');

// bootstrap doctrine
$application->getBootstrap()
            ->bootstrap('doctrine');
$em = $application->getBootstrap()
                  ->getResource('doctrine');

// generate the Doctrine HelperSet
$helperSet = new \Symfony\Component\Console\Helper\HelperSet(
    array(
        'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper(
            $em->getConnection()),
        'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
    ));

\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);
