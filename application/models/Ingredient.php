<?php

namespace application\models;

/**
 * Abneigungen
 * 
 * Zutaten und Inhaltsstoffe gegen die eine Person allergisch ist,
 * oder die sie aus ethischen bzw. religösen Gründen nicht verzehren darf und will.
 * 
 * @Entity
 * @Table(name="ingredient")
 * 
 * @copyright 2013 DHBW StuV Stuttgart
 * @author    Benjamin Mannal <benjamin.mannal@gmail.com>
 * @version   $Id$
 */
class Ingredients
{
	/**
	 * Eindeutige laufende Nummer der Abneigungen
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
}