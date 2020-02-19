
<?php $title = $pagina[0]->title; ?>

    <section>

        <h1 id="titre">Billet simple pour l'Alaska</h1>
        <h3>Episode n°<?= $pagina[0]->chapterNumber ?></h3>
        <h3>Publié <?= $pagina[0]->date ?></h3>
        <a class="anchor" href="#headCom">Aller aux commentaires</a>
        <div class="backNext<?php if($totalpages < 2) echo 'Hidden'?>">
            <a href="index.php?action=episodePage&amp;currentpage=<?= $currentpage - 1?>&amp;#titre" class="<?php if($currentpage === 1) echo 'hidden'?>"><div class="left"></div>Episode précédent</a>
            <span class="currentPage"><?='Page ' . $currentpage . '/' . $totalpages?></span>
            <a href="index.php?action=episodePage&amp;currentpage=<?= $currentpage + 1?>&amp;#titre" class="<?php if($currentpage == $totalpages ) echo 'hidden' ?>">Episode suivant<div class="right"></div></a>
        </div>
        <h2><?= $pagina[0]->title ?></h2>
        
        <div id="chapitre"><?= $pagina[0]->content ?></div>

        <div class="backNext<?php if($totalpages < 2) echo 'Hidden'?>">
            <a href="index.php?action=episodePage&amp;currentpage=<?= $currentpage - 1?>&amp;#titre" class="<?php if($currentpage === 1) echo 'hidden'?>"><div class="left"></div>Episode précédent</a>
            <span class="currentPage"><?='Page ' . $currentpage . '/' . $totalpages?></span>
            <a href="index.php?action=episodePage&amp;currentpage=<?= $currentpage + 1?>&amp;#titre" class="<?php if($currentpage == $totalpages ) echo 'hidden' ?>">Episode suivant<div class="right"></div></a>
        </div>
        <a class="anchor" href="#titre">Revenir au début de l'épisode</a>
    </section>
    <section class="comments">
        <h2 id="headCom">Commentaires</h2>

        <?php foreach($comments as $com): ?>
        <div class="comment">
            <span><?= $com->commentDate ?></span><span>par <b><?= htmlspecialchars($com->author) ?></b></span>
            <p class="content"><?= htmlspecialchars($com->comment) ?></p>
            <a href="index.php?action=report&amp;id=<?= $com->id ?>&amp;rp=<?= $com->report ?>&amp;postid=<?= $com->post_id ?>&amp;currentpage=<?= $currentpage ?>" type="submit" class="<?php if(($com->report) < 24) {echo 'reporting';} else {echo 'reported';}?>"><?php if(($com->report) > 0 && ($com->report)< 24) {echo 'Signalé ',  $com->report, ' fois';} if(($com->report) > 23) {echo 'Maximum de signalements atteint';} if(($com->report) < 1) {echo 'Signaler';}?></a>
        </div>
        <?php endforeach; ?>

        <form id="makeComment" action="index.php?action=addComment&amp;currentpage=<?= $currentpage ?>&amp;nb=<?= $pagina[0]->chapterNumber ?>&amp;id=<?= $pagina[0]->post_id ?>" method="post">
            <h2>Laissez moi un commentaire</h2>
            <input title="author" class="champ" type="text" name="author" id="author" placeholder=" Votre pseudo" size="15"/>
            <label id="labelAuthor" for="author"><?php if(isset($error)) echo $error ?></label>
            <p>Votre commentaire</p>
            <textarea title="comment" name="comment" id="comment" cols="40" rows="5"></textarea>
            <label id="labelComment" for="comment"></label>
            <input type="submit" class="bouton" value="envoyer">
        </form>
    </section>


    