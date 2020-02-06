<?php
declare(strict_types=1);

require_once("src/model/Manager.php");

class UsersManager extends manager
{
    public function resetInfos($pseudo, $pass)//requête pour choisir son pseudo et son mot de passe en tant qu'admin
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('UPDATE users set pseudo = :newPseudo, pass = :newPass WHERE id = 1');
        $infos = $req->execute(array(
        'newPseudo' => $pseudo, 
        'newPass' => $pass));
        return $infos;
        $req->closeCursor();
    }


    public function testInfos($pseudo, $pass)//requête pour vérifier les identifiants qd on essaie de se connecter à la bdd
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT COUNT(*) FROM users WHERE pseudo = :newPseudo, pass = :newPass AND id = 1');
        $infos = $req->execute(array(
        'newPseudo' => $pseudo, 
        'newPass' => $pass));
        return $infos;
        $req->closeCursor();
    }

    public function getHash()//requête pour obtenir le mdp haché
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT pass FROM users WHERE id = 1');
        $req->execute(array());
        $hash = $req->fetch();
        return $hash;
        $req->closeCursor();
    }

    public function getPseudo()//requête pour obtenir le mdp haché
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT pseudo FROM users WHERE id = 1');
        $req->execute(array());
        $pseudRegister = $req->fetch();
        return $pseudRegister;
        $req->closeCursor();
    }
}