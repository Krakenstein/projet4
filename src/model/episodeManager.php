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
        $episodesTot = $req->fetch();
        return $episodesTot;
    }

    public function countEpisodes()// requete pour compter le nombre d'épisodes total
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) FROM posts');
        $req->execute();
        $episodesTot = $req->fetch();
        return $episodesTot;
    }

    public function PagineEpisodes($offset, $nbByPage)//requête pour récupérer les épisodes publiés en fonction de la pagination
    {
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date FROM posts WHERE stat = 1 ORDER BY chapterNumber, publiDate  LIMIT :offset, :limitation  ');
        $req->bindValue(':limitation', (int) $nbByPage, PDO::PARAM_INT);
        $req->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $req->execute();
        $pagina = $req->fetchALL(PDO::FETCH_OBJ);
        return $pagina;
    }

    public function findEpisodes()//requête pour récupérer tous les épisodes publiés
    {
 
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date FROM posts WHERE stat = 1 ORDER BY publiDate DESC');
        $req->execute();
        $episodes = $req->fetchALL(PDO::FETCH_OBJ);
        return $episodes;
    }

    public function findAllEpisodes()//requête pour récupérer la liste de tous les épisodes 
    {
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS publiDate FROM posts ORDER BY chapterNumber DESC');
        $req->execute();
        $allEpisodes = $req->fetchALL(PDO::FETCH_OBJ);
        return $allEpisodes;
    }

    public function findEpisode($post_id)//requête pour récupérer un épisode en fonction de son id
    {
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'le %d/%m/%Y à %Hh %imin\') AS publiDate FROM posts WHERE post_id = ? ');
        $req->execute(array($post_id));
        $data = $req->fetch(PDO::FETCH_OBJ);

        return $data;
    }

    public function findLastEpisode()//requête pour récupérer le dernier épisode publié par date de publication
    {
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date FROM posts WHERE stat = 1 ORDER BY publiDate DESC ');
        $req->execute();
        $lastEpisode = $req->fetch(PDO::FETCH_OBJ);

        return $lastEpisode;
    }

    public function findPostedEpisode($post_id)//requête pour récupérer un épisode publié en fonction de son id
    {
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS date, publiDate FROM posts WHERE post_id = ? AND stat = 1');
        $req->execute(array($post_id));
        $data = $req->fetch(PDO::FETCH_OBJ);

        return $data;
    }

    public function previousEpisode($chapterNumber, $publidate)
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
    }

    public function postEpisode($chapterNumber, $title, $content)//requête pour rajouter un épisode publié dans la bdd
    {
        $req = $this->bdd->prepare('INSERT INTO posts (chapterNumber, title, content, publiDate, stat) VALUES (?, ?, ?, NOW(), 1)');
        $postedEpisode = $req->execute(array($chapterNumber, $title, $content));
        return $postedEpisode;
    }

    public function saveEpisode($chapterNumber, $title, $content)//requête pour rajouter un épisode archivé dans la bdd
    {
        $req = $this->bdd->prepare('INSERT INTO posts (chapterNumber, title, content, publiDate, stat) VALUES (?, ?, ?, null, 0)');
        $savedEpisode = $req->execute(array($chapterNumber, $title, $content));
        return $savedEpisode;
    }

    public function postModifiedEpisode($post_id, $nvchapter, $nvtitle, $nvcontent)//requête pour modifier un épisode et le publier à la date d'aujourd'hui
    {
        $req = $this->bdd->prepare('UPDATE posts SET post_id = :sameid, chapterNumber = :nvchapter, title = :nvtitle, content = :nvcontent, publiDate =  NOW(), stat = 1 WHERE post_id = :sameid ');
        $postedModifiedEpisode = $req->execute(array(
            'sameid' => $post_id,
            'nvchapter' => $nvchapter, 
            'nvtitle' => $nvtitle, 
            'nvcontent' => $nvcontent
            ));
        return $postedModifiedEpisode;
    }

    public function postModifiedEpisodeSameDate($post_id, $nvchapter, $nvtitle, $nvcontent)//requête pour modifier un épisode et le publier sans changer la date de publication
    {
        $req = $this->bdd->prepare('UPDATE posts SET post_id = :sameid, chapterNumber = :nvchapter, title = :nvtitle, content = :nvcontent, stat = 1 WHERE post_id = :sameid ');
        $postedModifiedEpisode = $req->execute(array(
            'sameid' => $post_id,
            'nvchapter' => $nvchapter, 
            'nvtitle' => $nvtitle, 
            'nvcontent' => $nvcontent,
            
            ));
        return $postedModifiedEpisode;
    }

    public function saveModifiedEpisode($post_id, $nvchapter, $nvtitle, $nvcontent)//requête pour modifier un épisode et l'archiver
    {
        $req = $this->bdd->prepare('UPDATE posts SET post_id = :sameid, chapterNumber = :nvchapter, title = :nvtitle, content = :nvcontent, stat = 0 WHERE post_id = :sameid ');
        $savedModifiedEpisode = $req->execute(array(
            'sameid' => $post_id,
            'nvchapter' => $nvchapter, 
            'nvtitle' => $nvtitle, 
            'nvcontent' => $nvcontent
            ));
        return $savedModifiedEpisode;
    }

    public function deleteEpisode($post_id)//requête pour supprimer un épisode en fonction de son numéro de chapitre
    {
        $req = $this->bdd->prepare('DELETE FROM posts WHERE post_id = ? ');
        $req->execute(array($post_id));
    }
    
    public function joinTables($offset, $nbByPage)//requête pour faire une jointure entre la table posts et la table comments
    {
        $req = $this->bdd->prepare('SELECT posts.post_id, report, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'%d/%m/%Y\') AS date,
        COUNT(comments.post_id) AS commentsNb,
        SUM(comments.report) AS reportsNb
        FROM comments
        RIGHT JOIN posts ON posts.post_id = comments.post_id
        GROUP BY(posts.post_id)
        ORDER BY chapterNumber , publiDate 
        LIMIT :offset, :limitation;');
        $req->bindValue(':limitation', (int) $nbByPage, PDO::PARAM_INT);
        $req->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $req->execute();
        $tablesJoin = $req->fetchALL(PDO::FETCH_OBJ);
 
        return $tablesJoin; 
    }
}
