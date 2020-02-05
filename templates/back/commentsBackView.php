<?php $title = 'Liste des épisodes'; ?>

<header>
        <h2>Billet simple pour l'Alaska</h2>
        <h2>Interface administrateur</h2>
        <h2>Jean Forteroche</h2>
    </header>
    <div id="main">
        <nav>
            <a class="btn" href="index.php?action=admConnect">Episodes</a>
            <a class="btn active" href="index.php?action=commentsPage">Commentaires</a>
            <a class="btn" href="index.php?action=profil">Profil</a>
            <a class="btn" href="index.php?action=disconnection">Déconnection</a>
        </nav>
        <section id="content">
            <h1>Liste des commentaires</h1>
            <h3>(pages: <a href="episode.html" class="page onPage">1</a><a href="episode.html" class="page">2</a><a href="episode.html" class="page">3</a>)</h3>
            <?php foreach($comments as $com): ?>
            <div class="comment">
                <span><b>Commentaire de l'épisode <?= $com->episodeNumber ?></b></span>
                <span><?= $com->commentDate ?></span><span>par <b><?= $com->author ?></b></span>
                <p class="content"><?= $com->comment ?></p>
                <h3>Nombre de signalements: <?= $com->report ?></h3>
                <a href="index.php?action=deleteCom&amp;id=<?= $com->id ?>" class="boutonRouge">supprimer</a>
            </div>
        <?php endforeach; ?>
        </section>
    </div>