<?php
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Entities\TShirtType;

class TShirtOptions implements FixtureInterface
{	
	public function load($em)
	{
		$name_prices = array(
			'ingen' => 0,
			'XS'    => 150,
			'S'     => 150,
			'M'     => 150,
			'L'     => 150,
			'XL'    => 150,
			'XXL'   => 150,
		);
		
		foreach ($name_prices as $name => $price)
		{
			$to = new TShirtType;
			$to->setName($name);
			$to->setPrice($price);
			$em->persist($to);
		}
		
		$em->flush();
	}
}