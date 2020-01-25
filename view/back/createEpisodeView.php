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
    <title>ecrire-backoffice</title>
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
            <a class="btn active" href="creer.html">Nouvel épisode</a>
            <a class="btn" href="profil.html">Profil</a>
            <a class="btn" href="accueil.html">Déconnection</a>
        </nav>
        <section id="content">
            <form id="modification"  action="index.php?action=addEpisode" method="post">
                <h3>Episode</h3>
                <input title="chapterNumber" class="champ" type="text" name="chapterNumber" id="number" placeholder="Numéro" size="5"/>
                <label id="labelChapterNumber" for="chapterNumber"></label>
                <h3>Titre de l'épisode</h3><input title="title" class="champ" type="text" name="title" id="titre" placeholder="Titre de l'épisode" size="45"/>
                <label id="labelTitle" for="title"></label>
                <textarea title="episode" name="content" id="episode" cols="150" rows="50"></textarea>
                <label id="labelEpisode" for="episode"></label>
                <div id="btnAction">
                    <input type="submit" class="bouton" name="save" value="Archiver">
                    <input type="submit" class="boutonVert" name="publish" value="Publier">
                </div>
            </form>
        </section>
    </div>
</body>