<?php

use Phalcon\Mvc\Controller;
use MyApp\Models\Users;
use MyApp\Library\RPCResponse;

class UsersController extends Controller
{
    /**
     * @param string $login
     * @param string $password
     * @param int|null $id
     * @return RPCResponse
     */
    public function authAction(string $login, string $password, ?int $id): RPCResponse
    {
        $user = Users::getUser($login);
        $auth = null === $user ? false : $user->authenticate($password);

        $success = ['success' => 'true', 'message' => 'успешная авторизация'];
        $failure = ['success' => 'false', 'message' => 'неверный логин или пароль'];

        $authMessage = $auth ? $success : $failure;

        $response = new RPCResponse();
        $response->id = $id;
        $response->setJsonContent($authMessage);

        return $response;
    }
}