<?php

namespace application\models;

/**
 * Abneigungen
 * 
 * Zutaten und Inhaltsstoffe gegen die eine Person allergisch ist,
 * oder die sie aus ethischen bzw. religösen Gründen nicht verzehren darf und will.
 * 
 * @Entity
 * @Table(
 * 	   name="ingredient",
 *     uniqueConstraints={@UniqueConstraint(name="ingredient_name_unique", columns={"name"})}
 * )
 * 
 * @copyright 2013 DHBW StuV Stuttgart
 * @author    Benjamin Mannal <benjamin.mannal@gmail.com>
 * @version   $Id$
 */

class Ingredient
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

}