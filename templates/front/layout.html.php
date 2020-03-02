<?php
use Projet4\Tools\Request;
$request = new Request();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="icon" href="images/fj.png" />
        <link rel="stylesheet" href="css/style.css" />
        <title><?= $title ?></title>
    </head>

    <nav class="navigation-bar">
        <a href="#foot" id="signature"><h2>Jean Forteroche</h2></a>
        <div id="navbar_accueil">
            <a class="btn<?php if(empty($request->get('action'))) echo (' active') ?>" href="index.php">Accueil</a>
            <a class="btn<?php if(!empty($request->get('action')) && $request->get('action') === 'listEpisodes') echo (' active') ?>" href="index.php?action=listEpisodes">Episodes</a>
        </div>
    </nav>

    <body>
        <?= $content ?>
    </body>

    <footer id="foot">
            <a class="btn<?php if(!empty($request->get('action')) && $request->get('action') === 'connectionPage') echo (' active') ?>" href="index.php?action=connectionPage">Administrateur</a>
    </footer>
</html>