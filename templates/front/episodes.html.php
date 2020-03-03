<?php $title = 'Episodes'; ?>

    <header>
        <img id="imgHeader" src="images/headerEpisodes.jpg" alt="forest by Daniel H. Tong"/>
    </header>
    <section>
    <?php if (empty($pagina)): ?>
        <h1 id="titre">Billet simple pour l'Alaska</h1>
        <h2>Aucun épisode n'est encore publié...</h2>
    <?php else: ?>
        <h1 id="titre">Billet simple pour l'Alaska</h1>
        <h2>Liste des épisodes</h2>
        <div class="backNext<?php if($totalpages < 2) echo 'Hidden'?>">
            <a href="index.php?action=listEpisodes&amp;currentpage=<?= $currentpage - 1?>&amp;#titre" class="<?php if($currentpage === 1) echo 'hidden'?>"><div class="left"></div>Page précédente</a>
            <span class="currentPage"><?='Page ' . $currentpage . '/' . $totalpages?></span>
            <a href="index.php?action=listEpisodes&amp;currentpage=<?= $currentpage + 1?>&amp;#titre" class="<?php if($currentpage === $totalpages ) echo 'hidden' ?>">Page suivante<div class="right"></div></a>
        </div>
   
        <?php foreach($pagina as $episode): ?>
        <a href="index.php?action=episodePage&amp;id=<?= $episode->post_id ?>" class="episodeListe">
            <p><i><?= $episode->date ?></i></p>
            <span>Episode n°<?= $episode->chapterNumber ?></span>
            <span class="episodeTitle"><?= $episode->title ?></span>
            <div class="extrait">
                <?php 
                if (strlen($episode->content) > 2000)
                {
                    $espace = strpos($episode->content,' ', 2000); 
                    $extr = substr($episode->content,0,$espace).'...';
                    echo strip_tags(htmlspecialchars_decode($extr));
                }else{echo strip_tags(htmlspecialchars_decode($episode->content));}
                ?>
            
            </div>
            <div class="read">Lire la suite</div>
        </a>
        <?php endforeach; ?>
        <div class="backNext<?php if($totalpages < 2) echo 'Hidden'?>">
            <a href="index.php?action=listEpisodes&amp;currentpage=<?= $currentpage - 1?>&amp;#titre" class="<?php if($currentpage === 1) echo 'hidden'?>"><div class="left"></div>Page précédente</a>
            <span class="currentPage"><?='Page ' . $currentpage . '/' . $totalpages?></span>
            <a href="index.php?action=listEpisodes&amp;currentpage=<?= $currentpage + 1?>&amp;#titre" class="<?php if($currentpage === $totalpages ) echo 'hidden' ?>">Page suivante<div class="right"></div></a>
        </div>
    <?php endif; ?>
    </section>
