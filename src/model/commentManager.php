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

    public function findAllComments(int $offset, int $nbByPage):array //requête pour paginer tous les commentaires par ordre décroissant de signalement
    {
        $this->bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
        $req = $this->bdd->prepare('SELECT id, chapterNumber, author, comment, report, DATE_FORMAT(commentDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS commentDate 
        FROM comments 
        INNER JOIN posts ON posts.post_id = comments.post_id
        ORDER BY report DESC
        LIMIT :offset, :limitation;');
        $req->execute(['limitation' => (int) $nbByPage, 
                        'offset' => (int) $offset ]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function findReportedComments(int $postId):array//requête pour récupérer les commentaires par ordre décroissant de signalement associés à un épisode en fonction de id
    {
        $req = $this->bdd->prepare('SELECT id, post_id, author, comment, report, DATE_FORMAT(commentDate, \'Le %d/%m/%Y à %Hh %imin %ss\') AS commentDate FROM comments WHERE post_id = :idPost ORDER BY report DESC');
        $req->execute(['idPost' => (int) $postId]);
        return $req->fetchALL(PDO::FETCH_OBJ);
    }

    public function postComment(int $postId, string $author, string $comment):bool//requête pour ajouter un commentaire à la bdd
    {
        $req = $this->bdd->prepare('INSERT INTO comments SET post_id = :idPost, author = :auth, comment = :com, commentDate = NOW(), report = 0');
        return $req->execute([
            'idPost' => (int) $postId,  
            'auth' => $author, 
            'com' => $comment]);
    }

    public function reports(int $id):bool//requête pour ajouter 1 au signalement d'un commentaire 
    {
        $req = $this->bdd->prepare('UPDATE comments SET report = report + 1  WHERE id = :comId ');
        return $req->execute(['comId' => (int) $id]);
    }

    public function deleteReports(int $id):bool//requête pour supprimmer les signalements d'un commentaire 
    {
        $req = $this->bdd->prepare('UPDATE comments SET report = 0  WHERE id = :comId ');
        return $req->execute(['comId' => (int) $id]);
    }

    public function deleteComment(int $id):void//requête pour supprimer un commentaire d'un épisode en fonction de son id
    {
        $req = $this->bdd->prepare('DELETE FROM comments WHERE id = :comId ');
        $req->execute(['comId' => (int) $id]);
    }

    public function countReports():array//requête pour compter les reports
    {
        $req = $this->bdd->prepare('SELECT SUM(report) AS value_sum FROM comments ');
        $req->execute();
        return $req->fetch();
    }

    public function countComs():array//requête pour compter les commentaires
    {
        $req = $this->bdd->prepare('SELECT COUNT(*) AS comsNb FROM comments ');
        $req->execute();
        return $req->fetch();
    }
}