<?php

declare(strict_types=1);

// bootstrap.php
// bootstrap file for public/index.php and bin/

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use DI\Container;

require_once __DIR__ . '/vendor/autoload.php';

if (!file_exists(__DIR__ . '/settings.php')) {
    copy(__DIR__ . '/settings.php.dist', __DIR__ . '/settings.php');
}

if (!file_exists(__DIR__ . '/var/database.sqlite')) {
    touch(__DIR__ . '/var/database.sqlite');
}

$container =  new Container(require __DIR__ . '/settings.php');

$settings = $container->get('settings');

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: $settings['doctrine']['metadata_dirs'],
    isDevMode: $settings['doctrine']['dev_mode'],
);

$connection = DriverManager::getConnection([
    'driver' => $settings['doctrine']['connection']['driver'],
    'path' => $settings['doctrine']['connection']['path'],
], $config);

$entityManager = new EntityManager($connection, $config);

$container->set(EntityManager::class, $entityManager);

return $container;
