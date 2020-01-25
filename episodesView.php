<?php

function getEpisodes()
{
    $bdd = new PDO('mysql:host=localhost;dbname=blogbdd;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $req = $bdd->prepare('SELECT * FROM posts WHERE stat = 1 ORDER BY id DESC');
    $req->execute();
    $data = $req->fetchALL(PDO::FETCH_OBJ);
    return $data;
    $req->closeCursor();
}

$episodes = getEpisodes();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css" />
    <title>Episodes</title>
</head>
<body>
    <nav class="navigation-bar">
        <a href="#foot" id="signature"><h2>Jean Forteroche</h2></a>
        <div id="navbar_accueil">
            <a class="btn" href="accueil.html">Accueil</a>
            <a class="btn active" href="episodes.html">Episodes</a>
        </div>
    </nav>
    <header>
        <img id="imgHeader" src="images/headerEpisodes.jpg" alt="forest by Daniel H. Tong"/>
    </header>
    <section>
        <h1 id="titre">Billet simple pour l'Alaska</h1>
        <h2>Liste des épisodes</h2>
        <h3>(page: <a href="episode.html" class="onPage">1</a><a href="episode.html">2</a><a href="episode.html">3</a>)</h3>
    
        <?php foreach($episodes as $episode): ?>
        <a href="episodeView.php?nb=<?= $episode->episodeNumber ?>" class="episodeListe">
            <p>Le <i><?= $episode->creationDate ?></i></p>
            <span>Episode n°<?= $episode->episodeNumber ?></span>
            <span class="episodeTitle"><?= $episode->title ?></span>
            <span class="extrait">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel ligula ac mi tincidunt condimentum. Aliquam id lacus in nulla vehicula pulvinar...</span>
        </a>
        <?php endforeach; ?>
        <h3>(page: <a href="episode.html" class="onPage">1</a><a href="episode.html">2</a><a href="episode.html">3</a>)</h3>
    </section>
    <footer id="foot">
        <a class="btn" href="connection.html">Administrateur</a>
    </footer>
</body>
</html>