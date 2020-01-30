<?php $title = 'Episodes'; ?>

    <nav class="navigation-bar">
        <a href="#foot" id="signature"><h2>Jean Forteroche</h2></a>
        <div id="navbar_accueil">
        <a class="btn" href="index.php">Accueil</a>
                <a class="btn active" href="index.php?action=listEpisodes">Episodes</a>
        </div>
    </nav>
    <header>
        <img id="imgHeader" src="public/images/headerEpisodes.jpg" alt="forest by Daniel H. Tong"/>
    </header>
    <section>
        <h1 id="titre">Billet simple pour l'Alaska</h1>
        <h2>Liste des épisodes</h2>
        <h3>(page: <a href="episode.html" class="onPage">1</a><a href="episode.html">2</a><a href="episode.html">3</a>)</h3>
    
        <?php foreach($episodes as $episode): ?>
        <a href="index.php?action=episode&amp;nb=<?= $episode->chapterNumber ?>" class="episodeListe">
            <p>Le <i><?= $episode->creationDate ?></i></p>
            <span>Episode n°<?= $episode->chapterNumber ?></span>
            <span class="episodeTitle"><?= $episode->title ?></span>
            <div class="extrait">
                <?php 
                if (strlen($episode->content) > 500)
                {
                    $espace = strpos($episode->content,' ', 500); 
                    $extr = substr($episode->content,0,$espace).'...';
                    echo $extr;
                }else{echo $episode->content;}
                ?>
            
            </div>
            <button class="read">Lire la suite</button>
        </a>
        <?php endforeach; ?>
        <h3>(page: <a href="episode.html" class="onPage">1</a><a href="episode.html">2</a><a href="episode.html">3</a>)</h3>
    </section>
