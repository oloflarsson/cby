<?php
$cbyconf = array(
	'doctrine' => array(
		'devmode' => true,
		'connectionOptions' => array(
			'driver' =>   'pdo_mysql',
			'user' =>     DB_USER,     // from wp-config.php
			'password' => DB_PASSWORD, // from wp-config.php
			'host' =>     DB_HOST,     // from wp-config.php
			'dbname' =>   DB_NAME,     // from wp-config.php
		),
	),	
);