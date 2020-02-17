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

    public function testInfos($pseudo)//requête pour obtenir le mdp haché
    {
        $req = $this->bdd->prepare('SELECT id, pseudo, pass FROM users WHERE pseudo = :testPseudo');
        $req->execute([
            'testPseudo' => $pseudo]);
        $infos = $req->fetch();
        return $infos;
    }
}