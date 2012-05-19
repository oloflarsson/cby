<?php

namespace Entities;

/** @Entity @Table(name="person", uniqueConstraints={@UniqueConstraint(name="code_unique",columns={"code"})}) */
class Person
{
	public function __construct()
	{
		global $em;
		$this->created = new \DateTime("now");
		$this->ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$this->code = self::genCode();
		$this->amountpaid = 0;
		$this->setCheckedIn(2);
		$this->setReceivedShirtOption(2);
		$this->setReceivedOilOption(2);
		$this->setReceivedGuardianOption(2);
		$this->setTicketOption(1);
	}
	
	public function setFromArray($array)
	{
		global $em;
		foreach ($array as $key => $value)
		{
			$this->{'set'.$key}($value);
		}
	}
	
	public function setReceivedDefaults()
	{
		if ($this->getShirtOption()->getId() == 1)
		{
			$this->setReceivedShirtOption(3);
		}
		if ($this->getOilOption()->getId() == 1)
		{
			$this->setReceivedOilOption(3);
		}
		if ($this->isAdult())
		{
			$this->setReceivedGuardianOption(3);
		}
	}
	
	public function getStatusLink()
	{
		return site_url('/status?code='.$this->getCode());
	}
	public function descStatusLink()
	{
		return '<a href="' . $this->getStatusLink() . '"><img src="' . plugins_url() . '/cby/img/fff/zoom.png"/></a>';
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
	public function descCreated() { return self::desc_datetime($this->created); }
	
	/** @Column(type="string", length=255) */
	private $ip;
	public function setIp($val) { $this->ip = $val; }
	public function getIp() { return $this->ip; }
	public function descIp() { return $this->ip; }
	
	/** @Column(type="string", length=255) */
	private $code;
	public function setCode($val) { $this->code = $val; }
	public function getCode() { return $this->code; }
	public function descCode() { return $this->code; }
	
	/** @ManyToOne(targetEntity="ReceivedOption") */
    private $checkedin;
	public function setCheckedIn($val) { $this->setOption('checkedin', $val, 'ReceivedOption'); }
	public function getCheckedIn() { return $this->checkedin; }
	public function descCheckedIn() { return $this->checkedin->getDesc(); }
	
	/** @ManyToOne(targetEntity="ReceivedOption") */
    private $receivedshirtoption;
	public function setReceivedShirtOption($val) { $this->setOption('receivedshirtoption', $val, 'ReceivedOption'); }
	public function getReceivedShirtOption() { return $this->shirtreceived; }
	public function descReceivedShirtOption() { return $this->receivedshirtoption->getDesc(); }
	
	/** @ManyToOne(targetEntity="ReceivedOption") */
    private $receivedoiloption;
	public function setReceivedOilOption($val) { $this->setOption('receivedoiloption', $val, 'ReceivedOption'); }
	public function getReceivedOilOption() { return $this->receivedoiloption; }
	public function descReceivedOilOption() { return $this->receivedoiloption->getDesc(); }
	
	/** @ManyToOne(targetEntity="ReceivedOption") */
    private $receivedguardianoption;
	public function setReceivedGuardianOption($val) { $this->setOption('receivedguardianoption', $val, 'ReceivedOption'); }
	public function getReceivedGuardianOption() { return $this->receivedguardianoption; }
	public function descReceivedGuardianOption() { return $this->receivedguardianoption->getDesc(); }
	
	/** @Column(type="string", length=255) */
	private $firstname;
	public function setFirstname($val) { $this->firstname = $val; }
	public function getFirstname() { return $this->firstname; }
	public function descFirstname() { return $this->firstname; }
	
	/** @Column(type="string", length=255) */
	private $nick;
	public function setNick($val) { $this->nick = $val; }
	public function getNick() { return $this->nick; }
	public function descNick() { return $this->nick; }

	/** @Column(type="string", length=255) */
	private $lastname;
	public function setLastname($val) { $this->lastname = $val; }
	public function getLastname() { return $this->lastname; }
	public function descLastname() { return $this->lastname; }
	
	public function getFullName()
	{
		$ret = '';
		$ret .= $this->getFirstname();
		if (strlen($this->getNick()))
		{
			$ret .= ' "';
			$ret .= $this->getNick();
			$ret .= '"';
		}
		$ret .= ' ';
		$ret .= $this->getLastname();
		return $ret;
	}
	
	/** @Column(type="string", length=12) */
	private $ssn;
	public function setSsn($val) { $this->ssn = (int)$val; }
	public function getSsn() { return $this->ssn; }
	public function descSsn() { return $this->ssn; }
	
	public function getAgeInYears()
	{
		global $cbyconf;
		$ssnDateTime = \DateTime::createFromFormat('Ymd????', $this->getSsn());
		$startDateTime = \DateTime::createFromFormat('Y-m-d', $cbyconf['campstart']);
		$interval = $ssnDateTime->diff($startDateTime);
		return $interval->y;
	}
	
	public function isAdult()
	{
		return $this->getAgeInYears() >= 18;
	}
	
	/** @Column(type="string", length=255) */
	private $email;
	public function setEmail($val) { $this->email = $val; }
	public function getEmail() { return $this->email; }
	public function descEmail() { return $this->email; }
	
	/** @Column(type="string", length=255) */
	private $phone;
	public function setPhone($val) { $this->phone = $val; }
	public function getPhone() { return $this->phone; }
	public function descPhone() { return $this->phone; }
	
	/** @ManyToOne(targetEntity="TicketOption") */
    private $ticketoption;
	public function setTicketOption($val) { $this->setOption('ticketoption', $val, 'TicketOption'); }
	public function getTicketOption() { return $this->ticketoption; }
	public function descTicketOption() { return $this->ticketoption->getDesc(); }
	
	/** @ManyToOne(targetEntity="BedOption") */
    private $bedoption;
	public function setBedOption($val) { $this->setOption('bedoption', $val, 'BedOption'); }
	public function getBedOption() { return $this->bedoption; }
	public function descBedOption() { return $this->bedoption->getDesc(); }

	/** @ManyToOne(targetEntity="ShirtOption") */
    private $shirtoption;
	public function setShirtOption($val) { $this->setOption('shirtoption', $val, 'ShirtOption'); }
	public function getShirtOption() { return $this->shirtoption; }
	public function descShirtOption() { return $this->shirtoption->getDesc(); }
	
	/** @ManyToOne(targetEntity="OilOption") */
    private $oiloption;
	public function setOilOption($val) { $this->setOption('oiloption', $val, 'OilOption'); }
	public function getOilOption() { return $this->oiloption; }
	public function descOilOption() { return $this->oiloption->getDesc(); }
	
	/** @Column(type="datetime", nullable="true") */
    private $datepaid;
	public function setDatePaid($val) { $this->datepaid = self::parse_datetime($val); }
	public function getDatePaid() { return $this->datepaid; }
	public function descDatePaid() { return self::desc_datetime($this->datepaid); }
	
	/** @Column(type="integer") */
    private $amountpaid;
	public function setAmountPaid($val) { $this->amountpaid = $val; }
	public function getAmountPaid() { return $this->amountpaid; }
	public function descAmountPaid() { return $this->amountpaid; }
	
	public function getPriceSum() { return $this->getTicketOption()->getPrice() + $this->getConsumerOption()->getPrice() + $this->getShirtOption()->getPrice() + $this->getBedOption()->getPrice() + $this->getOilOption()->getPrice(); }
	public function descPriceSum() { return $this->getPriceSum(); }
	
	public function hasPaidEnough()
	{
		return $this->getAmountPaid() >= $this->getPriceSum();
	}
	
	/** @ManyToOne(targetEntity="ConsumerOption") */
    private $consumeroption;
	public function setConsumerOption($val) { $this->setOption('consumeroption', $val, 'ConsumerOption'); }
	public function getConsumerOption() { return $this->consumeroption; }
	public function descConsumerOption() { return $this->consumeroption->getDesc(); }
	
	/** @Column(type="string", length=255) */
	private $allergycomment;
	public function setAllergycomment($val) { $this->allergycomment = $val; }
	public function getAllergycomment() { return $this->allergycomment; }
	public function descAllergycomment() { return $this->allergycomment; }
	
	/** @Column(type="string", length=255) */
	private $comment;
	public function setComment($val) { $this->comment = $val; }
	public function getComment() { return $this->comment; }
	public function descComment() { return $this->comment; }
	
	public function getPriceLines()
	{
		$ret = array();
		$ret[$this->getTicketOption()->getName() . ' biljett'] = $this->getTicketOption()->getPrice();
		$ret[$this->getBedOption()->getName() . ' (sovplats)'] = $this->getBedOption()->getPrice();
		$ret[$this->getShirtOption()->getName() . ' T-Shirt'] = $this->getShirtOption()->getPrice();
		$ret[$this->getOilOption()->getName() . ' lampolja'] = $this->getOilOption()->getPrice();
		$ret[$this->getConsumerOption()->getName() . '-mat'] = $this->getConsumerOption()->getPrice();
		$ret['Totalt'] = $this->getPriceSum();
		return $ret;
	}
	
	protected function setOption($field, $val, $option)
	{
		global $em;
		if ( ! is_a($val, $option))
		{
			$val = $em->find('Entities\\'.$option, $val);
		}
		$this->$field = $val;
	}
	
	/*protected static function desc_boolean($bool)
	{
		if ($bool) return "Japp :D";
		return "Nein :(";
	}*/
	
	protected static function desc_datetime($datetime)
	{
		if ( ! $datetime) return '';
		return $datetime->format('Y-m-d H:i');
	}
	protected static function parse_datetime($o)
	{
		if ($o instanceof \DateTime) return $o;
		if (strpos($o, '_') !== false) return null;
		if ( ! $o) return null;
		if (strlen($o) == 0) return null;
		return date_create($o);
	}
	
	public static function genCode()
	{
		global $em;
		$code = null;
		while ($code == null || $em->getRepository('Entities\\Person')->findByCode($code))
		{
			$code = \CBY::gencode(5);
		}
		return $code;
	}
}