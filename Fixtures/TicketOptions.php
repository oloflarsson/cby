<?php
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Entities\TicketOption;

class TicketOptions implements FixtureInterface
{	
	public function load($em)
	{
		$datas = array();
		// 2012
		//$datas[] = array('name' => 'Vanlig', 'price' => 400, 'available' => true);
		//$datas[] = array('name' => 'Sen', 'price' => 500, 'available' => false);

		// 2013
		//$datas[] = array('name' => 'Hel biljett, tidig', 'price' => 420, 'available' => true);
		//$datas[] = array('name' => 'Hel biljett', 'price' => 530, 'available' => false);
		//$datas[] = array('name' => 'Hel biljett, betalat på plats', 'price' => 570, 'available' => false);
		//$datas[] = array('name' => 'Tredagars, tidig', 'price' => 230, 'available' => true);
		//$datas[] = array('name' => 'Tredagars', 'price' => 270, 'available' => false);
		//$datas[] = array('name' => 'Tredagars, betalat på plats', 'price' => 300, 'available' => false);

		// 2014
		$datas[] = array('name' => 'Hel biljett, tidig', 'price' => 465, 'available' => true);
		$datas[] = array('name' => 'Hel biljett', 'price' => 530, 'available' => false);
		$datas[] = array('name' => 'Hel biljett, betalat på plats', 'price' => 600, 'available' => false);
		$datas[] = array('name' => 'Tredagars, tidig', 'price' => 320, 'available' => true);
		$datas[] = array('name' => 'Tredagars', 'price' => 380, 'available' => false);
		$datas[] = array('name' => 'Tredagars, betalat på plats', 'price' => 440, 'available' => false);
		$datas[] = array('name' => 'En dag, tidig', 'price' => 160, 'available' => true);
		$datas[] = array('name' => 'En dag', 'price' => 190, 'available' => false);
		$datas[] = array('name' => 'En dag, betalat på plats', 'price' => 220, 'available' => false);

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