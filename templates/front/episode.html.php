<?php if (empty($episode)): ?>
    <?php $title = 'erreur'; ?>
<?php else: ?>
    <?php $title = $episode[0]->title; ?>
<?php endif; ?>
    
    <section>
    <?php if (empty($episode)): ?>
        <h1 id="titre">Billet simple pour l'Alaska</h1>
        <h2>Cet épisode n'existe pas...</h2>
    <?php else: ?>
        <h1 id="titre">Billet simple pour l'Alaska</h1>
        <h3>Episode n°<?= $episode[0]->chapterNumber ?></h3>
        <h3>Publié <?= $episode[0]->date ?></h3>
        <a class="anchor" href="#fin">Aller à la fin de l'épisode</a>
        <div class="backNext<?php if($totalpages < 2) echo 'Hidden'?>">
            <a href="index.php?action=previous&amp;id=<?= $episode[0]->post_id ?>" class="<?php if($currentpage < 2) echo 'hidden'?>"><div class="left"></div>Episode précédent</a>
            <span class="currentPage"><?='Page ' . $currentpage . '/' . $totalpages?></span>
            <a href="index.php?action=next&amp;id=<?= $episode[0]->post_id ?>" class="<?php if($currentpage === $totalpages ) echo 'hidden' ?>">Episode suivant<div class="right"></div></a>
        </div>
        <h2><?= $episode[0]->title ?></h2>
        <div id="chapitre"><?= htmlspecialchars_decode($episode[0]->content) ?></div>
        <div class="backNext<?php if($totalpages < 2) echo 'Hidden'?>">
            <a href="index.php?action=previous&amp;id=<?= $episode[0]->post_id ?>" class="<?php if($currentpage < 2) echo 'hidden'?>"><div class="left"></div>Episode précédent</a>
            <span class="currentPage"><?='Page ' . $currentpage . '/' . $totalpages?></span>
            <a href="index.php?action=next&amp;id=<?= $episode[0]->post_id ?>" class="<?php if($currentpage === $totalpages ) echo 'hidden' ?>">Episode suivant<div class="right"></div></a>
        </div>
        <a class="anchor" id="fin" href="#titre">Revenir au début de l'épisode</a>
    </section>
    <section class="comments">
    <?php if ($episode[0]->author): ?>
        <h2 id="headCom">Commentaires</h2>    
        <?php foreach($episode as $com): ?>
        <div class="comment">
            <span><?= $com->commentDate ?></span><span>par <b><?= htmlspecialchars($com->author) ?></b></span>
            <p class="content"><?= wordwrap(htmlspecialchars($com->comment) , 30 , ' ' , true ) ?></p>
            <a href="index.php?action=report&amp;currentpage=<?= $currentpage ?>&amp;comId=<?= $com->id ?>&amp;rp=<?= $com->report ?>&amp;id=<?= $com->post_id ?>" type="submit" class="<?php if(($com->report) < 24) {echo 'reporting';} else {echo 'reported';}?>"><?php if(($com->report) > 0 && ($com->report)< 24) {echo 'Signalé ',  $com->report, ' fois';} if(($com->report) > 23) {echo 'Maximum de signalements atteint';} if(($com->report) < 1) {echo 'Signaler';}?></a>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
        <form id="makeComment" action="index.php?action=newCom&amp;id=<?= $episode[0]->post_id ?>#makeComment" method="post">
            <h2>Laissez moi un commentaire</h2>
            <input type="hidden" name="csrf" value="<?php echo $token ?>">
            <input title="author" class="champ" type="text" name="author" id="author" placeholder="Votre pseudo" value="<?php echo $pseudo ?>" size="15"/>
            <label id="labelAuthor" for="author"><?php echo $error ?></label>
            <p>Votre commentaire</p>
            <textarea title="comment" name="comment" id="comment" cols="40" rows="5"><?php echo $comment ?></textarea>
            <label id="labelComment" for="comment"></label>
            <input type="submit" class="bouton" value="envoyer">
        </form>
        <?php endif; ?>
    </section>


    