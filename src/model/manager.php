<?php
declare(strict_types=1);

namespace Projet4\Manager;

use \PDO ;

class Manager
{
    protected function dbConnect()
    {
        $bdd = new PDO('mysql:host=localhost;dbname=blogbdd;charset=utf8', 'root', '');
    return $bdd;
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
}