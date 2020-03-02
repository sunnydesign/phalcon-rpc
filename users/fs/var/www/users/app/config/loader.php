<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->libraryDir,
    ]
)->registerNamespaces(
    [
        'MyApp\Controllers' => $config->application->controllersDir,
        'MyApp\Models' => $config->application->modelsDir,
        'MyApp\Library' => $config->application->libraryDir,
    ]
)->register();
