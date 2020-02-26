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


    public function countEpisodesPub():array// requete pour compter le nombre d'épisodes publiés
    {
        $req =$this->bdd->prepare('SELECT COUNT(*) FROM posts WHERE stat = 1');
        $req->execute();
        return $req->fetch();
    }

    public function countEpisodes():array // requete pour compter le nombre d'épisodes total
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) FROM posts');
        $req->execute();
        return $req->fetch();
    }

    public function pagineEpisodes(int $offset, int $nbByPage):array//requête pour récupérer les épisodes publiés en fonction de la pagination
    {
        $this->bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date FROM posts WHERE stat = 1 ORDER BY chapterNumber, publiDate  LIMIT :offset, :limitation  ');
        $req->execute(['limitation' => (int) $nbByPage, 
                        'offset' => (int) $offset ]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function findLastEpisode():array//requête pour récupérer le dernier épisode publié par date de publication
    {
        $req = $this->bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date FROM posts WHERE stat = 1 ORDER BY publiDate DESC ');
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function findEpisode(int $postId):array//requête pour récupérer un épisode en fonction de son id avec ses commentaires
    {
        $req = $this->bdd->prepare('SELECT id, author, comment, commentDate, report, posts.post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS date, publiDate 
        FROM posts
        LEFT JOIN comments ON posts.post_id = comments.post_id 
        
        WHERE posts.post_id = :idPost 
        ORDER BY comments.report DESC');
        
        $req->execute(['idPost' => (int) $postId]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function findPostedEpisode(int $postId):array//requête pour récupérer un épisode publié en fonction de son id avec ses commentaires
    {
        $req = $this->bdd->prepare('SELECT id, author, comment, DATE_FORMAT(commentDate, \'Le %d/%m/%Y à %Hh %imin\') AS commentDate, report, posts.post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date, publiDate 
        FROM posts
        LEFT JOIN comments ON posts.post_id = comments.post_id 
        
        WHERE posts.post_id = :idPost AND stat = 1
        ORDER BY comments.commentDate DESC');
        
        $req->execute(['idPost' => (int) $postId]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function previousNextEpisode(int $offset):array //requête pour naviguer entre les pages épisodes  
    {
        $this->bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
        $req = $this->bdd->prepare('SELECT post_id FROM posts WHERE stat = 1 ORDER BY chapterNumber , publiDate  LIMIT :offset, 1  ');
        $req->execute(['offset' => (int) $offset ]);
        return $req->fetch();
    }

    public function postEpisode(int $chapterNumber, string $title, string $content):bool//requête pour rajouter un épisode publié dans la bdd
    {
        $req = $this->bdd->prepare('INSERT INTO posts SET chapterNumber = :chapNumb, title = :epTitle, content = :epCont, publiDate = NOW(), stat = 1');
        return $req->execute([
            'chapNumb' => (int) $chapterNumber, 
            'epTitle' => $title, 
            'epCont' => $content]);
    }

    public function saveEpisode(int $chapterNumber, string $title, string $content):bool//requête pour rajouter un épisode sauvegardé dans la bdd
    {
        $req = $this->bdd->prepare('INSERT INTO posts SET chapterNumber = :chapNumb, title = :epTitle, content = :epCont, publiDate = null, stat = 0');
        return $req->execute([
            'chapNumb' => $chapterNumber, 
            'epTitle' => $title, 
            'epCont' => $content]);
    }

    public function postModifiedEpisode(int $postId, int $nvchapter, string $nvtitle, string $nvcontent):bool//requête pour modifier un épisode et le publier à la date d'aujourd'hui
    {
        $req = $this->bdd->prepare('UPDATE posts SET post_id = :sameid, chapterNumber = :nvchapter, title = :nvtitle, content = :nvcontent, publiDate =  NOW(), stat = 1 WHERE post_id = :sameid ');
        return $req->execute([
            'sameid' => $postId,
            'nvchapter' => $nvchapter, 
            'nvtitle' => $nvtitle, 
            'nvcontent' => $nvcontent]);
    }

    public function postModifiedEpisodeSameDate(int $postId, int $nvchapter, string $nvtitle, string $nvcontent):bool//requête pour modifier un épisode et le publier sans changer la date de publication
    {
        $req = $this->bdd->prepare('UPDATE posts SET post_id = :sameid, chapterNumber = :nvchapter, title = :nvtitle, content = :nvcontent, stat = 1 WHERE post_id = :sameid ');
        return $req->execute([
            'sameid' => $postId,
            'nvchapter' => $nvchapter, 
            'nvtitle' => $nvtitle, 
            'nvcontent' => $nvcontent]);
    }

    public function saveModifiedEpisode(int $postId, int $nvchapter, string $nvtitle, string $nvcontent):bool//requête pour modifier un épisode et l'archiver
    {
        $req = $this->bdd->prepare('UPDATE posts SET post_id = :sameid, chapterNumber = :nvchapter, title = :nvtitle, content = :nvcontent, stat = 0 WHERE post_id = :sameid ');
        return $req->execute([
            'sameid' => $postId,
            'nvchapter' => $nvchapter, 
            'nvtitle' => $nvtitle, 
            'nvcontent' => $nvcontent]);
    }

    public function deleteEpisode(int $postId):void//requête pour supprimer un épisode en fonction de son id
    {
        $req = $this->bdd->prepare('DELETE FROM posts WHERE post_id = :idPost ');
        $req->execute(['idPost' => $postId]);
    }
    
    public function listBackEpisodes(int $offset, int $nbByPage):array//requête récupérer paginée la liste des épisodes avec des infos sur leurs commentaires associés
    {
        $this->bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
        $req = $this->bdd->prepare('SELECT posts.post_id, report, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'%d/%m/%Y\') AS date,
        COUNT(comments.post_id) AS commentsNb,
        SUM(comments.report) AS reportsNb
        FROM comments
        RIGHT JOIN posts ON posts.post_id = comments.post_id
        GROUP BY(posts.post_id)
        ORDER BY chapterNumber , publiDate 
        LIMIT :value1, :value2 ');
        $req->execute(['value1' => (int) $offset,
                    'value2' => (int) $nbByPage ]);
        return $req->fetchALL(PDO::FETCH_OBJ); 
    }
}
