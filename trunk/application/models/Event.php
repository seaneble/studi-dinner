<?php

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
class Event
{
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
	
}