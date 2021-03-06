<?php
use Doctrine\Common\ClassLoader,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration;

# =============================================
# CREATE CONSTANTS AND LOAD CONFIGURATION
# =============================================

# Create some abspath folder definitions
define('CBY_BASEFOLDER', dirname(__FILE__));
define('CBY_LIBFOLDER', CBY_BASEFOLDER.'/lib');

# Get the configuration
require_once 'conf.php';

# =============================================
# DOCTRINE
# =============================================

# === Setup Class-loading ===
# The classloader can't load itself if it isn't loaded already :P
if ( ! class_exists('Doctrine\Common\ClassLoader', false))
{
	require_once CBY_LIBFOLDER . '/doctrine-common/lib/Doctrine/Common/ClassLoader.php';
}

$packagefolders = array(
	'Doctrine\Common\DataFixtures' => CBY_LIBFOLDER.'/doctrine-data-fixtures/lib',
	'Doctrine\Common'              => CBY_LIBFOLDER.'/doctrine-common/lib',
	'Doctrine\DBAL\Migrations'     => CBY_LIBFOLDER.'/doctrine-migrations/lib',
	'Doctrine\DBAL'                => CBY_LIBFOLDER.'/doctrine-dbal/lib',
	'Doctrine\ORM'                 => CBY_LIBFOLDER.'/doctrine-orm/lib',
	'Symfony\Component'            => CBY_LIBFOLDER,
	'Entities'                     => CBY_BASEFOLDER,
	'Proxies'                      => CBY_BASEFOLDER,
);

foreach ($packagefolders as $package => $folder)
{
	$loader = new ClassLoader($package, $folder);
	$loader->register();
}

# === Obtaining the EntityManager ===
// TODO: Fixa APC så jag kan använda APC-Cache.
$cache = new \Doctrine\Common\Cache\ArrayCache;

$config = new Configuration;
$config->setMetadataCacheImpl($cache);
$driverImpl = $config->newDefaultAnnotationDriver(CBY_BASEFOLDER.'/Entities');
$config->setMetadataDriverImpl($driverImpl);
$config->setQueryCacheImpl($cache);
$config->setProxyDir(CBY_BASEFOLDER.'/Proxies');
$config->setProxyNamespace('Proxies');

$config->setAutoGenerateProxyClasses($cbyconf['doctrine']['devmode']);

$em = EntityManager::create($cbyconf['doctrine']['connectionOptions'], $config);