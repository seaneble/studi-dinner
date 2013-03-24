<?php

namespace application\models;

/**
 * Gang-Datensatz
 * 
 * Eine Veranstaltung besteht aus mehreren G�ngen oder Gerichten, die
 * wechselseitig zubereitet werden.
 * 
 * @Entity
 * @Table(name="course")
 * 
 * @copyright 2013 DHBW StuV Stuttgart
 * @author    Benjamin Mannal <benjamin.mannal@gmail.com>
 * @version   $Id$
 */
class Course {
	/**
	 * Eindeutige laufende Nummer des Gangs
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
	 * @Column(type="integer")
	 *
	 * @var int
	 */
	private $sequence;

	/**
	 * Teams, die einem Gang zugeordnet sind
	 *
	 * @OneToMany(targetEntity="Team", mappedBy="course")
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
	 * @return the int
	 */
	public function getSequence()
	{
		return $this->sequence;
	}

	/**
	 * @param  $sequence
	 */
	public function setSequence($sequence)
	{
		$this->sequence = $sequence;
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
