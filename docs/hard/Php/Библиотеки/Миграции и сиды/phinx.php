<?php

include_once 'config.php';

$pdoString = M_PDO_STRING ?: M_DEV_MACHINE_PDO_STRING;

preg_match('/pgsql:host=(.*)(;|$)/mU', $pdoString, $matches);
$host = $matches[1];
preg_match('/dbname=(.*)(;|$)/mU', $pdoString, $matches);
$dbname = $matches[1];
preg_match('/port=(.*)(;|$)/mU', $pdoString, $matches);
$port = $matches[1];
preg_match('/user=(.*)(;|$)/mU', $pdoString, $matches);
$user = $matches[1];
preg_match('/password=(.*)(;|$)/mU', $pdoString, $matches);
$password = $matches[1];

return
    [
        'paths'         => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/project/db/migrations',
            'seeds'      => '%%PHINX_CONFIG_DIR%%/project/db/seeds',
        ],
        'environments'  => [
            'default_migration_table' => 'phinxlog',
            'default_environment'     => 'development',
            'development'             => [
                'adapter' => 'pgsql',
                'host'    => $host,
                'name'    => $dbname,
                'user'    => $user,
                'pass'    => $password,
                'port'    => $port,
                'charset' => 'utf8',
            ],
        ],
        'version_order' => 'creation',
    ];
