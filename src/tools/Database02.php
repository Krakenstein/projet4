<?php
declare(strict_types=1);

namespace Projet4\Tools;

use \PDO ;

class Database {

    private static $instance = null;
    
    private $dbName;
    private $dbUser;
    private $dbPass;
    private $dbHost;
    private $bdd;

    public function __construct($dbName = 'blogbdd', $dbUser = 'root', $dbPass = '', $dbHost = 'localhost')
    {
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->dbHost = $dbHost;
    }

    public function dbConnect():PDO
    {
        if($this->bdd === null){
            $bdd = new PDO("mysql:host=$this->dbHost; dbname=$this->dbName;charset=utf8", $this->dbUser, $this->dbPass);
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            $this->bdd = $bdd;
            var_dump('database');
        }
        return $this->bdd;
        var_dump('database2');
    }

    public static function getInstance()
    {
        if (self::$instance == null)
        {
        self::$instance = new Database();
        }
    
        return self::$instance;
    }

}