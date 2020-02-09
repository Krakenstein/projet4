<?php $title = 'Liste des épisodes'; ?>

    <header>
        <h2>Billet simple pour l'Alaska</h2>
        <h2>Interface administrateur</h2>
        <h2>Jean Forteroche</h2>
    </header>
    <div id="main">
        <nav>
            <a class="btn active" href="index.php?action=episodes">Episodes</a>
            <a class="btn<?php if(($countcoms[0])  < 1) echo 'Hidden'?><?php if(($sum->value_sum) > 0) echo 'Red'?>" href="index.php?action=commentsPage">Commentaires</a>
            <a class="btn" href="index.php?action=profil">Profil</a>
            <a class="btn" href="index.php?action=disconnection">Déconnection</a>
        </nav>
        <section id="content">
            <h1>Aucun épisode pour le moment</h1>
            <a href="index.php?action=createEpisode" class="bouton">Créer un nouvel épisode</a>
        </section>
    </div>
