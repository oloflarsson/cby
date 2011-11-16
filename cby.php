<?php
/*
Plugin Name: CBY
Plugin URI: http://campburnyourself.se
Description: desc desc desc.
Version: 1.0
Author: Olof Larsson
Author URI: http://oloflarsson.se
*/

use Entities\Person;

require_once 'bootstrap.php';

class CBY
{
	public static function init()
	{
		/*global $em;
		
		$person = new Person;
		$person->setFirstname('Sven');
		$person->setNick('Svempa');
		$person->setLastname('Svensson');
		$person->setSsn(1212121212);
		$person->setPhone('070121212');
		$person->setComment('Wooop opoop po poop po  o popo');
		$person->setAllergycomment('jag er elergisk mot hest');
		
		$em->persist($person);
		$em->flush();*/
	}
}
CBY::init();