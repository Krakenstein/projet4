<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="public/css/style.css" />
    <title><?= $episode->title ?></title>
</head>
<body>
    <nav class="navigation-bar">
        <a href="#foot" id="signature"><h2>Jean Forteroche</h2></a>
        <div id="navbar_accueil">
            <a class="btn" href="index.php">Accueil</a>
            <a class="btn" href="index.php?action=listEpisodes">Episodes</a>
        </div>
    </nav>
    <section>
        <h1 id="titre">Billet simple pour l'Alaska</h1>
        <h3>Episode n°<?= $episode->chapterNumber ?></h3>
        <h3>Publié le <?= $episode->creationDate ?></h3>
        <h2><?= $episode->title ?></h2>
        
        <div id="chapitre"><?= $episode->content ?></div>
        
        <div id="backNext">
            <a href="#"><div class="left"></div>Episode précédent</a>
            <a href="#">Episode suivant<div class="right"></div></a>
        </div>
    </section>
    <section class="comments">
        <h2>Commentaires</h2>

        <?php foreach($comments as $com): ?>
        <div class="comment">
            <span><?= $com->commentDate ?></span><span>par <b><?= $com->author ?></b></span>
            <p class="content"><?= $com->comment ?></p>
            <a href="index.php?action=report&amp;id=<?= $com->id ?>&amp;nb=<?= $com->episodeNumber ?>&amp;chpt=<?= $com->episodeNumber ?>" type="submit" class="reporting">signaler</a>
        </div>
        <?php endforeach; ?>

        <form id="makeComment" action="index.php?action=addComment&amp;nb=<?= $episode->chapterNumber ?>" method="post">
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
        <a class="btn" href="index.php?action=connection">Administrateur</a>
    </footer>
    <script src="public/js/classChange.js"></script>
</body>
</html>

    