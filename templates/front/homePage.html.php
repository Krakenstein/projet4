<?php $title = 'Billet simple pour l\'Alaska'; ?>

        <header>
            <img id="imgHeader" src="images/header.jpg" alt="old man by mari lezhava"/>
            <h1 id="titre">Billet simple pour l'Alaska</h1>
            <h3 id="slogan">"Un feuilleton numérique et littéraire de Jean Forteroche"</h3>
        </header>
        <?php if (!empty($lastEpisode)): ?>
        <section>
            <h2>Dernier épisode mis en ligne</h2>
            <a href="index.php?action=episodePage&amp;id=<?= $lastEpisode[0]->post_id ?>" class="episodeListe">
                <p><i><?= $lastEpisode[0]->date ?></i></p>
                <span>Episode n°<?= $lastEpisode[0]->chapterNumber ?></span>
                <span class="episodeTitle"><?= $lastEpisode[0]->title ?></span>
                <div class="extrait">
                    <?php 
                    if (strlen($lastEpisode[0]->content) > 2000)
                    {
                        $espace = strpos($lastEpisode[0]->content,' ', 2000); 
                        $extr = substr($lastEpisode[0]->content,0,$espace);
                        echo strip_tags(htmlspecialchars_decode($extr)).'(...)';
                    }else{echo strip_tags(htmlspecialchars_decode($lastEpisode[0]->content));}
                    ?>        
                </div>
                <div class="read">Lire la suite</div>
            </a>
            <a class="anchor" href="#navbar_accueil">Retourner en haut de la page</a>
        </section>
        <?php endif; ?>
