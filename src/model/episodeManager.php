<?php
declare(strict_types=1);

require_once("src/model/manager.php");

class episodeManager extends manager
{
    public function countEpisodesPub()// requete pour compter le nombre d'épisodes publiés
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT COUNT(*) FROM posts WHERE stat = 1');
        $req->execute();
        $episodesTot = $req->fetch();
        return $episodesTot;
        $req->closeCursor();
    }

    public function countEpisodes()// requete pour compter le nombre d'épisodes publiés
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT COUNT(*) FROM posts');
        $req->execute();
        $episodesTot = $req->fetch();
        return $episodesTot;
        $req->closeCursor();
    }

    public function PagineEpisodes($offset, $nbByPage)//requête pour récupérer les épisodes publiés en fonction de la pagination
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date FROM posts WHERE stat = 1 ORDER BY publiDate DESC LIMIT :offset, :limitation  ');
        $req->bindValue(':limitation', (int) $nbByPage, PDO::PARAM_INT);
        $req->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $req->execute();
        $pagina = $req->fetchALL(PDO::FETCH_OBJ);
        return $pagina;
        $req->closeCursor();
    }

    public function getEpisodes()//requête pour récupérer tous les épisodes publiés
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date FROM posts WHERE stat = 1 ORDER BY publiDate DESC');
        $req->execute();
        $episodes = $req->fetchALL(PDO::FETCH_OBJ);
        return $episodes;
        $req->closeCursor();
    }

    public function getAllEpisodes()//requête pour récupérer la liste de tous les épisodes 
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS publiDate FROM posts ORDER BY chapterNumber DESC');
        $req->execute();
        $allEpisodes = $req->fetchALL(PDO::FETCH_OBJ);
        return $allEpisodes;
        $req->closeCursor();
    }

    public function getEpisode($post_id)//requête pour récupérer un épisode en fonction de son id
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date FROM posts WHERE post_id = ? ');
        $req->execute(array($post_id));
        $data = $req->fetch(PDO::FETCH_OBJ);

        return $data;
    }

    public function getLastEpisode()//requête pour récupérer le dernier épisode publié par date de publication
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date FROM posts WHERE stat = 1 ORDER BY publiDate DESC ');
        $req->execute();
        $lastEpisode = $req->fetch(PDO::FETCH_OBJ);

        return $lastEpisode;
    }

    public function getPostedEpisode($post_id)//requête pour récupérer un épisode publié en fonction de son id
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date, publiDate FROM posts WHERE post_id = ? AND stat = 1');
        $req->execute(array($post_id));
        $data = $req->fetch(PDO::FETCH_OBJ);

        return $data;
    }

    public function previousEpisode($publiDate)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date, publiDate FROM posts WHERE publiDate < ? AND stat = 1 ORDER BY publiDate DESC LIMIT 1;');
        $req->execute(array($publiDate));
        $previousEp = $req->fetch(PDO::FETCH_OBJ);
        return $previousEp;
        $req->closeCursor();
    }

    public function nextEpisode($publiDate)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT post_id, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'Le %d/%m/%Y\') AS date, publiDate FROM posts WHERE publiDate > ? AND stat = 1 ORDER BY publiDate LIMIT 1;');
        $req->execute(array($publiDate));
        $nextEp = $req->fetch(PDO::FETCH_OBJ);
        return $nextEp;
        $req->closeCursor();
    }

    public function postEpisode($chapterNumber, $title, $content)//requête pour rajouter un épisode publié dans la bdd
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('INSERT INTO posts (chapterNumber, title, content, publiDate, stat) VALUES (?, ?, ?, NOW(), 1)');
        $postedEpisode = $req->execute(array($chapterNumber, $title, $content));
        return $postedEpisode;
        $req->closeCursor();
    }

    public function saveEpisode($chapterNumber, $title, $content)//requête pour rajouter un épisode archivé dans la bdd
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('INSERT INTO posts (chapterNumber, title, content, publiDate, stat) VALUES (?, ?, ?, null, 0)');
        $savedEpisode = $req->execute(array($chapterNumber, $title, $content));
        return $savedEpisode;
        $req->closeCursor();
    }

    public function postModifiedEpisode($post_id, $nvchapter, $nvtitle, $nvcontent)//requête pour modifier un épisode et le publier 
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('UPDATE posts SET post_id = :sameid, chapterNumber = :nvchapter, title = :nvtitle, content = :nvcontent, publiDate =  NOW(), stat = 1 WHERE post_id = :sameid ');
        $postedModifiedEpisode = $req->execute(array(
            'sameid' => $post_id,
            'nvchapter' => $nvchapter, 
            'nvtitle' => $nvtitle, 
            'nvcontent' => $nvcontent
            ));
        return $postedModifiedEpisode;
        $req->closeCursor();
    }

    public function saveModifiedEpisode($post_id, $nvchapter, $nvtitle, $nvcontent)//requête pour modifier un épisode et l'archiver
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('UPDATE posts SET post_id = :sameid, chapterNumber = :nvchapter, title = :nvtitle, content = :nvcontent, publiDate =  null, stat = 0 WHERE post_id = :sameid ');
        $savedModifiedEpisode = $req->execute(array(
            'sameid' => $post_id,
            'nvchapter' => $nvchapter, 
            'nvtitle' => $nvtitle, 
            'nvcontent' => $nvcontent
            ));
        return $savedModifiedEpisode;
        $req->closeCursor();
    }

    public function deleteEpisode($post_id)//requête pour supprimer un épisode en fonction de son numéro de chapitre
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('DELETE FROM posts WHERE post_id = ? ');
        $req->execute(array($post_id));
        $req->closeCursor();
    }
    
    public function joinTables($offset, $nbByPage)//requête pour faire une jointure entre la table posts et la table comments
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT posts.post_id, report, chapterNumber, title, content, stat, DATE_FORMAT(publiDate, \'%d/%m/%Y\') AS date,
        COUNT(comments.post_id) AS commentsNb,
        SUM(comments.report) AS reportsNb
        FROM comments
        RIGHT JOIN posts ON posts.post_id = comments.post_id
        GROUP BY(posts.post_id)
        ORDER BY chapterNumber DESC, publiDate DESC
        LIMIT :offset, :limitation;');
        $req->bindValue(':limitation', (int) $nbByPage, PDO::PARAM_INT);
        $req->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $req->execute();
        $tablesJoin = $req->fetchALL(PDO::FETCH_OBJ);
 
        return $tablesJoin; 
        $req->closeCursor();
    }
}
