<?php

function getEpisode($nb)
{
    $bdd = new PDO('mysql:host=localhost;dbname=blogbdd;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
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
    $bdd = new PDO('mysql:host=localhost;dbname=blogbdd;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $req = $bdd->prepare('SELECT * FROM comments WHERE episodeNumber = ?');
    $req->execute(array($nb));
    $data = $req->fetchALL(PDO::FETCH_OBJ);
    return $data;
    $req->closeCursor();
}
function addComment($episodeNumber, $author, $comment)
{
    $bdd = new PDO('mysql:host=localhost;dbname=blogbdd;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $req = $bdd->prepare('INSERT INTO comments (episodeNumber, author, comment, creationDate, report) VALUES (?, ?, ?, NOW(), 0)');
    $req->execute(array($episodeNumber, $author, $comment));
    $req->closeCursor();
}

if(!isset($_GET['nb']) OR !is_numeric($_GET['nb']))

    header('location: episodesView.php');
else
{
    extract($_GET);
    $nb = strip_tags($nb);

    if(!empty($_POST))
    {
        extract($_POST);
        $errors = array();

        $author = strip_tags($author);
        $comment = strip_tags($comment);

        if(empty($author))
            array_push($errors, 'Entrez un pseudo');
        
        if(empty($comment))
            array_push($errors, 'Entrez un commentaire');

        if(count($errors) == 0)
        {
            $comment = addComment($nb, $author, $comment);

            $success = 'Votre commentaire a été publié';
            unset($author);
            unset($comment);
        }
    }

    $episode = getEpisode($nb);
    $comments = getComments($nb);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css" />
    <title><?= $episode->title ?></title>
</head>
<body>
    <nav class="navigation-bar">
        <a href="#foot" id="signature"><h2>Jean Forteroche</h2></a>
        <div id="navbar_accueil">
            <a class="btn" href="accueilView.php">Accueil</a>
            <a class="btn" href="episodesView.php">Episodes</a> 
        </div>
    </nav>
    <section>
        <h1 id="titre">Billet simple pour l'Alaska</h1>
        <h3>Episode n°<?= $episode->episodeNumber ?> publié le <?= $episode->creationDate ?></h3>
        
        <h2><?= $episode->title ?></h2>
        
        <p id="chapitre"><?= $episode->content ?></p>
        <div id="backNext">
            <a href="#"><div class="left"></div>Episode précédent</a>
            <a href="#">Episode suivant<div class="right"></div></a>
        </div>
    </section>
    <section class="comments">
        <h2>Commentaires</h2>

        <?php foreach($comments as $com): ?>
        <div class="comment">
            <span><?= $com->creationDate ?></span><span>par <b><?= $com->author ?></b></span>
            <p class="content"><?= $com->comment ?></p>
            <input type="submit" class="bouton" value="signaler">  
        </div>
        <?php endforeach; ?>

        <form id="makeComment" action="episodeView.php?nb=<?= $episode->episodeNumber ?>" method="post">
            <h2>Laissez moi un commentaire</h2>
            <input title="author" class="champ" type="text" name="author" id="author" placeholder=" Votre pseudo" size="15"/>
            <label id="labelAuthor" for="author"></label>
            <p>Votre commentaire</p>
            <textarea title="comment" name="comment" id="comment" cols="40" rows="5"></textarea>
            <label id="labelComment" for="comment"></label>
            <input type="submit" class="bouton" value="envoyer">
        </form>
    </section>
    <footer id="foot">
        <a class="btn" href="connection.html">Administrateur</a>
    </footer>
</body>
</html>

    