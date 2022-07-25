<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [


    'db' => [


        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=blog;host=Localhost',
        'driver_options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
        ],
    ],

    'service_manager' => [

        'factories' => [


            'Laminas\Db\Adapter\Adapter' => 'Laminas\Db\Adapter\AdapterServiceFactory',
            'Laminas\Db\TableGateway\TableGateway' => 'Laminas\Db\TableGateway\TableGatewayServiceFactory',

        ],
    ],

    'session_containers' => [
        Laminas\Session\Container::class,
    ],
    'session_storage' => [
        'type' => Laminas\Session\Storage\SessionStorage::class,
    ],
    'session_config' => [
        'cache_expire' => 60 * 24 * 30,
        'cookie_httponly' => true,
        'cookie_lifetime' => 86400 * 30,
        'gc_maxlifetime' => 86400 * 30,
        'name' => 'mm3bb',
        'remember_me_seconds' => 86400 * 30,
        'use_cookies' => true,
    ],
];