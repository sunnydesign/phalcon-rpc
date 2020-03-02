<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Phalcon\Http\Message\Uri;

use Graze\GuzzleHttp\JsonRpc\Client;
use Graze\GuzzleHttp\JsonRpc\Exception\RequestException;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

/**
 * Autoload composer vendors
 */
require __DIR__ . '/../vendor/autoload.php';

try {
    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Read services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    $app = new Micro($di);

    // index get
    $app->get(
        '/',
        function () use ($app) {
            $app->assets->addCss('css/style.css');
            $app->view->render('index', 'index');
        }
    );

    // index post
    $app->post(
        '/',
        function () use ($app) {
            $config = $app->getDI()->getConfig();
            $uri = new Uri($config['usersUrl']);

            $payload = [
                'login' => $app->request->getPost('login', 'string'),
                'password' => $app->request->getPost('password', 'string'),
            ];

            $client = Client::factory($uri, ['rpc_error' => true]);
            $request = $client->request(1, 'users.auth', $payload);

            try {
                $response = $client->send($request);
                $result = json_decode($response->getRpcResult());

                if('true' === $result->success) {
                    $app->flash->success($result->message);
                } else {
                    $app->flash->error($result->message);
                }
            } catch (RequestException $e) {
                die($e->getResponse()->getRpcErrorMessage());
            }

            $app->assets->addCss('css/style.css');
            $app->view->render('index', 'index');
        }
    );

    $app->notFound(
        function () use ($app) {
            $message = 'Nothing to see here. Move along....';
            $app
                ->response
                ->setStatusCode(404, 'Not Found')
                ->sendHeaders()
                ->setContent($message)
                ->send()
            ;
        }
    );

    $app->handle($_SERVER['REQUEST_URI']);

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
