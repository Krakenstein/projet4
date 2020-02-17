<?php
declare(strict_types=1);

namespace Projet4\Model;

use \PDO ;
use Projet4\Tools\Database;

class CommentManager
{
    private $dataBase;
    private $bdd;
   
    public function __construct()
    {
        $this->dataBase = new Database();
        $this->bdd = $this->dataBase->dbConnect();
    }


    public function findAllComments($offset, $nbByPage)//requête pour paginer tous les commentaires par ordre décroissant de signalement
    {
        $this->bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
        $req = $this->bdd->prepare('SELECT id, episodeNumber, author, comment, report, DATE_FORMAT(commentDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS commentDate FROM comments ORDER BY report DESC
        LIMIT :offset, :limitation;');
        $req->execute(['limitation' => (int) $nbByPage, 
                        'offset' => (int) $offset ]);
        $allComs = $req->fetchALL(PDO::FETCH_OBJ);
        return $allComs;
    }
    
    public function findComments($post_id)//requête pour récupérer les commentaires associés à un épisode en fonction de son id
    {
        $req = $this->bdd->prepare('SELECT id, post_id, episodeNumber, author, comment, DATE_FORMAT(commentDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS commentDate, report FROM comments WHERE post_id = ? ORDER BY commentDate DESC');
        $req->execute(array($post_id));
        $data = $req->fetchALL(PDO::FETCH_OBJ);
        return $data;
    }

    public function findReportedComments($post_id)//requête pour récupérer les commentaires par ordre décroissant de signalement associés à un épisode en fonction de id
    {
        $req = $this->bdd->prepare('SELECT id, post_id, episodeNumber, author, comment, report, DATE_FORMAT(commentDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS commentDate FROM comments WHERE post_id = ? ORDER BY report DESC');
        $req->execute(array($post_id));
        $data = $req->fetchALL(PDO::FETCH_OBJ);
        return $data;
    }

    public function postComment($post_id, $episodeNumber, $author, $comment)//requête pour ajouter un commentaire à la bdd
    {
        $req = $this->bdd->prepare('INSERT INTO comments SET post_id = :idPost, episodeNumber = :episNumb, author = :auth, comment = :com, commentDate = NOW(), report = 0');
        $affectedLines = $req->execute(array(
            'idPost' => $post_id, 
            'episNumb' => $episodeNumber, 
            'auth' => $author, 
            'com' => $comment));
        return $affectedLines;
    }

    public function reports($id)//requête pour ajouter 1 au signalement d'un commentaire 
    {
        $req = $this->bdd->prepare('UPDATE comments SET report = report + 1  WHERE id = :comId ');
        $report = $req->execute(array(
            'comId' => $id));
        return $report;
    }

    public function deleteReports($id)//requête pour supprimmer les signalements d'un commentaire 
    {
        $req = $this->bdd->prepare('UPDATE comments SET report = 0  WHERE id = :comId ');
        $reportDelet = $req->execute(array(
            'comId' => $id));
        return $reportDelet;
    }

    public function deleteComment($id)//requête pour supprimer un commentaire d'un épisode en fonction de son id
    {
        $req = $this->bdd->prepare('DELETE FROM comments WHERE id = :comId ');
        $req->execute(array(
            'comId' => $id));
    }

    public function countReports()//requête pour compter les reports
    {
        $req = $this->bdd->prepare('SELECT SUM(report) AS value_sum FROM comments ');
        $req->execute();
        $sum = $req->fetch();
        return $sum;
    }

    public function countComs()//requête pour compter les commentaires
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) AS comsNb FROM comments ');
        $req->execute();
        $count = $req->fetch();
        return $count;
    }
}