<?php $title = 'Gestion d\'épisode'; ?>

        <section id="content">
        <?php if (empty($episode[0])): ?>
            <h1>Cet épisode n'existe pas</h1>
        <?php else: ?>
            <form id="modification" action="index.php?action=episodeModications&amp;postId=<?= $episode[0]->post_id ?>&amp;dt=<?= $episode[0]->publiDate ?>" method="post">
                <h3>Gestion de l'épisode</h3>
                <input type="hidden" name="csrf" value="<?php echo $token ?>">
                <input title="chapterNumber" class="champ" type="text" name="nvchapter" id="number" value="<?php if ($chapterNumber !== null) echo $chapterNumber; else echo $episode[0]->chapterNumber; ?>" size="5" />
                <label id="labelChapterNumber" for="chapterNumber"></label>
                <h3>Statut: <?php if(($episode[0]->stat) === 1) echo 'publié'?><?php if(($episode[0]->stat) === 0) echo 'Sauvegardé'?></h3>
                <h3><?php if(($episode[0]->publiDate) != null) echo 'Publié ' . $episode[0]->date ?></h3>
                <select id="dateChoice<?php if(($episode[0]->publiDate) === null) echo 'Hidden' ?>" name="dateChoice">
                    <option value="oldDate">Garder cette date de publication</option>
                    <option value="newDate">Republier à la date de maintenant</option>
                </select>
                <h3>Titre de l'épisode</h3><input title="title" class="champ" type="text" name="nvtitle" id="titre" value="<?php if ($titleEp !== null) echo $titleEp; else echo $episode[0]->title; ?>" size="45"/>
                <label id="labelTitle" for="title"><?php if(isset($error)) echo $error ?></label>
                <textarea title="content" name="nvcontent" id="episode[0]" cols="150" rows="50"><?php if ($content !== null) echo $content; else echo $episode[0]->content; ?></textarea>
                    <label id="labelepisode[0]" for="content"></label>
                <div id="btnAction">
                    <input type="submit" class="bouton" name="save" value="Sauvegarder">
                    <input type="submit" class="boutonVert" name="publish" value="Publier">
                    <input type="submit" class="boutonRouge" name="delete" value="Supprimmer">
                </div>
            </form>
            <?php if ($episode[0]->author): ?>
            <h3 id="headCom">Commentaires</h3>
            <?php foreach($episode as $com): ?>
            <div class="comment">
                <span><?= $com->commentDate ?></span><span>par <b><?= htmlspecialchars($com->author) ?></b></span>
                <p class="content"><?= htmlspecialchars($com->comment) ?></p>
                <h3>Nombre de signalements: <?= $com->report ?></h3>
                <div id="btnAction">
                    <a href="index.php?action=commentDelete&amp;id=<?= $com->id ?>&amp;postid=<?= $com->post_id ?>&amp;chpt=<?= $com->chapterNumber ?>" class="boutonRouge">supprimer</a>
                    <a href="index.php?action=deleteReportsFromEp&amp;id=<?= $com->id ?>&amp;postid=<?= $com->post_id ?>" class="bouton">Retirer les signalements</a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>
        </section>       
    </div>
