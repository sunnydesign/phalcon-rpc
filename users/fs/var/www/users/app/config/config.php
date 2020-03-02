<?php

/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

// если не прописать схему как `string`, то Phalcon\Migrations\Utils::resolveDbSchema(Config $config)
// возвращает `null` для адаптера `Sqlite`, и при $connection->createTable() выходит следующая ошибка:
//
// PHP Fatal error:  Uncaught TypeError:
// Argument 2 passed to Phalcon\Db\Adapter\AbstractAdapter::createTable() must be of the type string,
// null given in /var/www/users/vendor/phalcon/migrations/src/Mvc/Model/Migration.php:737
return new \Phalcon\Config([
    'database' => [
        'adapter' => 'Sqlite',
        'dbname' => APP_PATH . '/users.db',
        'schema' => '',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'libraryDir'     => APP_PATH . '/library/',
        'baseUri'        => '/',
    ],
    'salt' => 's#d8!6s',
]);
