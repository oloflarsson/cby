<?php
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Entities\TicketOption;

class TicketOptions implements FixtureInterface
{	
	public function load($em)
	{
		$name_prices = array(
			'Vanlig' => 430,
			'Sen'  => 500,
		);
		
		foreach ($name_prices as $name => $price)
		{
			$o = new TicketOption;
			$o->setName($name);
			$o->setPrice($price);
			$em->persist($o);
		}
		
		$em->flush();
	}
}