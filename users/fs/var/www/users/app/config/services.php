<?php

use Phalcon\Mvc\Dispatcher;
use Phalcon\Http\Request;

// set DI config
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

// set DI DB
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'dbname'   => $config->database->dbname,
    ];

    return new $class($params);
});

// set DI Dispatcher
$di->setShared('dispatcher', function () {
    return new Dispatcher();
});

// set DI Request
$di->setShared('request', function () {
    return new Request();
});