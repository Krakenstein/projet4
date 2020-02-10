<?php $title = 'Liste des épisodes'; ?>

    <header>
        <h2>Billet simple pour l'Alaska</h2>
        <h2>Interface administrateur</h2>
        <h2>Jean Forteroche</h2>
    </header>
    <div id="main">
        <nav>
            <a class="btn active" href="index.php?action=episodes">Episodes</a>
            <a class="btn<?php if(($countcoms[0])  < 1) echo 'Hidden'?><?php if(($sum->value_sum) > 0) echo 'Red'?>" href="index.php?action=commentsPage">Commentaires</a>
            <a class="btn" href="index.php?action=profil">Profil</a>
            <a class="btn" href="index.php?action=disconnection">Déconnection</a>
        </nav>
        <section id="content">
            <h1>Liste des épisodes</h1>
            <div id="infosEp">
                <h3>Nombre total d'épisodes: <?=$episodesTot[0]?></h3>
                <h3>Episodes publiés: <?=$episodesPubTot[0]?></h3>
            </div>
            <h3 id="message<?php if($message === null ) echo 'Hidden'?>"><?= $message?></h3>
            <a href="index.php?action=createEpisode" class="boutonVert">Créer un nouvel épisode</a>
            <div class="table">
                <div class="rowsTitle">
                    <div class="cell-title"><h4>Numéro</h4></div>
                    <div class="cell-title"><h4>titre</h4></div>
                    <div class="cell-title"><h4>Statut</h4></div>
                    <div class="cell-title"><h4>Commentaires</h4></div>
                    <div class="cell-title"><h4>Signalements</h4></div>
                </div>

            <?php foreach($tablesJoin as $episode): ?>
                <a class="rows" href="index.php?action=modifyEpisode&amp;id=<?= $episode->post_id ?>">
                    <div class="cell"><?= $episode->chapterNumber ?></div>
                    <div class="cell"><?= $episode->title ?></div>
                    <div class="cell"><?php if(($episode->stat) == 1) echo 'publié le ' . ($episode->date)?><?php if(($episode->stat) == 0) echo 'Sauvegardé'?></div>
                    <div class="cell"><?= $episode->commentsNb ?></div>
                    <div class="cell"><?php if(($episode->reportsNb) == null) echo '0'?><?php if(($episode->reportsNb) !== 0) echo $episode->reportsNb ?></div>
            <?php endforeach; ?>
                </a>
            </div>
            <div class="backNext<?php if($totalpages < 2) echo 'Hidden'?>">
            <a href="index.php?action=episodes&amp;currentpage=<?= $currentpage - 1?>&amp;#titre" class="<?php if($currentpage === 1) echo 'hidden'?>"><div class="left"></div>Page précédente</a>
            <span class="currentPage"><?='Page ' . $currentpage . '/' . $totalpages?></span>
            <a href="index.php?action=episodes&amp;currentpage=<?= $currentpage + 1?>&amp;#titre" class="<?php if($currentpage === $totalpages - 1) echo 'hidden' ; else echo 'visible'?>">Page suivante<div class="right"></div></a>
            </div>
        </section>
    </div>
