<?php

/**
* @Entity
* @Table(name="event")
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