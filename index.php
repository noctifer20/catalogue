<?php
$loader = require 'vendor/autoload.php';
$loader->set('App\\', __DIR__ . '/src/');

$container = require_once __DIR__.'/src/dependency.php';

$app = new \Slim\App($container);


require_once __DIR__.'/src/routes/index.route.php';

try {
  $app->run();
} catch (Exception $e) {
  echo $e->getMessage();
}
