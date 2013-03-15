<?php 

namespace application\models;

/**
 * Personen-Datensatz
 *
 * Diese Datei enthaelt die ....
 *
 * @Entity
 * @Table(name="person")
 *
 * @copyright 2013 DHBW StuV Stuttgart
 * @author    Matthias Holetzko <mholetzko@gmx.net>
 * @version   $Id$
 */

class Person
{
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
	 * @Column(type="string", length=255)
	 *
	 * @var string
	 */
	private $firstname;
	
	/**
	 * @Column(type="string", length=255)
	 *
	 * @var string
	 */
	private $lastname;
	
	/**
	 * @Column(type="string", length=255)
	 *
	 * @var string
	 */
	private $city;
	
	/**
	 * @Column(type="string", length=255)
	 *
	 * @var string
	 */
	private $street;
	
	/**
     * Hausnummer
     * 
     * String aufgrund eventuell wechselnder Zusammensetzung von Hausnummern 
     * und Adresszusätzen in Form von Buchstaben/Text
     * 
     * @Column(type="string", length=10)
     * 
     * @var string
     */
	private $street_nr;
	
	/**
	 * @Column(type="text")
	 *
	 * @var string
	 */
	private $address_specifications;
	
	/**
	 * @Column(type="string", length=255)
	 *
	 * @var string
	 */
	private $password;
	
	/**
     * Telefonnummer
     * 
     * String aufgrund eventuell wechselnder Zusammensetzung von Telefonnummern 
     * und Zusaetzen in Form von Sonderzeichen
     * 
     * @Column(type="string", length=30)
     * 
     * @var string
     */
	private $phone_number;
	
	/**
	 * @Column(type="string", length=255)
	 *
	 * @var string
	 */
	private $e_mail;
}
?>