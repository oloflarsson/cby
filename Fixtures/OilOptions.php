<?php
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Entities\OilOption;

class OilOptions implements FixtureInterface
{
	public function load($em)
	{
		$literprice = 25;
		$name_prices = array(
			'0 liter'    =>  0 * $literprice,
			'1 liter'    =>  1 * $literprice,
			'2 liter'    =>  2 * $literprice,
			'3 liter'    =>  3 * $literprice,
			'4 liter'    =>  4 * $literprice,
			'5 liter'    =>  5 * $literprice,
			'6 liter'    =>  6 * $literprice,
			'7 liter'    =>  7 * $literprice,
			'8 liter'    =>  8 * $literprice,
			'9 liter'    =>  9 * $literprice,
			'10 liter'   => 10 * $literprice,
		);
		
		foreach ($name_prices as $name => $price)
		{
			$o = new OilOption;
			$o->setName($name);
			$o->setPrice($price);
			$em->persist($o);
		}
		
		$em->flush();
	}
}