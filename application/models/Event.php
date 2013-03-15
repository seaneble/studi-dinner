<?php

namespace application\models;

/**
 * Event-Datensatz
 * 
 * Jede Kombination aus Teams wird einer Veranstaltung zugeordnet. Im Rahmen
 * verschieder Veranstaltungen können Personen in unterschiedlichen Teams
 * zusammenkommen.
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
	 * Name der Veranstaltung für interne Referenz-Zwecke
	 * 
	 * @Column(type="string", length=255, unique=true)
	 *
	 * @var string
	 */
	private $name;
	
	/**
	 * Datum der Veranstaltung für interne Referenz-Zwecke
	 * 
	 * @Column(type="date")
	 * 
	 * @var DateTime
	 */
	private $date;
}