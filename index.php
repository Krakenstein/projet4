<?php
require('src/controller/frontController.php');
require('src/controller/backController.php');


try {
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'listEpisodes') {//on affiche la liste des épisodes dans le front
            $controller = new frontController();
            $controller->listEpisodes();
        }
        elseif ($_GET['action'] == 'connection') {//on affiche la page de connection dans le front
            $controller = new frontController();
            $controller->connectionPage();           
        }
        elseif ($_GET['action'] == 'episode') {//on affiche un épisode dans le front
            $controller = new frontController();
            $controller->episode();
        }
        elseif ($_GET['action'] == 'admConnect') {//on affiche la page d'accueil-liste des épisodes dans le back
            $controller = new backController();
            $controller->admConnect();
        }
        elseif ($_GET['action'] == 'createEpisode') {//on affiche la page de création d'un nouvel épisode dans le back
            $controller = new backController();
            $controller->createEpisode();
        }
        elseif ($_GET['action'] == 'profil') {//on affiche la page profil dans le back
            $controller = new backController();
            $controller->profil();
        }
        elseif ($_GET['action'] == 'deleteCom') {//action pour supprimmer un commentaire depuis le back
            $controller = new backController();
            $controller->commentDelete();
        }
        elseif ($_GET['action'] == 'modifyEpisode') {// action pour se rendre à la page de modification d'épisode dans le back
            $controller = new backController();
            $controller->modifyEpisode();
        }
        elseif ($_GET['action'] == 'addEpisode') { //rajoute un épisode dans la bdd, publié ou archivé en fonction du bouton cliqué
            $controller = new backController();
            $controller->addEpisode();
        }
        elseif ($_GET['action'] == 'modifiedEpisode') { //modifie ou supprime un épisode dans la bdd, publié ou archivé en fonction du bouton cliqué
            $controller = new backController();
            $controller->episodeModications();
        }
        elseif ($_GET['action'] == 'disconnection') {//on se déconnecte du back et on revient à l'accueil du front
            $controller = new backController();
            $controller2 = new frontController();
            $controller->disconnection();
            $controller2->homePage();
        }
        elseif ($_GET['action'] == 'addComment') {//rajoute un commentaire, l'associe à un épisode et l'affiche sur la page du dit épisode
            $controller = new frontController();
            $controller->newCom();
        }       
        elseif ($_GET['action'] == 'report') {//pour signaler un commentaire
            $controller = new frontController();
            $controller->report();
        }
    }
    else {//si aucune action stipulée on affiche l'accueil du front
        $controller = new frontController();
        $controller->homePage();
    }
}
catch(Exception $e) { 
    echo 'Erreur : ' . $e->getMessage();
}