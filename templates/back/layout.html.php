<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="css/styleBack.css" />
        <script src="https://cdn.tiny.cloud/1/megygorehyq07d08ikdzcz9cckf4kuxryrgvmc533dogxs8y/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <script>tinymce.init({selector:'textarea',
            toolbar: 'undo redo | styleselect | bold italic backcolor| alignleft aligncenter alignright alignjustify |',
            menubar: false,
            width: 950});</script>
            <title><?= $title ?></title>
    </head>

    <body>
    <header>
        <h2>Billet simple pour l'Alaska</h2>
        <h2>Interface administrateur</h2>
        <h2>Jean Forteroche</h2>
    </header>
    <div id="main">
        <nav>
            <a class="btn<?php if(!empty($_GET['action']) && $_GET['action'] === 'episodes') echo (' active') ?>" href="index.php?action=episodes">Episodes</a>
            <a class="btnCom<?php if(($countcoms[0])  < 1) echo 'Blue'?><?php if(($sum[0]) > 0) echo 'Red'?><?php if(!empty($_GET['action']) && $_GET['action'] === 'comPage') echo ('Active') ?>" href="index.php?action=comPage">Commentaires</a>
            <a class="btn<?php if(!empty($_GET['action']) && $_GET['action'] === 'profil' || $_GET['action'] ==='reset') echo (' active') ?>" href="index.php?action=profil">Profil</a>
            <a class="btn<?php if(!empty($_GET['action']) && $_GET['action'] === 'disconnection') echo (' active') ?>" href="index.php?action=disconnection">Déconnection</a>
        </nav>
        <?= $content ?>
    </body>
</html>