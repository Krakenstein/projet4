<?php $title = 'Billet simple pour l\'Alaska'; ?>

<?php ob_start(); ?>
        <nav class="navigation-bar">
            <a href="#foot" id="signature"><h2>Jean Forteroche</h2></a>
            <div id="navbar_accueil">
                <a class="btn active" href="index.php">Accueil</a>
                <a class="btn" href="index.php?action=listEpisodes">Episodes</a>
            </div>
        </nav>
        <header>
            <img id="imgHeader" src="public/images/header.jpg" alt="old man by mari lezhava"/>
            <h1 id="titre">Billet simple pour l'Alaska</h1>
            <h3 id="slogan">"Un feuilleton numérique et littéraire de Jean Forteroche"</h3>
        </header>
        
<?php$posts->closeCursor();?>
<?php $content = ob_get_clean(); ?>

<?php require('templates/frontend/templatefront.php'); ?>