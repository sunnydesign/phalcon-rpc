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
        return $this->password === $this->hashPassword($password);
    }

    /**
     * Hash password sha256 algorithm with salt
     *
     * @param string|null $password
     * @return string
     */
    public function hashPassword(?string $password): string
    {
        $config = $this->getDI()->getConfig();
        return hash('sha256', $password . $config['salt']);
    }
}