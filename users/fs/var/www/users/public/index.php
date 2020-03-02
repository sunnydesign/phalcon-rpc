<?php

use Phalcon\Di\FactoryDefault;
use MyApp\Library\RPCResponse;
use MyApp\Library\RPCException32600;
use MyApp\Library\RPCException32601;
use MyApp\Library\RPCException32700;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

require __DIR__ . '/../vendor/autoload.php';

$di = new FactoryDefault();

include APP_PATH . '/config/services.php';

$config = $di->getConfig();

include APP_PATH . '/config/loader.php';

// get request
$request = $di->getShared('request');

// get dispatcher
$dispatcher = $di->getShared('dispatcher');

try {
    // get json data as object
    try {
        $data = $request->getJsonRawBody();
    } catch (\Exception $e) {
        throw new RPCException32700();
    }

    // json is not json-rps
    if (!property_exists($data, 'jsonrpc') || !property_exists($data, 'method')) {
        throw new RPCException32600();
    }

    // parse method
    $method = explode('.', $data->method);

    // set a route
    $dispatcher->setControllerName($method[0]);
    $dispatcher->setActionName($method[1] ?? 'index');
    $dispatcher->setParams(array_merge((array)$data->params, ['id' => $data->id]));
    $dispatcher->dispatch();

    // get response
    $response = $dispatcher->getReturnedValue();
} catch (RPCException32700 $e) {
    // error parse json
    $response = new RPCResponse();
    $response->error = $e;
    $response->setStatusCode(500);
} catch (RPCException32600 $e) {
    // json is not json-rps
    $response = new RPCResponse();
    $response->error = $e;
    $response->setStatusCode(400);
} catch (DispatchException $e) {
    // procedure not found
    $response = new RPCResponse();
    $response->id = $data->id;
    $response->error = new RPCException32601();
    $response->setStatusCode(404);
}

// return content
echo $response->getContent();