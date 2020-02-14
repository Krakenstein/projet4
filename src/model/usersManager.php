<?php
declare(strict_types=1);

namespace Projet4\Model;

use \PDO ;
use Projet4\Tools\Database;

class UsersManager 
{
    private $dataBase;
    private $bdd;
   
    public function __construct()
    {
        $this->dataBase = new Database();
        $this->bdd = $this->dataBase->dbConnect();
    }
    
    public function resetInfos($pseudo, $pass)//requête pour choisir son pseudo et son mot de passe en tant qu'admin
    {
        $req = $this->bdd->prepare('UPDATE users set pseudo = :newPseudo, pass = :newPass WHERE id = 1');
        $infos = $req->execute(array(
        'newPseudo' => $pseudo, 
        'newPass' => $pass));
        return $infos;
    }


    public function testInfos($pseudo, $pass)//requête pour vérifier les identifiants qd on essaie de se connecter à la bdd
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) FROM users WHERE pseudo = :newPseudo, pass = :newPass AND id = 1');
        $infos = $req->execute(array(
        'newPseudo' => $pseudo, 
        'newPass' => $pass));
        return $infos;
    }

    public function getHash()//requête pour obtenir le mdp haché
    {
        $req = $this->bdd->prepare('SELECT pass FROM users WHERE id = 1');
        $req->execute(array());
        $hash = $req->fetch();
        return $hash;
    }

    public function getPseudo()//requête pour obtenir le mdp haché
    {
        $req = $this->bdd->prepare('SELECT pseudo FROM users WHERE id = 1');
        $req->execute(array());
        $pseudRegister = $req->fetch();
        return $pseudRegister;
    }
}