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
        $this->dataBase = Database::getInstance();
        $this->bdd = $this->dataBase->getConnection();
    }
    
    public function resetInfos(string $pseudo, string $pass):bool//requête pour choisir son pseudo et son mot de passe en tant qu'admin
    {
        $req = $this->bdd->prepare('UPDATE users set pseudo = :newPseudo, pass = :newPass WHERE id = 1');
        return $req->execute([
        'newPseudo' => $pseudo, 
        'newPass' => $pass]);
    }

    public function testInfos(string $pseudo):array//requête pour tester les infos lors d'une tentative de connection
    {
        $req = $this->bdd->prepare('SELECT id, pseudo, pass FROM users WHERE pseudo = :testPseudo');
        $req->execute([
            'testPseudo' => $pseudo]);
            return $req->fetchALL(PDO::FETCH_OBJ);
    }
}