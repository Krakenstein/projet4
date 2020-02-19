<?php $title = 'Commentaires'; ?>

        <section id="content">
        <?php if (empty($allComs[0])): ?>
            <h1>Aucun commentaire posté pour le moment</h1>
        <?php else: ?>
            <h1>Liste des commentaires</h1>
            <div id="infosEp">
                <h3>Nombre total de commentaires: <?=$countcoms[0]?></h3>
            </div>
            <div class="backNext<?php if($totalpages < 2) echo 'Hidden'?>">
            <a href="index.php?action=commentsPage&amp;currentpage=<?= $currentpage - 1?>&amp;#titre" class="<?php if($currentpage === 1) echo 'hidden'?>"><div class="left"></div>Page précédente</a>
            <span class="currentPage"><?='Page ' . $currentpage . '/' . $totalpages?></span>
            <a href="index.php?action=commentsPage&amp;currentpage=<?= $currentpage + 1?>&amp;#titre" class="<?php if($currentpage == $totalpages ) echo 'hidden' ?>">Page suivante<div class="right"></div></a>
            </div>
            <?php foreach($allComs as $com): ?>
            <div class="comment">
                <span><b>Commentaire de l'épisode <?= $com->episodeNumber ?></b></span>
                <span>Posté <?= $com->commentDate ?></span><span>par <b><?= htmlspecialchars($com->author) ?></b></span>
                <p class="content"><?= htmlspecialchars($com->comment) ?></p>
                <h3>Nombre de signalements: <?= $com->report ?></h3>
                <div id="btnAction">
                    <a href="index.php?action=deleteCom&amp;id=<?= $com->id ?>" class="boutonRouge">Supprimer le commentaire</a>
                    <a href="index.php?action=deleteRep&amp;id=<?= $com->id ?>" class="bouton">Retirer les signalements</a>
                </div>
            </div>
        <?php endforeach; ?>
            <div class="backNext<?php if($totalpages < 2) echo 'Hidden'?>">
            <a href="index.php?action=commentsPage&amp;currentpage=<?= $currentpage - 1?>&amp;#titre" class="<?php if($currentpage === 1) echo 'hidden'?>"><div class="left"></div>Page précédente</a>
            <span class="currentPage"><?='Page ' . $currentpage . '/' . $totalpages?></span>
            <a href="index.php?action=commentsPage&amp;currentpage=<?= $currentpage + 1?>&amp;#titre" class="<?php if($currentpage == $totalpages ) echo 'hidden' ?>">Page suivante<div class="right"></div></a>
            </div>
        <?php endif; ?>
        </section>
    </div>