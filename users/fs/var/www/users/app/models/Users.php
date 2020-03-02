<?php

namespace MyApp\Models;

use Phalcon\Mvc\Model;

class Users extends Model
{
    public $id;
    public $username;
    public $password;

    /**
     * Get user by username
     *
     * @param string|null $username
     * @return mixed
     */
    public static function getUser(?string $username)
    {
        return self::findFirst([
            'conditions' => 'username = :username:',
            'bind' => [
                'username' => $username
            ]
        ]);
    }

    /**
     * Authenticate user by password
     *
     * @param string|null $password
     * @return bool
     */
    public function authenticate(?string $password): bool
    {
        if($this->getDi()->getShared('security')->checkHash($password, $this->password)) {
            return true;
        } else {
            $this->getDi()->getShared('security')->hash(rand());
            return false;
        }
    }
}