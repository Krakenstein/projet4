<?php

function getEpisodes()
{
    $bdd = dbConnect();
    $req = $bdd->prepare('SELECT * FROM posts WHERE stat = 1 ORDER BY id DESC');
    $req->execute();
    $data = $req->fetchALL(PDO::FETCH_OBJ);
    return $data;
    $req->closeCursor();
}

function getEpisode($nb)
{
    $bdd = dbConnect();
    $req = $bdd->prepare('SELECT * FROM posts WHERE episodeNumber = ?');
    $req->execute(array($nb));
    if($req->rowCount() == 1)
    {
        $data = $req->fetch(PDO::FETCH_OBJ);
        return $data;
    }else
        header('location: index.php');
    $req->closeCursor();
}

function getComments($nb)
{
    $bdd = dbConnect();
    $req = $bdd->prepare('SELECT * FROM comments WHERE episodeNumber = ?');
    $req->execute(array($nb));
    $data = $req->fetchALL(PDO::FETCH_OBJ);
    return $data;
    $req->closeCursor();
}

function postComment($episodeNumber, $author, $comment)
{
    $bdd = dbConnect();
    $req = $bdd->prepare('INSERT INTO comments (episodeNumber, author, comment, creationDate, report) VALUES (?, ?, ?, NOW(), 0)');
    $affectedLines = $req->execute(array($episodeNumber, $author, $comment));
    return $affectedLines;
    $req->closeCursor();
}

function dbConnect()
{
    $bdd = new PDO('mysql:host=localhost;dbname=blogbdd;charset=utf8', 'root', '');
    return $bdd;
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}