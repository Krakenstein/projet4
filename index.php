<?php
require('src/controller/frontend.php');
require('src/controller/backend.php');


try {
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'listEpisodes') {//on affiche la liste des épisodes dans le front
            listEpisodes();
        }
        elseif ($_GET['action'] == 'connection') {//on affiche la page de connection dans le front
            connectionPage();           
        }
        elseif ($_GET['action'] == 'episode') {//on affiche un épisode dans le front
            episode();
        }
        elseif ($_GET['action'] == 'admConnect') {//on affiche la page d'accueil-liste des épisodes dans le back
            admConnect();
        }
        elseif ($_GET['action'] == 'createEpisode') {//on affiche la page de création d'un nouvel épisode dans le back
            createEpisode();
        }
        elseif ($_GET['action'] == 'profil') {//on affiche la page profil dans le back
            profil();
        }
        elseif ($_GET['action'] == 'deleteCom') {//action pour supprimmer un commentaire depuis le back
            commentDelete();
        }
        elseif ($_GET['action'] == 'modifyEpisode') {// action pour se rendre à la page de modification d'épisode dans le back
            modifyEpisode();
        }
        elseif ($_GET['action'] == 'addEpisode') { //rajoute un épisode dans la bdd, publié ou archivé en fonction du bouton cliqué
            addEpisode();
        }
        elseif ($_GET['action'] == 'modifiedEpisode') { //modifie ou supprime un épisode dans la bdd, publié ou archivé en fonction du bouton cliqué
            episodeModications();
        }
        elseif ($_GET['action'] == 'disconnection') {//on se déconnecte du back et on revient à l'accueil du front
            disconnection();
            homePage();
        }
        elseif ($_GET['action'] == 'addComment') {//on se déconnecte du back et on revient à l'accueil du front
            newCom();
        }       
        elseif ($_GET['action'] == 'report') {//pour signaler un commentaire
            report();
        }
    }
    else {//si aucune action stipulée on affiche l'accueil du front
        homePage();
    }
}
catch(Exception $e) { 
    echo 'Erreur : ' . $e->getMessage();
}