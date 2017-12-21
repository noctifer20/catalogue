<?php
$config = require 'config.php';

$container = new \Slim\Container($config);
$container[\Doctrine\ORM\EntityManager::class] = function () use ($container) {
  $classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__ . '/App/entity');
  $classLoader->register();
  $classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__ . '/../var');
  $classLoader->register();

  $config = new \Doctrine\ORM\Configuration();
  $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(__DIR__ . '/App/entity', false));
  $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
  $config->setProxyDir(__DIR__ . '/var');
  $config->setProxyNamespace('proxies_namespace');
  $connectionParams = [
    'driver' => 'pdo_pgsql',
    'host' => $container->get('DB_HOST'),
    'port' => $container->get('DB_PORT'),
    'user' => $container->get('DB_USER'),
    'password' => $container->get('DB_PASS'),
    'dbname' => $container->get('DB_NAME'),
    'charset' => 'utf8',
  ];
  $em = \Doctrine\ORM\EntityManager::create($connectionParams, $config);

  return $em;
};
$container[\App\Service\ImageUploadService::class] = function () use ($container) {
  return new \App\Service\ImageUploadService($container->get('UPLOAD_DIR'));
};
$container[\App\Controller\UserController::class] = function () use ($container) {
  $entityManager = $container->get(\Doctrine\ORM\EntityManager::class);
  $imageUploadService = $container->get(\App\Service\ImageUploadService::class);

  return new \App\Controller\UserController($entityManager, $imageUploadService);
};
$container[\App\Controller\ReceiptController::class] = function () use ($container) {
  $entityManager = $container->get(\Doctrine\ORM\EntityManager::class);

  return new \App\Controller\ReceiptController($entityManager);
};


return $container;