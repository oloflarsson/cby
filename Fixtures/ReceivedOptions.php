<?php
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Entities\ReceivedOption;

class ReceivedOptions implements FixtureInterface
{	
	public function load($em)
	{
		$name_prices = array(
			'ja' => 0,
			'nej' => 0,
			'ska inte ha' => 0
		);
		
		foreach ($name_prices as $name => $price)
		{
			$o = new ReceivedOption;
			$o->setName($name);
			$o->setPrice($price);
			$em->persist($o);
		}
		
		$em->flush();
	}
}