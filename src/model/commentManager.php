<?php
declare(strict_types=1);

require_once("src/model/Manager.php");

class commentManager extends manager
{
    public function getAllComments()//requête pour récupérer tous les commentaires par ordre décroissant de signalement
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT id, episodeNumber, author, comment, report, DATE_FORMAT(commentDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS commentDate FROM comments ORDER BY report DESC');
        $req->execute();
        $allComs = $req->fetchALL(PDO::FETCH_OBJ);
        return $allComs;
        $req->closeCursor();
    }
    
    public function getComments($post_id)//requête pour récupérer les commentaires associés à un épisode en fonction de son id
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT id, post_id, episodeNumber, author, comment, DATE_FORMAT(commentDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS commentDate, report FROM comments WHERE post_id = ?');
        $req->execute(array($post_id));
        $data = $req->fetchALL(PDO::FETCH_OBJ);
        return $data;
        $req->closeCursor();
    }

    public function getReportedComments($post_id)//requête pour récupérer les commentaires par ordre décroissant de signalement associés à un épisode en fonction de id
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT id, post_id, episodeNumber, author, comment, report, DATE_FORMAT(commentDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS commentDate FROM comments WHERE post_id = ? ORDER BY report DESC');
        $req->execute(array($post_id));
        $data = $req->fetchALL(PDO::FETCH_OBJ);
        return $data;
        $req->closeCursor();
    }

    public function postComment($post_id, $episodeNumber, $author, $comment)//requête pour ajouter un commentaire à la bdd
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('INSERT INTO comments (post_id, episodeNumber, author, comment, commentDate, report) VALUES (?, ?, ?, ?, NOW(), 0)');
        $affectedLines = $req->execute(array($post_id, $episodeNumber, $author, $comment));
        return $affectedLines;
        $req->closeCursor();
    }

    public function reports($id)//requête pour ajouter 1 au signalement d'un commentaire 
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('UPDATE comments SET report = report + 1  WHERE id = ? ');
        $report = $req->execute(array($id));
        return $report;
        $req->closeCursor();
    }

    public function deleteComments($post_id)//requête pour supprimer les commentaires d'un épisode en fonction de id
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('DELETE FROM comments WHERE post_id = ? ');
        $req->execute(array($post_id));
        $req->closeCursor();
    }

    public function deleteComment($id)//requête pour supprimer un commentaire d'un épisode en fonction de son id
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('DELETE FROM comments WHERE id = ? ');
        $req->execute(array($id));
        $req->closeCursor();
    }

    public function countReports()//requête pour compter les reports
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT SUM(report) AS value_sum FROM comments ');
        $req->execute();
        $sum = $req->fetch(PDO::FETCH_OBJ);
        return $sum;
        $req->closeCursor();
    }
}