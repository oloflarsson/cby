<?php
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Entities\ConsumerOption;

class ConsumerOptions implements FixtureInterface
{	
	public function load($em)
	{
		$name_prices = array(
			'Allätare'   => 0,
			'Vegetarian' => 0,
			'Vegan'      => 0,
		);
		
		foreach ($name_prices as $name => $price)
		{
			$o = new ConsumerOption;
			$o->setName($name);
			$o->setPrice($price);
			$em->persist($o);
		}
		
		$em->flush();
	}
}