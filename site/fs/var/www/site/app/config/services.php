<?php

use Phalcon\Security;
use Phalcon\Escaper;
use Phalcon\Mvc\View;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Url as UrlResolver;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;

// set DI config
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

// set DI view
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.phtml' => PhpEngine::class
    ]);

    return $view;
});

// set DI session
$di->set('session', function () {
    $session = new Manager();
    $files = new Stream(
        [
            'savePath' => '/tmp',
        ]
    );

    $session->setAdapter($files);
    $session->start();

    return $session;
});

// set DI security
$di->setShared('security', function () {
    $security = new Security();
    $security->setWorkFactor(12);

    return $security;
});

// set DI flash
$di->set('flash', function () {
    $escaper = new Escaper();
    $flash = new Flash($escaper);
    $flash->setImplicitFlush(false);
    $flash->setCssClasses([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);

    return $flash;
});
