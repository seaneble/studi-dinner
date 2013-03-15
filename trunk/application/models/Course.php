<?php

/**
* @Entity
* @Table(name="course")
*/
class Course
{
	/**
	 * Eindeutige laufende Nummer der Gänge
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