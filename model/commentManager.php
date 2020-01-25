<?php

require_once("model/Manager.php");

class commentManager extends manager
{
    public function getAllComments()//requête pour récupérer tous les commentaires 
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT * FROM comments ORDER BY episodeNumber DESC');
        $req->execute();
        $allComs = $req->fetchALL(PDO::FETCH_OBJ);
        return $allComs;
        $req->closeCursor();
    }
    
    public function getComments($episodeNumber)//requête pour récupérer les commentaires associés à un épisode en fonction de son numéro de chapitre
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT * FROM comments WHERE episodeNumber = ?');
        $req->execute(array($episodeNumber));
        $data = $req->fetchALL(PDO::FETCH_OBJ);
        return $data;
        $req->closeCursor();
    }

    public function getReportedComments($episodeNumber)//requête pour récupérer les commentaires par ordre décroissant de signalement associés à un épisode en fonction de son numéro de chapitre
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT * FROM comments WHERE episodeNumber = ? ORDER BY report DESC');
        $req->execute(array($episodeNumber));
        $data = $req->fetchALL(PDO::FETCH_OBJ);
        return $data;
        $req->closeCursor();
    }

    public function postComment($episodeNumber, $author, $comment)//requête pour ajouter un commentaire à un épisode en fonction de son numéro de chapitre
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('INSERT INTO comments (episodeNumber, author, comment, commentDate, report) VALUES (?, ?, ?, NOW(), 0)');
        $affectedLines = $req->execute(array($episodeNumber, $author, $comment));
        return $affectedLines;
        $req->closeCursor();
    }

    public function countComments($chapterNumber)//requête pour "compter" les commentaires relatifs à un épisode en fonction de son numéro de chapitre
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('UPDATE posts SET commentsNb = commentsNb + 1 WHERE chapterNumber = ? ');
        $nbComments = $req->execute(array($chapterNumber));
        return $nbComments;
        $req->closeCursor();
    }

    public function countReports($chapterNumber)//requête pour compter les commentaires signalés relatifs à un épisode en fonction de son numéro de chapitre
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('UPDATE posts SET reports = reports + 1 WHERE chapterNumber = ? ');
        $nbReports = $req->execute(array($chapterNumber));
        return $nbReports;
        $req->closeCursor();
    }

    public function substractComments($chapterNumber)//requête pour soustraire les commentaires supprimés relatifs à un épisode en fonction de son numéro de chapitre
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('UPDATE posts SET commentsNb = commentsNb - 1 WHERE chapterNumber = ? ');
        $subComments = $req->execute(array($chapterNumber));
        return $subComments;
        $req->closeCursor();
    }

    public function reports($id)//requête pour ajouter 1 au signalement d'un commentaire et le marquer comme signalé
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('UPDATE comments SET report = report + 1 , reported = true WHERE id = ? ');
        $report = $req->execute(array($id));
        return $report;
        $req->closeCursor();
    }

    public function deleteComments($episodeNumber)//requête pour supprimer les commentaires d'un épisode en fonction de son numéro de chapitre
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('DELETE FROM comments WHERE episodeNumber = ? ');
        $req->execute(array($episodeNumber));
        $req->closeCursor();
    }

    public function deleteComment($id)//requête pour supprimer un commentaire d'un épisode en fonction de son id
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('DELETE FROM comments WHERE id = ? ');
        $req->execute(array($id));
        $req->closeCursor();
    }
}