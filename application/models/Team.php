<?php

namespace application\models;

/**
 * Team-Datensatz
 * 
 * Eine lange Beschreibung
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
}