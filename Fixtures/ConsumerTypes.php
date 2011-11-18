<?php
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Entities\ConsumerType;

class ConsumerTypes implements FixtureInterface
{	
	public function load($em)
	{
		$names = array('Allätare', 'Vegetarian', 'Vegan');
		
		foreach ($names as $name)
		{
			$to = new ConsumerType;
			$to->setName($name);
			$em->persist($to);
		}
		
		$em->flush();
	}
}