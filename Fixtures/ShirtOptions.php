<?php
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Entities\ShirtOption;

class ShirtOptions implements FixtureInterface
{	
	public function load($em)
	{
		$name_prices = array(
			'Ingen' => 0,
			'S'     => 125,
			'M'     => 125,
			'L'     => 125,
			'XL'    => 125
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