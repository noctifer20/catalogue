<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__ . '/src/App/Entity');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__ . '/var');
$classLoader->register();

// Config
$config = new \Doctrine\ORM\Configuration();
$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(__DIR__ . '/src/App/Entity'));
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$config->setProxyDir(__DIR__ . '/var');
$config->setProxyNamespace('proxies_namespace');
$connectionParams = [
  'driver' => 'pdo_pgsql',
  'host' => '127.0.0.1',
  'port' => '5432',
  'user' => 'postgres',
  'password' => '1',
  'dbname' => 'catalogue',
  'charset' => 'utf8',
];
$em = \Doctrine\ORM\EntityManager::create($connectionParams, $config);

// Fetch metadata
$driver = new \Doctrine\ORM\Mapping\Driver\DatabaseDriver(
  $em->getConnection()->getSchemaManager()
);
$tables = [];
//$tables[] = $em->getConnection()->getSchemaManager()->listTableDetails('user');
$tables[] = $em->getConnection()->getSchemaManager()->listTableDetails('receipt');
$driver->setTables($tables, []);

$em->getConfiguration()->setMetadataDriverImpl($driver);
$classes = $driver->getAllClassNames();

$cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory();
$cmf->setEntityManager($em);
$metadata = $cmf->getAllMetadata();
$generator = new Doctrine\ORM\Tools\EntityGenerator();
$generator->setUpdateEntityIfExists(true);
$generator->setGenerateStubMethods(true);
$generator->setGenerateAnnotations(true);
$generator->generate($metadata, __DIR__ . '/src/App/Entity');
print 'Done!';