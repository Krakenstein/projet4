<?php $title = 'Gestion d\'épisode'; ?>

    <header>
        <h2>Billet simple pour l'Alaska</h2>
        <h2>Interface administrateur</h2>
        <h2>Jean Forteroche</h2>
    </header>
    <div id="main">
        <nav>
            <a class="btn" href="index.php?action=episodes">Episodes</a>
            <a class="btnCom<?php if(($countcoms[0])  < 1) echo 'Blue'?><?php if(($sum->value_sum) > 0) echo 'Red'?>" href="index.php?action=commentsPage">Commentaires</a>
            <a class="btn" href="index.php?action=profil">Profil</a>
            <a class="btn" href="index.php?action=disconnection">Déconnection</a>
        </nav>
        <section id="content">
            <form id="modification" action="index.php?action=modifiedEpisode&amp;id=<?= $episode->post_id ?>&amp;dt=<?= $episode->publiDate ?>" method="post">
                <h3>Gestion de l'épisode</h3>
                <input title="chapterNumber" class="champ" type="text" name="nvchapter" id="number" value="<?php if (isset($_SESSION['chapterNumber'])) echo $_SESSION['chapterNumber']; else echo $episode->chapterNumber; ?>" size="5" />
                <label id="labelChapterNumber" for="chapterNumber"></label>
                <h3>Statut: <?php if(($episode->stat) == 1) echo 'publié'?><?php if(($episode->stat) == 0) echo 'Sauvegardé'?></h3>
                <h3><?php if(($episode->publiDate) != null) echo 'Publié ' . $episode->publiDate ?></h3>
                <select id="dateChoice<?php if(($episode->publiDate) === null) echo 'Hidden' ?>" name="dateChoice">
                    <option value="oldDate">Garder cette date de publication</option>
                    <option value="newDate">Republier à la date de maintenant</option>
                </select>
                <h3>Titre de l'épisode</h3><input title="title" class="champ" type="text" name="nvtitle" id="titre" value="<?php if (isset($_SESSION['title'])) echo $_SESSION['title']; else echo $episode->title; ?>" size="45"/>
                <label id="labelTitle" for="title"><?php if(isset($error)) echo $error ?></label>
                <textarea title="content" name="nvcontent" id="episode" cols="150" rows="50"><?php if (isset($_SESSION['content'])) echo $_SESSION['content']; else echo $episode->content; ?></textarea>
                    <label id="labelEpisode" for="content"></label>
                <div id="btnAction">
                    <input type="submit" class="bouton" name="save" value="Sauvegarder">
                    <input type="submit" class="boutonVert" name="publish" value="Publier">
                    <input type="submit" class="boutonRouge" name="delete" value="Supprimmer">
                </div>
            </form>
 

            <h3><?php if(($countcoms[0])  < 1) echo ''?><?php if(($sum->value_sum) > 0) echo 'Commentaires'?></h3>

            <?php foreach($comments as $com): ?>
            <div class="comment">
                <span><?= $com->commentDate ?></span><span>par <b><?= htmlspecialchars($com->author) ?></b></span>
                <p class="content"><?= htmlspecialchars($com->comment) ?></p>
                <h3>Nombre de signalements: <?= $com->report ?></h3>
                <a href="index.php?action=deleteComEpisode&amp;id=<?= $com->id ?>&amp;postid=<?= $com->post_id ?>&amp;chpt=<?= $com->episodeNumber ?>" class="boutonRouge">supprimer</a>
            </div>
        <?php endforeach; ?>
        </section>
    </div>
