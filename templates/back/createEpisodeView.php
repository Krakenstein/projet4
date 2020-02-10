<?php $title = 'Nouvel épisode'; ?>

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
            <form id="modification"  action="index.php?action=addEpisode" method="post">
                <h3>Episode</h3>
                <input title="chapterNumber" class="champ" type="text" name="chapterNumber" id="number" placeholder="Numéro" size="5" value="<?php if (isset($_SESSION['chapterNumber'])) echo $_SESSION['chapterNumber']; else echo ''; ?>"/>
                <label id="labelChapterNumber" for="chapterNumber"></label>
                <h3>Titre de l'épisode</h3><input title="title" class="champ" type="text" name="title" id="titre" placeholder="Titre de l'épisode" size="45" value="<?php if (isset($_SESSION['title'])) echo $_SESSION['title']; else echo ''; ?>"/>
                <label id="labelTitle" for="title"><?php echo $error ?></label>
                <textarea title="episode" name="content" id="episode" cols="150" rows="50"><?php if (isset($_SESSION['content'])) echo $_SESSION['content']; else echo ''; ?></textarea>
                <label id="labelEpisode" for="episode"></label>
                <div id="btnAction">
                    <input type="submit" class="bouton" name="save" value="Sauvegarder">
                    <input type="submit" class="boutonVert" name="publish" value="Publier">
                </div>
            </form>
        </section>
    </div>
