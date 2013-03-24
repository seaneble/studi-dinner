<?php

namespace application\models;

/**
 * Team-Datensatz
 * 
 * Ein Team besteht aus zwei Personen. Im Rahmen einer Veranstaltung ist es
 * dafür zuständig, einen Gang zuzubereiten, zu dem zwei weitere Teams
 * eingeladen sind.
 * 
 * @Entity
 * @Table(name="team")
 * 
 * @copyright 2013 DHBW StuV Stuttgart
 * @author    Sebastian Leitz <webmaster@stuv-stuttgart.de>
 * @version   $Id$
 */
class Team
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
	 * Team-Name zur Identifizierung
	 * 
	 * Der Name muss eindeutig sein, damit bei einer Veranstaltung planlos anrufende
	 * Menschen sich eindeutig identifizieren können.
	 * 
	 * @Column(type="string", length=255)
	 *
	 * @var string
	 */
	private $name;
	
	/**
     * Menü-Gang, den das Team zubereiten soll
     * 
     * HIER FEHLT DIE DOCTRINE-LOGIK ZUR REFERENZIERUNG DER TABELLE course
     * @Column(type="integer")
     * 
     * @var Course|int
     */
    private $course;
    
    /**
     * Gast-Team #1
     * 
     * HIER FEHLT DIE DOCTRINE-LOGIK ZUR REFERENZIERUNG DER TABELLE team
     * @Column(type="integer")
     * 
     * @var Team|int
     */
    private $guest1;
    
    /**
     * Gast-Team #2
     * 
     * HIER FEHLT DIE DOCTRINE-LOGIK ZUR REFERENZIERUNG DER TABELLE team
     * @Column(type="integer")
     * 
     * @var Team|int
     */
    private $guest2;

    /**
     * Gastgeber
     * 
     * HIER FEHLT DIE DOCTRINE-LOGIK ZUR REFERENZIERUNG DER TABELLE team
     * @Column(type="integer")
     *
     * @var Person|int
     */
    private $host;
    
    /**
     * Partner
     *
     * HIER FEHLT DIE DOCTRINE-LOGIK ZUR REFERENZIERUNG DER TABELLE team
     * @Column(type="integer")
     *
     * @var Person|int
     */
    private $partner;
}