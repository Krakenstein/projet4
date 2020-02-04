<?php $title = 'Episodes'; ?>

<?php ob_start(); ?>
    <nav class="navigation-bar">
        <a href="#foot" id="signature"><h2>Jean Forteroche</h2></a>
        <div id="navbar_accueil">
        <a class="btn" href="index.php">Accueil</a>
                <a class="btn active" href="index.php?action=listEpisodes">Episodes</a>
        </div>
    </nav>
    <header>
        <img id="imgHeader" src="public/images/headerEpisodes.jpg" alt="forest by Daniel H. Tong"/>
    </header>
    <section>
        <h1 id="titre">Billet simple pour l'Alaska</h1>
        <h2>Aucun épisode n'est encore publié...</h2>
    </section>
<?php$posts->closeCursor();?>
<?php $content = ob_get_clean(); ?>

<?php require('templates/frontend/templatefront.php'); ?>