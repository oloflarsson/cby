<?php

namespace Entities;

/** @Entity @Table(name="tshirttype") */
class TShirtType
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
	public function getId() { return $this->id; }
	
	/** @Column(type="string", length=255) */
	private $name;
	public function getName() { return $this->name; }
	public function setName($val) { $this->name = $val; }
	
	/** @Column(type="integer", length=3) */
	private $price;
	public function getPrice() { return $this->price; }
	public function setPrice($val) { $this->price = $val; }
}