<?php

namespace application\models;
/**
 * Team-Datensatz
 * 
 * Ein Team besteht aus zwei Personen. Im Rahmen einer Veranstaltung ist es
 * dafür zuständig, einen Gang zuzubereiten, zu dem zwei weitere Teams
 * eingeladen sind.
 * 
 * @Entity
 * @Table(
 *     name="team",
 * 	   uniqueConstraints={
 *         @UniqueConstraint(name="name_unique", columns={"name"})
 *     }
 * )
 * 
 * @copyright 2013 DHBW StuV Stuttgart
 * @author    Sebastian Leitz <webmaster@stuv-stuttgart.de>
 * @version   $Id$
 */
class Team {
	/**
	 * Eindeutige laufende Nummer des Teams
	 * 
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="IDENTITY")
	 * 
	 * @var int
	 */
	private $id;

	/**
	 * Team-Name zur Identifizierung
	 * 
	 * Der Name muss eindeutig sein, damit bei einer Veranstaltung planlos anrufende
	 * Menschen sich eindeutig identifizieren können.
	 * 
	 * @Column(type="string", length=255, nullable=true)
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Menü-Gang, den das Team zubereiten soll
	 * 
	 * @ManyToOne(targetEntity="Course", inversedBy="teams")
	 * @JoinColumn(name="course", referencedColumnName="id", onDelete="RESTRICT")
	 * 
	 * @var Course|int
	 */
	private $course;

	/**
	 * Gast-Teams
	 * 
	 * @ManyToMany(targetEntity="Team", inversedBy="hosts")
	 * @JoinTable(
	 *     name="guests",
	 *     joinColumns={
	 *         @JoinColumn(name="host_id", referencedColumnName="id")
	 *     },
	 *     inverseJoinColumns={
	 *         @JoinColumn(name="guest_id", referencedColumnName="id")
	 *     }
	 * )
	 * 
	 * @var Team|int
	 */
	private $guests;

	/**
	 * Gastgeber-Teams
	 * 
	 * @ManyToMany(targetEntity="Team", mappedBy="guests")
	 * 
	 * @var Team|int
	 */
	private $hosts;

	/**
	 * Gastgeber
	 * 
	 * @ManyToOne(targetEntity="Person", inversedBy="teams_host")
	 * @JoinColumn(name="host_person", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
	 *
	 * @var Person|int
	 */
	private $host_person;

	/**
	 * Partner
	 *
	 * @ManyToOne(targetEntity="Person", inversedBy="teams_partner")
	 * @JoinColumn(name="partner_person", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
	 *
	 * @var Person|int
	 */
	private $partner_person;

	/**
	 * Veranstaltung
	 * 
	 * @ManyToOne(targetEntity="Event", inversedBy="teams")
	 * @JoinColumn(name="event", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
	 *
	 * @var Event|int
	 */
	private $event;
	
	/**
	 * @Column(type="boolean")
	 *
	 * @var Boolean
	 */
	private $random_partner = false;
	
	/**
	 * @Column(type="boolean")
	 *
	 * @var Boolean
	 */
	private $confirmed = false;

	public function __construct() 
	{
		$this->guests = new \Doctrine\Common\Collections\ArrayCollection();
		$this->hosts  = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	/**
	 * @return the int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param  $id
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return the string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param  $name
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return the Course
	 */
	public function getCourse() 
	{
		return $this->course;
	}

	/**
	 * @param unknown_type $course
	 */
	public function setCourse($course)
	{
		$this->course = $course;
		return $this;
	}

	/**
	 * @return the Team
	 */
	public function getGuests()
	{
		return $this->guests;
	}

	/**
	 * @param unknown_type $guests
	 */
	public function setGuests($guests)
	{
		$this->guests = $guests;
		return $this;
	}

	/**
	 * @return the Team
	 */
	public function getHosts()
	{
		return $this->hosts;
	}

	/**
	 * @param unknown_type $hosts
	 */
	public function setHosts($hosts)
	{
		$this->hosts = $hosts;
		return $this;
	}

	/**
	 * @return the Person
	 */
	public function getHostPerson()
	{
		return $this->host_person;
	}

	/**
	 * @param unknown_type $host_person
	 */
	public function setHostPerson($host_person)
	{
		$this->host_person = $host_person;
		return $this;
	}

	/**
	 * @return the Person
	 */
	public function getPartnerPerson()
	{
		return $this->partner_person;
	}

	/**
	 * @param unknown_type $partner_person
	 */
	public function setPartnerPerson($partner_person)
	{
		$this->partner_person = $partner_person;
		return $this;
	}

	/**
	 * @return the Event
	 */
	public function getEvent()
	{
		return $this->event;
	}

	/**
	 * @param unknown_type $event
	 */
	public function setEvent($event)
	{
		$this->event = $event;
		return $this;
	}

	/**
	 * @return the Boolean
	 */
	public function getRandomPartner()
	{
		return $this->random_partner;
	}
	
	/**
	 * @param  $active
	 */
	public function setRandomPartner($random_partner) 
	{
		$this->random_partner = $random_partner;
		return $this;
	}

	/**
	 * @return the Boolean
	 */
	public function getConfirmed()
	{
		return $this->confirmed;
	}
	
	/**
	 * @param  $confirmed
	 */
	public function setConfirmed($confirmed) 
	{
		$this->confirmed = $confirmed;
		return $this;
	}

}
