<?php
declare(strict_types=1);

namespace Projet4\Model;

use \PDO ;
use Projet4\Tools\Database;

class EpisodeManager
{
    private $dataBase;
    private $bdd;
   
    public function __construct()
    {
        $this->dataBase = new Database();
        $this->bdd = $this->dataBase->dbConnect();
    }


    public function countEpisodesPub()// requete pour compter le nombre d'épisodes publiés
    {
        $req =$this->bdd->prepare('SELECT COUNT(*) FROM posts WHERE stat = 1');
        $req->execute();
        return $req->fetch();
    }

    public function countEpisodes()// requete pour compter le nombre d'épisodes total
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) FROM posts');
        $req->execute();
        return $req->fetch();
    }

    public function PagineEpisodes($offset, $nbByPage)//requête pour récupérer les épisodes publiés en fonction de la pagination
    {
        $this->bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date FROM posts WHERE stat = 1 ORDER BY chapterNumber, publiDate  LIMIT :offset, :limitation  ');
        $req->execute(['limitation' => (int) $nbByPage, 
                        'offset' => (int) $offset ]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function findEpisodes()//requête pour récupérer tous les épisodes publiés
    {
 
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date FROM posts WHERE stat = 1 ORDER BY publiDate DESC');
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function findAllEpisodes()//requête pour récupérer la liste de tous les épisodes 
    {
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS publiDate FROM posts ORDER BY chapterNumber DESC');
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function findEpisode($postId)//requête pour récupérer un épisode en fonction de son id
    {
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'le %d/%m/%Y à %Hh %imin\') AS publiDate FROM posts WHERE post_id = :idPost ');
        $req->execute(array(
            'idPost' => $postId));
        return $req->fetch(PDO::FETCH_OBJ);
    }

    public function findLastEpisode()//requête pour récupérer le dernier épisode publié par date de publication
    {
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date FROM posts WHERE stat = 1 ORDER BY publiDate DESC ');
        $req->execute();
        return $req->fetch(PDO::FETCH_OBJ);
    }

    public function findPostedEpisode($postId)//requête pour récupérer un épisode publié en fonction de son id
    {
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS date, publiDate FROM posts WHERE post_id = :idPost AND stat = 1');
        $req->execute(array(
            'idPost' => $postId));
        return $req->fetch(PDO::FETCH_OBJ);
    }

    /*public function previousEpisode($chapterNumber, $publidate)
    {
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date, publiDate FROM posts WHERE chapterNumber < ? AND publiDate < ? AND stat = 1 ORDER BY chapterNumber, publiDate DESC LIMIT 1;');
        $req->execute(array($chapterNumber));
        $previousEp = $req->fetch(PDO::FETCH_OBJ);
        return $previousEp;
    }

    public function nextEpisode($chapterNumber, $publiDate)
    {
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date, publiDate FROM posts WHERE chapterNumber > ? AND publiDate < ? AND stat = 1 ORDER BY chapterNumber, publiDate LIMIT 1;');
        $req->execute(array($chapterNumber));
        $nextEp = $req->fetch(PDO::FETCH_OBJ);
        return $nextEp;
    }*/

    public function postEpisode($chapterNumber, $title, $content)//requête pour rajouter un épisode publié dans la bdd
    {
        $req = $this->bdd->prepare('INSERT INTO posts SET chapterNumber = :chapNumb, title = :epTitle, content = :epCont, publiDate = NOW(), stat = 1');
        return $req->execute(array(
            'chapNumb' => $chapterNumber, 
            'epTitle' => $title, 
            'epCont' => $content));
    }

    public function saveEpisode($chapterNumber, $title, $content)//requête pour rajouter un épisode sauvegardé dans la bdd
    {
        $req = $this->bdd->prepare('INSERT INTO posts SET chapterNumber = :chapNumb, title = :epTitle, content = :epCont, publiDate = null, stat = 0');
        return $req->execute(array(
            'chapNumb' => $chapterNumber, 
            'epTitle' => $title, 
            'epCont' => $content));
    }

    public function postModifiedEpisode($postId, $nvchapter, $nvtitle, $nvcontent)//requête pour modifier un épisode et le publier à la date d'aujourd'hui
    {
        $req = $this->bdd->prepare('UPDATE posts SET post_id = :sameid, chapterNumber = :nvchapter, title = :nvtitle, content = :nvcontent, publiDate =  NOW(), stat = 1 WHERE post_id = :sameid ');
        return $req->execute(array(
            'sameid' => $postId,
            'nvchapter' => $nvchapter, 
            'nvtitle' => $nvtitle, 
            'nvcontent' => $nvcontent
            ));
    }

    public function postModifiedEpisodeSameDate($postId, $nvchapter, $nvtitle, $nvcontent)//requête pour modifier un épisode et le publier sans changer la date de publication
    {
        $req = $this->bdd->prepare('UPDATE posts SET post_id = :sameid, chapterNumber = :nvchapter, title = :nvtitle, content = :nvcontent, stat = 1 WHERE post_id = :sameid ');
        return $req->execute(array(
            'sameid' => $postId,
            'nvchapter' => $nvchapter, 
            'nvtitle' => $nvtitle, 
            'nvcontent' => $nvcontent           
            ));
    }

    public function saveModifiedEpisode($postId, $nvchapter, $nvtitle, $nvcontent)//requête pour modifier un épisode et l'archiver
    {
        $req = $this->bdd->prepare('UPDATE posts SET post_id = :sameid, chapterNumber = :nvchapter, title = :nvtitle, content = :nvcontent, stat = 0 WHERE post_id = :sameid ');
        return $req->execute(array(
            'sameid' => $postId,
            'nvchapter' => $nvchapter, 
            'nvtitle' => $nvtitle, 
            'nvcontent' => $nvcontent
            ));
    }

    public function deleteEpisode($postId)//requête pour supprimer un épisode en fonction de son numéro de chapitre
    {
        $req = $this->bdd->prepare('DELETE FROM posts WHERE post_id = :idPost ');
        $req->execute(array(
            'idPost' => $postId));
    }
    
    public function joinTables($offset, $nbByPage)//requête pour faire une jointure entre la table posts et la table comments
    {
        $this->bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
        $req = $this->bdd->prepare('SELECT posts.post_id, report, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'%d/%m/%Y\') AS date,
        COUNT(comments.post_id) AS commentsNb,
        SUM(comments.report) AS reportsNb
        FROM comments
        RIGHT JOIN posts ON posts.post_id = comments.post_id
        GROUP BY(posts.post_id)
        ORDER BY chapterNumber , publiDate 
        LIMIT :offset, :limitation;');
        $req->execute(['limitation' => (int) $nbByPage, 
                        'offset' => (int) $offset ]);
        return $req->fetchALL(PDO::FETCH_OBJ); 
    }
}
