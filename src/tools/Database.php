<?php
declare(strict_types=1);

namespace Projet4\Tools;

use \PDO ;

class Database {

    private $db_name;
    private $db_user;
    private $db_pass;
    private $db_host;
    private $bdd;

    public function __construct($db_name = 'blogbdd', $db_user = 'root', $db_pass = '', $db_host = 'localhost')
    {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
    }

    public function dbConnect():PDO
    {
        if($this->bdd === null){
            $bdd = new PDO('mysql:host=localhost;dbname=blogbdd;charset=utf8', $this->db_user, $this->db_pass);
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            $this->bdd = $bdd;
        }
        return $this->bdd;
    }


}