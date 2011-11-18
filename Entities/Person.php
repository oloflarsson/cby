<?php

namespace Entities;

/** @Entity @Table(name="person") */
class Person
{
	public function __construct()
	{
		$this->created = new \DateTime("now");
		$this->ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	}
	
	public function setFromArray($array)
	{
		global $em;
		foreach ($array as $key => $value)
		{
			if ($key == 'tshirttype')
			{
				$value = $em->find('Entities\TShirtType', $value);
			}
			else if ($key == 'consumertype')
			{
				$value = $em->find('Entities\ConsumerType', $value);
			}
			
			$this->{'set'.ucfirst($key)}($value);
		}
	}

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
	public function getId() { return $this->id; }
	
	/** @Column(type="datetime") */
    private $created;
	public function getCreated() { return $this->created; }
	
	/** @Column(type="string", length=255) */
	private $ip;
	public function getIp() { return $this->ip; }
	public function setIp($val) { $this->ip = $val; }
	
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
	
	/** @Column(type="string", length=12) */
	private $ssn;
	public function getSsn() { return $this->ssn; }
	public function setSsn($val) { $this->ssn = (int)$val; }
	
	/** @Column(type="string", length=255) */
	private $email;
	public function getEmail() { return $this->email; }
	public function setEmail($val) { $this->email = $val; }
	
	/** @Column(type="string", length=255) */
	private $phone;
	public function getPhone() { return $this->phone; }
	public function setPhone($val) { $this->phone = $val; }
	
	/** @Column(type="string", length=255) */
	private $comment;
	public function getComment() { return $this->comment; }
	public function setComment($val) { $this->comment = $val; }
	
	/** @Column(type="string", length=255) */
	private $allergycomment;
	public function getAllergycomment() { return $this->allergycomment; }
	public function setAllergycomment($val) { $this->allergycomment = $val; }
	
	// Omnivore, Vegetarian, Vegan
	/** @ManyToOne(targetEntity="ConsumerType") */
    private $consumertype;
	public function getConsumerType() { return $this->consumertype; }
	public function setConsumerType(ConsumerType $val) { $this->consumertype = $val; }
	
	// XS, S, M, L, XL, XXL
	/** @ManyToOne(targetEntity="TShirtType") */
    private $tshirttype;
	public function getTShirtType() { return $this->tshirttype; }
	public function setTShirtType(TShirtType $val) { $this->tshirttype = $val; }
}