<?php $title = $episode->title; ?>

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
        <h3>Publié <?= $episode->date ?></h3>
        <div class="backNext">
            <a href="index.php?action=previous&amp;dt=<?= $episode->publiDate?>&amp;id=<?= $episode->post_id?>"><div class="left"></div>Episode précédent</a>
            <a class="currentPage" href="#headCom">Aller aux commentaires</a>
            <a href="index.php?action=next&amp;dt=<?= $episode->publiDate?>&amp;id=<?= $episode->post_id?>">Episode suivant<div class="right"></div></a>
        </div>
        <h2><?= $episode->title ?></h2>
        
        <div id="chapitre"><?= $episode->content ?></div>
        
        <div class="backNext">
            <a href="index.php?action=previous&amp;dt=<?= $episode->publiDate?>"><div class="left"></div>Episode précédent</a>
            <a class="currentPage" href="#titre">Revenir au début de l'épisode</a>
            <a href="index.php?action=next&amp;dt=<?= $episode->publiDate?>">Episode suivant<div class="right"></div></a>
        </div>
    </section>
    <section class="comments">
        <h2 id="headCom">Commentaires</h2>

        <?php foreach($comments as $com): ?>
        <div class="comment">
            <span><?= $com->commentDate ?></span><span>par <b><?= htmlspecialchars($com->author) ?></b></span>
            <p class="content"><?= htmlspecialchars($com->comment) ?></p>
            <a href="index.php?action=report&amp;id=<?= $com->id ?>&amp;nb=<?= $com->episodeNumber ?>&amp;postid=<?= $com->post_id ?>" type="submit" class="<?php if(($com->report) < 24) {echo 'reporting';} else {echo 'reported';}?>"><?php if(($com->report) > 0) {echo 'Signalé ',  $com->report, ' fois';} else {echo 'Signaler';}?></a>
        </div>
        <?php endforeach; ?>

        <form id="makeComment" action="index.php?action=addComment&amp;nb=<?= $episode->chapterNumber ?>&amp;id=<?= $episode->post_id ?>" method="post">
            <h2>Laissez moi un commentaire</h2>
            <input title="author" class="champ" type="text" name="author" id="author" placeholder=" Votre pseudo" size="15"/>
            <label id="labelAuthor" for="author"><?php echo $error ?></label>
            <p>Votre commentaire</p>
            <textarea title="comment" name="comment" id="comment" cols="40" rows="5"></textarea>
            <label id="labelComment" for="comment"></label>
            <input type="submit" class="bouton" value="envoyer">
        </form>
    </section>


    