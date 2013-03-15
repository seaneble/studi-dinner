<?php

/**
 * @Entity
 * @Table(name="team")
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
     * @Column(type="string", length=255, nullable=true)
     * 
     * @var string
     */
    private $password;
    
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
}