#!/usr/bin/php7.2

<?php

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

// если не прописать схему как `string`, то Phalcon\Migrations\Utils::resolveDbSchema(Config $config)
// возвращает `null` для адаптера `Sqlite`, и при $connection->createTable() выходит следующая ошибка:
//
// PHP Fatal error:  Uncaught TypeError:
// Argument 2 passed to Phalcon\Db\Adapter\AbstractAdapter::createTable() must be of the type string,
// null given in /var/www/users/vendor/phalcon/migrations/src/Mvc/Model/Migration.php:737
/*
use Phalcon\Config;
use Phalcon\Migrations\Migrations;

require __DIR__ . '/vendor/autoload.php';

$dbConfig = new Config([
    'database' => [
        'adapter' => 'Sqlite',
        'dbname' => APP_PATH .'/users.db',
        'schema' => ''
    ],
]);

$mgrConfig = [
    'migrationsDir' => [
        'app/migrations',
    ],
    'config' => $dbConfig
];

$migration = new Migrations();

$migration::run($mgrConfig);

*/

use Phalcon\Db\Adapter\Pdo\Sqlite;
use Phalcon\Db\Column;

$path = APP_PATH . '/users.db';
if (!file_exists($path)) {
    $f = fopen($path, 'w');
    fclose($f);
}

$connection = new Sqlite(
    [
        'dbname' => APP_PATH . '/users.db',
    ]
);

if(!$connection->tableExists('users')) {
    $connection->createTable('users', '', [
        'columns' => [
            new Column(
                'id',
                [
                    'type' => Column::TYPE_INTEGER,
                    'autoIncrement' => true,
                    'size' => 1,
                    'first' => true
                ]
            ),
            new Column(
                'username',
                [
                    'type' => Column::TYPE_TEXT,
                    'notNull' => true,
                    'size' => 1,
                    'after' => 'id'
                ]
            ),
            new Column(
                'password',
                [
                    'type' => Column::TYPE_TEXT,
                    'notNull' => true,
                    'size' => 1,
                    'after' => 'username'
                ]
            ),
        ]
    ]);

    $success = $connection->insertAsDict(
        'users',
        [
            'id' => 1,
            'username' => 'admin',
            'password' => 'f4696338d7b2c17dcd9439e72a6e893a2205c3604696e474070c39ee7b1b948f',
        ]
    );
}