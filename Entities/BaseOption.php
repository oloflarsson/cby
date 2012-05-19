<?php

namespace Entities;

/** @MappedSuperclass */
class BaseOption
{
	public function __construct()
	{
		$this->available = true;
	}

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
	public function getId() { return $this->id; }
	
	/** @Column(type="boolean") */
    private $available;
	public function setAvailable($val) { $this->available = $val; }
	public function getAvailable() { return $this->available; }
	
	/** @Column(type="string", length=255) */
	private $name;
	public function getName() { return $this->name; }
	public function setName($val) { $this->name = $val; }
	
	/** @Column(type="integer", length=3) */
	private $price;
	public function getPrice() { return $this->price; }
	public function setPrice($val) { $this->price = $val; }
	
	public function getDesc()
	{
		$ret = $this->name;
		if ($this->price != 0)
		{
			$ret .= ' - ' . $this->price . 'kr';
		}
		if ( ! $this->available)
		{
			$ret .= ' (ej tillgänglig)';
		}
		return $ret;
	}
}