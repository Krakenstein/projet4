<?php
declare(strict_types=1);

namespace Projet4\Tools;

use \PDO ;

class Database {

    private $dbName;
    private $dbUser;
    private $dbPass;
    private $dbHost;
    private $bdd;

    public function __construct($dbName = 'dbs307088', $dbUser = 'dbu563302', $dbPass = 'Akira99!T3tsuo', $dbHost = 'db5000314632.hosting-data.io')
    {
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->dbHost = $dbHost;
    }

    public function dbConnect():PDO
    {
        if($this->bdd === null){
            $bdd = new PDO("mysql:host=$this->dbHost; dbname=$this->dbName;", $this->dbUser, $this->dbPass);
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            $this->bdd = $bdd;
        }
        return $this->bdd;
    }


}