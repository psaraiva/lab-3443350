<?php

namespace App;

use App\Adapters\In\Cli\Controller\UserController;

// CSV
use App\Adapters\Out\RepositoryCsv\UserRepositoryCsv;
use App\Adapters\Out\RepositoryCsv\Mapper\UserEntityMapperCsv;
use App\Adapters\Out\RepositoryCsv\Entity\UserEntityCsv;

// Json file
use App\Adapters\Out\RepositoryJson\UserRepositoryJson;
use App\Adapters\Out\RepositoryJson\Mapper\UserEntityMapperJson;
use App\Adapters\Out\RepositoryJson\Entity\UserEntityJson;

// Sqlite
use App\Adapters\Out\RepositorySqlite\UserRepositorySqlite;
use App\Adapters\Out\RepositorySqlite\Mapper\UserEntityMapperSqlite;
use App\Adapters\Out\RepositorySqlite\Entity\UserEntitySqlite;

use Exception;

require __DIR__ . '/vendor/autoload.php';

define('APP_PATH', __DIR__);

$actions = ['insert', 'find-by-id', 'update', 'delete-by-id'];

// parans accepted
$options = getopt("", ["db-engine:", "action:", "entity:", "data:"]);
$db_engine = strtolower(trim($options['db-engine'])) ?? '';
$entity = strtolower(trim($options['entity'])) ?? '';
$action = strtolower(trim($options['action'])) ?? '';
$data = strtolower(trim($options['data'])) ?? '';

// MAIN
echo main($entity, $action, $actions, $db_engine, $data) . PHP_EOL;

function main(string $entity, string $action, array $actions, string $db_engine, string $data): string
{
    validAction($action, $actions);
    validEntity($entity);

    $userController = getUserController($db_engine);
    if ($action == 'insert') {
        return $userController->insert($data);
    }
    
    if ($action == 'find-by-id') {
        return $userController->findById($data);
    }
    
    if ($action == 'update') {
        return $userController->update($data);
    }
    
    if ($action == 'delete-by-id') {
        return $userController->deleteById($data);
    }

    return 'none';
}


function validEntity(string $entity)
{
    if ($entity != 'user') {
        throw new Exception("Option entity not found.");
    }
}

function validAction(string $action, array $actions)
{
    $actions = array_flip($actions);
    if (!isset($actions[$action])) {
        throw new Exception("Option action not found.");
    }
}

function getUserController(string $db_engine)
{
    $userEntityMapper = $userRepository = null;
    if ($db_engine == 'sqlite') {
        $userRepository = new UserRepositorySqlite(new UserEntitySqlite());
        $userEntityMapper = new UserEntityMapperSqlite();
    }

    if ($db_engine == 'csv') {
        $userRepository = new UserRepositoryCsv(new UserEntityCsv());
        $userEntityMapper = new UserEntityMapperCsv();
    }

    if ($db_engine == 'json') {
        $userRepository = new UserRepositoryJson(new UserEntityJson());
        $userEntityMapper = new UserEntityMapperJson();
    }

    if ($userRepository == null || $userEntityMapper == null) {
        throw new Exception("Option core not found.");
    }

    return new UserController($userRepository, $userEntityMapper);
}
