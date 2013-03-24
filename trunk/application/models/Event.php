<?php

namespace application\models;

/**
 * Team-Datensatz
 * 
 * Eine lange Beschreibung
 * 
 * @Entity
 * @Table(name="event")
 * 
 * @copyright 2013 DHBW StuV Stuttgart
 * @author    Benjamin Mannal <benjamin.mannal@gmail.com>
 * @version   $Event$
 */
class Event {
	/**
	 * Eindeutige laufende Nummer eines Events
	 * 
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="IDENTITY")
	 *
	 * @var int
	 */
	private $id;

	/**
	 * @Column(type="string", length=255)
	 *
	 * @var string
	 */
	private $name;

	/**
	 * @Column(type="date")
	 * 
	 * @var DateTime
	 */
	private $date;

	/**
	 * Teams, die einem Event zugeordnet sind
	 * 
	 * @OneToMany(targetEntity="Team", mappedBy="event")
	 * 
	 * @var Team[]
	 */
	private $teams;

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
	 * @return the DateTime
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * @param  $date
	 */
	public function setDate($date)
	{
		$this->date = $date;
		return $this;
	}

	/**
	 * @return the Team[]
	 */
	public function getTeams()
	{
		return $this->teams;
	}

	/**
	 * @param  $teams
	 */
	public function setTeams($teams)
	{
		$this->teams = $teams;
		return $this;
	}

}
