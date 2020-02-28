<?php $title = 'Nouvel épisode'; ?>

    <section id="content">
        <form id="modification"  action="index.php?action=addEpisode" method="post">
            <h3>Episode</h3>
            <input type="hidden" name="csrf" value="<?php echo $token ?>">
            <input title="chapterNumber" class="champ" type="text" name="chapterNumber" id="number" placeholder="Numéro" size="5" value="<?php echo $chapterNumber ?>"/>
            <label id="labelChapterNumber" for="chapterNumber"></label>
            <h3>Titre de l'épisode</h3>
            <input title="title" class="champ" type="text" name="title" id="titre" placeholder="Titre de l'épisode" size="45" value="<?php echo $titleEp ?>"/>
            <label id="labelTitle" for="title"><?php echo $error ?></label>
            <textarea title="episode" name="content" id="episode" cols="150" rows="50"><?php echo $content ?></textarea>
            <label id="labelEpisode" for="episode"></label>
            <div id="btnAction">
                <input type="submit" class="bouton" name="save" value="Sauvegarder">
                <input type="submit" class="boutonVert" name="publish" value="Publier">
            </div>
        </form>
    </section>
    
