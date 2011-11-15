<?php
/*
Plugin Name: CBY
Plugin URI: http://campburnyourself.se
Description: desc desc desc.
Version: 1.0
Author: Olof Larsson
Author URI: http://oloflarsson.se
*/

define('CBY_ABSPATH', WP_PLUGIN_DIR . '/cby');

class CBY
{
	// The Doctrine2 EntityManager
	public static $em;
	
	public static function init()
	{
		self::doctrine_bootstrap();
	}
	
	public static function doctrine_bootstrap()
	{
		// Setup the doctrine class loader
		require 'doctrine/lib/Doctrine/ORM/Tools/Setup.php';
		Doctrine\ORM\Tools\Setup::registerAutoloadGit(CBY_ABSPATH . '/doctrine');
		
		$paths = array(CBY_ABSPATH . '/entities');
		$isDevMode = true;
		$config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
		
		// Database connection information
		$connectionOptions = array(
			'driver' =>   'pdo_mysql',
			'user' =>     DB_USER,
			'password' => DB_PASSWORD,
			'host' =>     DB_HOST,
			'dbname' =>   DB_NAME,
		);
		
		// Create EntityManager
		self::$em = Doctrine\ORM\EntityManager::create($connectionOptions, $config);
	}
}
CBY::init();