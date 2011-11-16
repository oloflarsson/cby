<?php

namespace Entities;

/** @Entity @Table(name="cby_person") */
class Person
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
	public function getId() { return $this->id; }
	
	/** @Column(type="string", length=255) */
	private $firstname;
	public function getFirstname() { return $this->firstname; }
	public function setFirstname($val) { $this->firstname = $val; }
	
	/** @Column(type="string", length=255) */
	private $nick;
	public function getNick() { return $this->nick; }
	public function setNick($val) { $this->nick = $val; }

	/** @Column(type="string", length=255) */
	private $lastname;
	public function getLastname() { return $this->lastname; }
	public function setLastname($val) { $this->lastname = $val; }
}