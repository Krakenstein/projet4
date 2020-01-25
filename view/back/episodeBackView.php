<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="public/css/styleBack.css" />
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({selector:'textarea',
        toolbar: 'undo redo | styleselect | bold italic backcolor| alignleft aligncenter alignright alignjustify |',
        menubar: false,
        width: 950});</script>
    <title>episode-backoffice</title>
</head>
<body>
    <header>
        <h2>Billet simple pour l'Alaska</h2>
        <h2>Interface administrateur</h2>
        <h2>Jean Forteroche</h2>
    </header>
    <div id="main">
        <nav>
            <a class="btn" href="index.php?action=admConnect">Episodes</a>
            <a class="btn" href="index.php?action=createEpisode">Nouvel épisode</a>
            <a class="btn" href="index.php?action=profil">Profil</a>
            <a class="btn" href="index.php?action=disconnection">Déconnection</a>
        </nav>
        <section id="content">
            <form id="modification" action="index.php?action=modifiedEpisode&amp;nb=<?= $episode->chapterNumber ?>" method="post">
                <h3>Gestion de l'épisode</h3>
                <input title="chapterNumber" class="champ" type="text" name="nvchapter" id="number" value="<?= $episode->chapterNumber ?>" size="5" readonly="readonly />
                <label id="labelChapterNumber" for="chapterNumber"></label>
                <h3>Statut: <?php if(($episode->stat) == 1) echo 'publié'?><?php if(($episode->stat) == 0) echo 'archivé'?></h3>
                <h3>Le <?= $episode->creationDate ?></h3>
                <h3>Titre de l'épisode</h3><input title="title" class="champ" type="text" name="nvtitle" id="titre" value="<?= $episode->title ?>" size="45"/>
                <label id="labelTitle" for="title"></label>
                <textarea title="content" name="nvcontent" id="episode" cols="150" rows="50"><?= $episode->content ?></textarea>
                    <label id="labelEpisode" for="content"></label>
                <div id="btnAction">
                    <input type="submit" class="bouton" name="save" value="Archiver">
                    <input type="submit" class="boutonVert" name="publish" value="Publier">
                    <input type="submit" class="boutonRouge" name="delete" value="Supprimmer">
                </div>
            </form>
 

            <h3>Commentaires</h3>

            <?php foreach($comments as $com): ?>
            <div class="comment">
                <span><?= $com->commentDate ?></span><span>par <b><?= $com->author ?></b></span>
                <p class="content"><?= $com->comment ?></p>
                <h3>Nombre de signalements: <?= $com->report ?></h3>
                <a href="index.php?action=deleteCom&amp;id=<?= $com->id ?>&amp;nb=<?= $com->episodeNumber ?>&amp;chpt=<?= $com->episodeNumber ?>" class="boutonRouge">supprimer</a>
            </div>
        <?php endforeach; ?>
        </section>
    </div>
</body>
</html>