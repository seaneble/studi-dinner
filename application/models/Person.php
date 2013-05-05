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
class Person {
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

	/**
	 * Teams, in denen diese Person als Gastgeber Mitglied ist
	 *
	 * @OneToMany(targetEntity="Team", mappedBy="host_person")
	 *
	 * @var Team[]
	 */
	
	private $teams_host;

	/**
	 * @Column(type="string", length=255)
	 *
	 * @var string
	 */
	private $password;
	
	/**
	 * Teams, in denen diese Person als Partner Mitglied ist
	 *
	 * @OneToMany(targetEntity="Team", mappedBy="partner_person")
	 *
	 * @var Team[]
	 */
	private $teams_partner;

	/**
	 * Zutaten und Abneigungen
	 * 
	 * @ManyToMany(targetEntity="Ingredient")
	 * @JoinTable(
	 *     name="person_dislikes",
	 *     joinColumns={
	 *         @JoinColumn(name="person_id", referencedColumnName="id")
	 *     },
	 *     inverseJoinColumns={
	 *         @JoinColumn(name="ingredient_id", referencedColumnName="id")
	 *     }
	 * )
	 * 
	 * @var Ingredient[]|int
	 */
	private $ingredients;
	
	/**
	 * @Column(type="boolean")
	 *
	 * @var Boolean
	 */
	private $active;
	
	/**
	 * @Column(type="string")
	 *
	 * @var string
	 */
	private $token;
	
	public function __construct()
	{
		$this->ingredients   = new \Doctrine\Common\Collections\ArrayCollection();
		$this->teams_partner = new \Doctrine\Common\Collections\ArrayCollection();
		$this->teams_partner = new \Doctrine\Common\Collections\ArrayCollection();
	}

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
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
	 * @param  $firstname
	 */
	public function setFirstname($firstname)
	{
		$this->firstname = $firstname;
		return $this;
	}

	/**
	 * @return the string
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * @param  $lastname
	 */
	public function setLastname($lastname)
	{
		$this->lastname = $lastname;
		return $this;
	}

	/**
	 * @return the string
	 */
	public function getAddressStreet()
	{
		return $this->address_street;
	}

	/**
	 * @param  $address_street
	 */
	public function setAddressStreet($address_street)
	{
		$this->address_street = $address_street;
		return $this;
	}

	/**
	 * @return the string
	 */
	public function getAddressNumber()
	{
		return $this->address_number;
	}

	/**
	 * @param  $address_number
	 */
	public function setAddressNumber($address_number)
	{
		$this->address_number = $address_number;
		return $this;
	}

	/**
	 * @return the int
	 */
	public function getAddressZip()
	{
		return $this->address_zip;
	}

	/**
	 * @param  $address_zip
	 */
	public function setAddressZip($address_zip)
	{
		$this->address_zip = $address_zip;
		return $this;
	}

	/**
	 * @return the string
	 */
	public function getAddressCity()
	{
		return $this->address_city;
	}

	/**
	 * @param  $address_city
	 */
	public function setAddressCity($address_city)
	{
		$this->address_city = $address_city;
		return $this;
	}

	/**
	 * @return the string
	 */
	public function getAddressDetails()
	{
		return $this->address_details;
	}

	/**
	 * @param  $address_details
	 */
	public function setAddressDetails($address_details)
	{
		$this->address_details = $address_details;
		return $this;
	}

	/**
	 * @return the string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param  $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @return the string
	 */
	public function getPhone()
	{
		return $this->phone;
	}

	/**
	 * @param  $phone
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone;
		return $this;
	}

	/**
	 * @return the string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param  $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		return $this;
	}

	/**
	 * @return the Team[]
	 */
	public function getTeamsHost()
	{
		return $this->teams_host;
	}

	/**
	 * @param  $teams_host
	 */
	public function setTeamsHost($teams_host)
	{
		$this->teams_host = $teams_host;
		return $this;
	}

	/**
	 * @return the Team[]
	 */
	public function getTeamsPartner()
	{
		return $this->teams_partner;
	}

	/**
	 * @param  $teams_partner
	 */
	public function setTeamsPartner($teams_partner)
	{
		$this->teams_partner = $teams_partner;
		return $this;
	}

	/**
	 * @return the Ingredient[]
	 */
	public function getIngredients()
	{
		return $this->ingredients;
	}

	/**
	 * @param unknown_type $ingredients
	 */
	public function setIngredients($ingredients)
	{
		$this->ingredients = $ingredients;
		return $this;
	}

	/**
	 * @return the Boolean
	 */
	public function getActive() 
	{
		return $this->active;
	}
	
	/**
	 * @param  $active
	 */
	public function setActive($active) 
	{
		$this->active = $active;
		return $this;
	}

	public function getToken() 
	{
		return $this->token;
	}
	
	public function setToken($token) 
	{
		$this->token = $token;
		return $this;
	}
	
	

}
