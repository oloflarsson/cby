<?php
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Entities\ShirtOption;

class ShirtOptions implements FixtureInterface
{
	public function load($em)
	{
		// 2013
		//$name_prices = array(
		//	'Ingen' => 0,
		//	'S'     => 125,
		//	'M'     => 125,
		//	'L'     => 125,
		//	'XL'    => 125
		//);

		// 2014
		$name_prices = array(
			'Ingen' => 0,
			'S'     => 150,
			'M'     => 150,
			'L'     => 150,
			'XL'    => 150
		);
		
		foreach ($name_prices as $name => $price)
		{
			$o = new ShirtOption;
			$o->setName($name);
			$o->setPrice($price);
			$em->persist($o);
		}
		
		$em->flush();
	}
}