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
class Course
{
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
}