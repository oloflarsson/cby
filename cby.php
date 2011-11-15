<?php
/*
Plugin Name: CBY
Plugin URI: http://campburnyourself.se
Description: desc desc desc.
Version: 1.0
Author: Olof Larsson
Author URI: http://oloflarsson.se
*/

// Bootstrap doctrine
require 'doctrine/lib/Doctrine/ORM/Tools/Setup.php';
$lib = 'doctrine'; /// Funkar detta eller måste vi ha abspath?
Doctrine\ORM\Tools\Setup::registerAutoloadGit($lib);

class CBY
{
	public static $example;
}