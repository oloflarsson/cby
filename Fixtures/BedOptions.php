<?php
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Entities\BedOption;

class BedOptions implements FixtureInterface
{	
	public function load($em)
	{
		$name_prices = array(
			'Eget Tält' => 0,
			'Säng' => 150,
		);
		
		foreach ($name_prices as $name => $price)
		{
			$o = new BedOption;
			$o->setName($name);
			$o->setPrice($price);
			$em->persist($o);
		}
		
		$em->flush();
	}
}