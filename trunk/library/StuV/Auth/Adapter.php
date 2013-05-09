<?php

namespace StuV\Auth;
/**
 * @see Zend_Auth_Adapter_Interface
 */
require_once 'Zend/Auth/Adapter/Interface.php';

/**
 * @see Zend_Auth_Result
 */
require_once 'Zend/Auth/Result.php';

/**
 * Adapter für die Ermittelung korrekter Benutzer-Angaben
 * 
 * Der Adapter prüft mit Doctrine, ob in der Datenbank ein Benutzer existiert,
 * der zu den Angaben passt. Das Passwort wird dazu gehasht und anschließend verglichen.
 */
class Adapter implements \Zend_Auth_Adapter_Interface
{
    
    const NOT_FOUND_MESSAGE = "Kein passender Benutzer gefunden";
    const BAD_PW_MESSAGE = "Ungültiges Passwort angegeben";
    
    /**
     * Model-Instanz der Personentabelle
     *
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $doctrineRepository;
    
    /**
     * Datensatz des angeforderten Benutzers
     * 
     * @var Daywork_Model_Person
     */
    protected $user;
    
    /**
     * Angegebener Benutzername
     * 
     * @var string
     */
    protected $username;
    
    /**
     * Angegebenes Passwort
     * 
     * @var string
     */
    protected $password;
    
    /**
     * Initialisiert Anmeldeinformationen
     *
     * @param \Doctrine\ORM\EntityRepository $doctrineRepository
     * @param string $username
     * @param string $password
     */
    public function __construct($doctrineRepository, $username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->doctrineRepository = $doctrineRepository;
    }
    
    /**
     * Versucht, einen Benutzer zu authentifizieren
     * 
     * Die Methode versucht, über das Model in der Datenbank einen passenden
     * Benutzerdatensatz zu finden. Dabei müssen die Benutzerinformationen
     * bereits über den Konstruktor angegeben worden sein.
     * Das Passwort wird gehasht, bevor es mit dem Wert der Datenbank
     * verglichen wird.
     *
     * @throws \Zend_Auth_Adapter_Exception if answering the authentication query is impossible
     * @return \Zend_Auth_Result
     */
    public function authenticate()
    {
        $this->user = $this->doctrineRepository
                           ->findOneByEmail($this->username);
        
        if ($this->user)
        {
            if (
                $this->user->getPassword() == 
                $this->encryptPassword($this->password)
            )
            {
                return $this->result(\Zend_Auth_Result::SUCCESS);
            }
            else
            {
                return $this->result(
                    \Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
                    self::BAD_PW_MESSAGE
                );
            }
        }
        else
        {
            return $this->result(
                \Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
                self::NOT_FOUND_MESSAGE
            );
        }
    }
    
    /**
     * Passwort verschlüsseln
     * 
     * Das Passwort wird mit dem SHA gehasht und vorher mit einem Salt
     * versehen, der in der Konfiguration über "daywork.hashSalt" festgelegt
     * wird.
     * 
     * @param string $password
     * @return string
     */
    public static function encryptPassword($password)
    {
        $config = \Zend_Registry::get('config');
        return hash(
            'sha256',
            $password . $config->hashSalt
        );
    }
    
    /**
     * Factory für Authentifikations-Resultate
     * 
     * @param integer  $code     HTTP-Fehlercode
     * @param string[] $messages Optionale Fehlermeldungen
     * @return \Zend_Auth_Result
     */
    protected function result($code, $messages = array())
    {
        if (!is_array($messages))
        {
            $messages = (array) $messages;
        }
        
        return new \Zend_Auth_Result($code, $this->user, $messages);
    }
    
    public function getUser()
    {
	    return $this->user;
    }
    
}
