<?php
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Entities\BedOption;

class BedOptions implements FixtureInterface
{	
	public function load($em)
	{
		$datas = array();

		// 2012
		$datas[] = array('name' => 'Eget Tält', 'price' => 0, 'available' => true);
		$datas[] = array('name' => 'Säng', 'price' => 100, 'available' => true);

		foreach ($datas as $data)
		{
			$o = new BedOption;
			$o->setName($data['name']);
			$o->setPrice($data['price']);
			$o->setAvailable($data['available']);
			$em->persist($o);
		}
		
		$em->flush();
	}
}