<?php 

namespace application\models;

/**
 * Personen-Datensatz
 *
 * Eine Person ist ein Teilnehmer einer Veranstaltung. Es werden sowohl die
 * Login-Daten als auch persönliche Informationen zum Ort der Küche und Essens-
 * Vorlieben gespeichert.
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
	 * Eindeutige laufende Nummer der Person
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
	private $address_street;
	
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
	private $address_number;
	
	/**
     * Postleitzahl
     * 
     * @Column(type="integer")
     * 
     * @var int
     */
	private $address_zip;
	
	/**
	 * @Column(type="string", length=255)
	 *
	 * @var string
	 */
	private $address_city;
	
	/**
	 * Angaben zur Adresse
	 * 
	 * Denkbar sind Angaben wie „Durch den Hinterhof“, „blaues Klingelschild“, etc.
	 * 
	 * @Column(type="text")
	 *
	 * @var string
	 */
	private $address_details;
	
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
     * und Zusätzen in Form von Sonderzeichen
     * 
     * @Column(type="string", length=30)
     * 
     * @var string
     */
	private $phone;
	
	/**
	 * @Column(type="string", length=255)
	 *
	 * @var string
	 */
	private $email;
}