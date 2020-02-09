<?php $title = 'Liste des épisodes'; ?>

<header>
        <h2>Billet simple pour l'Alaska</h2>
        <h2>Interface administrateur</h2>
        <h2>Jean Forteroche</h2>
    </header>
    <div id="main">
        <nav>
            <a class="btn" href="index.php?action=episodes">Episodes</a>
            <a class="btn<?php if(($countcoms[0])  < 1) echo 'Hidden'?><?php if(($sum->value_sum) > 0) echo 'Red'?>" href="index.php?action=commentsPage">Commentaires</a>
            <a class="btn" href="index.php?action=profil">Profil</a>
            <a class="btn" href="index.php?action=disconnection">Déconnection</a>
        </nav>
        <section id="content">
            <h1>Liste des commentaires</h1>
            <h3>Nombre total de commentaires:<?=$countcoms[0]?></h3>
            <div class="backNext<?php if($totalpages < 2) echo 'Hidden'?>">
            <a href="index.php?action=commentsPage&amp;currentpage=<?= $currentpage - 1?>&amp;#titre"><div class="left"></div>Page précédente</a>
            <span class="currentPage"><?='Page ' . $currentpage . '/' . $totalpages?></span>
            <a href="index.php?action=commentsPage&amp;currentpage=<?= $currentpage + 1?>&amp;#titre">Page suivante<div class="right"></div></a>
            </div>
            <?php foreach($allComs as $com): ?>
            <div class="comment">
                <span><b>Commentaire de l'épisode <?= $com->episodeNumber ?></b></span>
                <span>Posté <?= $com->commentDate ?></span><span>par <b><?= $com->author ?></b></span>
                <p class="content"><?= $com->comment ?></p>
                <h3>Nombre de signalements: <?= $com->report ?></h3>
                <a href="index.php?action=deleteCom&amp;id=<?= $com->id ?>" class="boutonRouge">supprimer</a>
            </div>
        <?php endforeach; ?>
            <div class="backNext<?php if($totalpages < 2) echo 'Hidden'?>">
            <a href="index.php?action=commentsPage&amp;currentpage=<?= $currentpage - 1?>&amp;#titre"><div class="left"></div>Page précédente</a>
            <span class="currentPage"><?='Page ' . $currentpage . '/' . $totalpages?></span>
            <a href="index.php?action=commentsPage&amp;currentpage=<?= $currentpage + 1?>&amp;#titre">Page suivante<div class="right"></div></a>
            </div>
        </section>
    </div>