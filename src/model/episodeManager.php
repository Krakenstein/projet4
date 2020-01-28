<?php

require_once("src/model/manager.php");

class episodeManager extends manager
{
    public function getEpisodes()//requête pour récupérer tous les épisodes publiés
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT chapterNumber, title, content, stat, DATE_FORMAT(creationDate, \'%d/%m/%Y\') AS creationDate FROM posts WHERE stat = 1 ORDER BY chapterNumber DESC');
        $req->execute();
        $episodes = $req->fetchALL(PDO::FETCH_OBJ);
        return $episodes;
        $req->closeCursor();
    }

    public function getAllEpisodes()//requête pour récupérer la liste de tous les épisodes 
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT chapterNumber, title, content, stat, DATE_FORMAT(creationDate, \'%d/%m/%Y\') AS creationDate FROM posts ORDER BY chapterNumber DESC');
        $req->execute();
        $allEpisodes = $req->fetchALL(PDO::FETCH_OBJ);
        return $allEpisodes;
        $req->closeCursor();
    }

    public function getEpisode($episodeNumber)//requête pour récupérer un épisode en fonction de son numéro de chapitre
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT chapterNumber, title, content, stat, DATE_FORMAT(creationDate, \'%d/%m/%Y\') AS creationDate FROM posts WHERE chapterNumber = ? ');
        $req->execute(array($episodeNumber));
        $data = $req->fetch(PDO::FETCH_OBJ);

        return $data;
    }

    public function getLastEpisode()//requête pour récupérer le dernier épisode publié par numéro de chapitre
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT chapterNumber, title, content, stat, DATE_FORMAT(creationDate, \'%d/%m/%Y\') AS creationDate FROM posts WHERE stat = 1 ORDER BY chapterNumber DESC ');
        $req->execute();
        $lastEpisode = $req->fetch(PDO::FETCH_OBJ);

        return $lastEpisode;
    }

    public function getPostedEpisode($episodeNumber)//requête pour récupérer un épisode publié en fonction de son numéro de chapitre
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT chapterNumber, title, content, stat, DATE_FORMAT(creationDate, \'%d/%m/%Y\') AS creationDate FROM posts WHERE chapterNumber = ? AND stat = 1');
        $req->execute(array($episodeNumber));
        $data = $req->fetch(PDO::FETCH_OBJ);

        return $data;
    }

    public function postEpisode($chapterNumber, $title, $content)//requête pour rajouter un épisode publié dans la bdd
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('INSERT INTO posts (chapterNumber, title, content, creationDate, stat) VALUES (?, ?, ?, NOW(), 1)');
        $postedEpisode = $req->execute(array($chapterNumber, $title, $content));
        return $postedEpisode;
        $req->closeCursor();
    }

    public function saveEpisode($chapterNumber, $title, $content)//requête pour rajouter un épisode archivé dans la bdd
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('INSERT INTO posts (chapterNumber, title, content, creationDate, stat) VALUES (?, ?, ?, NOW(), 0)');
        $savedEpisode = $req->execute(array($chapterNumber, $title, $content));
        return $savedEpisode;
        $req->closeCursor();
    }

    public function postModifiedEpisode($nvchapter, $nvtitle, $nvcontent)//requête pour modifier un épisode et le publier 
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('UPDATE posts SET chapterNumber = :nvchapter, title = :nvtitle, content = :nvcontent, creationDate =  NOW(), stat = 1 WHERE chapterNumber = :nvchapter ');
        $postedModifiedEpisode = $req->execute(array(
            'nvchapter' => $nvchapter, 
            'nvtitle' => $nvtitle, 
            'nvcontent' => $nvcontent
            ));
        return $postedModifiedEpisode;
        $req->closeCursor();
    }

    public function saveModifiedEpisode($nvchapter, $nvtitle, $nvcontent)//requête pour modifier un épisode et l'archiver
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('UPDATE posts SET chapterNumber = :nvchapter, title = :nvtitle, content = :nvcontent, creationDate =  NOW(), stat = 0 WHERE chapterNumber = :nvchapter ');
        $savedModifiedEpisode = $req->execute(array(
            'nvchapter' => $nvchapter, 
            'nvtitle' => $nvtitle, 
            'nvcontent' => $nvcontent
            ));
        return $savedModifiedEpisode;
        $req->closeCursor();
    }

    public function deleteEpisode($chapterNumber)//requête pour supprimer un épisode en fonction de son numéro de chapitre
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('DELETE FROM posts WHERE chapterNumber = ? ');
        $req->execute(array($chapterNumber));
        $req->closeCursor();
    }
    
    public function joinTables()//requête pour supprimer un épisode en fonction de son numéro de chapitre
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT chapterNumber, title, content, stat, DATE_FORMAT(creationDate, \'%d/%m/%Y\') AS creationDate, commentsNb, reported 
        FROM comments
        RIGHT JOIN posts ON posts.chapterNumber = comments.episodeNumber
        GROUP BY(posts.chapterNumber)
        ORDER BY chapterNumber DESC;');
        $req->execute(array());
        $tablesJoin = $req->fetchALL(PDO::FETCH_OBJ);
 
        return $tablesJoin; 
    }
}
