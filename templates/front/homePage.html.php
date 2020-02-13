<?php $title = 'Billet simple pour l\'Alaska'; ?>


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
        <?php if ($lastEpisode != false): ?>
        <section>
            <h2>Dernier épisode mis en ligne</h2>
            <h3>Episode n°<?= $lastEpisode->chapterNumber ?> publié le <?= $lastEpisode->date ?></h3>
            <a class="anchor" href="index.php?action=episodePage&amp;currentpage=<?= array_search($lastEpisode->post_id, array_column($pagina, 'post_id')) + 1 ?>">Aller sur la page de l'épisode</a>
            <h2><?= $lastEpisode->title?></h2>    
            <div id="chapitre"><?= $lastEpisode->content ?></div>
            <a class="anchor" href="index.php?action=episodePage&amp;currentpage=<?= array_search($lastEpisode->post_id, array_column($pagina, 'post_id')) + 1 ?>">Aller sur la page de l'épisode</a>
            <a class="anchor" href="#navbar_accueil">Retourner en haut de la page</a>
        </section>
        <?php endif; ?>
