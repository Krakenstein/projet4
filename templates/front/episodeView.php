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
        <h3>Publié le <?= $episode->creationDate ?></h3>
        <h2><?= $episode->title ?></h2>
        
        <div id="chapitre"><?= $episode->content ?></div>
        
        <div id="backNext">
            <a href="index.php?action=episode&amp;nb=<?= $episode->chapterNumber - 1 ?>"><div class="left"></div>Episode précédent</a>
            <a href="index.php?action=episode&amp;nb=<?= $episode->chapterNumber + 1 ?>">Episode suivant<div class="right"></div></a>
        </div>
    </section>
    <section class="comments">
        <h2 id="headCom">Commentaires</h2>

        <?php foreach($comments as $com): ?>
        <div class="comment">
            <span><?= $com->commentDate ?></span><span>par <b><?= $com->author ?></b></span>
            <p class="content"><?= $com->comment ?></p>
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


    