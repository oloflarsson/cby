<?php
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Entities\TicketOption;

class TicketOptions implements FixtureInterface
{	
	public function load($em)
	{
		$datas = array();
		$datas[] = array('name' => 'Vanlig', 'price' => 400, 'available' => true);
		$datas[] = array('name' => 'Sen', 'price' => 500, 'available' => false);
		
		foreach ($datas as $data)
		{
			$o = new TicketOption;
			$o->setName($data['name']);
			$o->setPrice($data['price']);
			$o->setAvailable($data['available']);
			$em->persist($o);
		}
		
		$em->flush();
	}
}