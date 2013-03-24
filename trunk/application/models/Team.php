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
     * @ManyToOne(targetEntity="Course", inversedBy="teams")
     * @JoinColumn(name="course", referencedColumnName="id", onDelete="RESTRICT")
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
     * @ManyToOne(targetEntity="Person", inversedBy="teams_host")
     * @JoinColumn(name="host_person", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     *
     * @var Person|int
     */
    private $host_person;
    
    /**
     * Partner
     *
     * @ManyToOne(targetEntity="Person", inversedBy="teams_partner")
     * @JoinColumn(name="partner_person", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     *
     * @var Person|int
     */
    private $partner_person;
    
    /**
     * Veranstaltung
     * 
     * @ManyToOne(targetEntity="Event", inversedBy="teams")
     * @JoinColumn(name="event", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     *
     * @var Event|int
     */
    private $event;
}