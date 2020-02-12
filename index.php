<?php
require('vendor/autoload.php');

use Projet4\Controller\frontController;
use Projet4\Controller\backController;




try {
    if (isset($_GET['action'])) {
        if (($_GET['action'] == 'listEpisodes') ||
            ($_GET['action'] == 'connection') ||
            ($_GET['action'] == 'episode') ||
            ($_GET['action'] == 'episodePage') ||
            ($_GET['action'] == 'previous') ||
            ($_GET['action'] == 'next') ||
            ($_GET['action'] == 'addComment') ||
            ($_GET['action'] == 'report')){
                $controller = new frontController();
            }else{
                $controller = new backController();
            }
        if ($_GET['action'] == 'listEpisodes') {//on affiche la liste des épisodes dans le front
            
            $controller->listEpisodes();
        }
        elseif ($_GET['action'] == 'connection') {//on affiche la page de connection dans le front
            
            $controller->connectionPage();           
        }
        elseif ($_GET['action'] == 'episode') {//on affiche un épisode dans le front
            
            $controller->episode();
        }
        elseif ($_GET['action'] == 'episodePage') {//on affiche un épisode dans le front
            
            $controller->episodePage();
        }
        elseif ($_GET['action'] == 'previous') {//on affiche un épisode dans le front
            
            $controller->previous();
        }
        elseif ($_GET['action'] == 'next') {//on affiche un épisode dans le front
           
            $controller->next();
        }
        elseif ($_GET['action'] == 'admConnect') {//on affiche la page d'accueil-liste des épisodes dans le back
            
            $controller->admConnect();
        }
        elseif ($_GET['action'] == 'episodes') {//on affiche la page d'accueil-liste des épisodes dans le back
            
            $controller->episodes();
        }
        elseif ($_GET['action'] == 'createEpisode') {//on affiche la page de création d'un nouvel épisode dans le back
            
            $controller->createEpisode();
        }
        elseif ($_GET['action'] == 'resetAdmin') {//on affiche la page profil dans le back
            
            $controller->reset();
        }
        elseif ($_GET['action'] == 'profil') {//on affiche la page profil dans le back
            
            $controller->profil();
        }
        elseif ($_GET['action'] == 'deleteComEpisode') {//action pour supprimmer un commentaire depuis la page épisode du back
            
            $controller->commentDelete();
        }
        elseif ($_GET['action'] == 'deleteCom') {//action pour supprimmer un commentaire depuis la page commentaires du back
            
            $controller->comDelete();
        }
        elseif ($_GET['action'] == 'deleteRep') {//action pour supprimmer un commentaire depuis la page commentaires du back
            
            $controller->deleteR();
        }
        elseif ($_GET['action'] == 'commentsPage') {//action pour supprimmer un commentaire depuis le back
            
            $controller->comPage();
        }
        elseif ($_GET['action'] == 'modifyEpisode') {// action pour se rendre à la page de modification d'épisode dans le back
            
            $controller->modifyEpisode();
        }
        elseif ($_GET['action'] == 'addEpisode') { //rajoute un épisode dans la bdd, publié ou archivé en fonction du bouton cliqué
            
            $controller->addEpisode();
        }
        elseif ($_GET['action'] == 'modifiedEpisode') { //modifie ou supprime un épisode dans la bdd, publié ou archivé en fonction du bouton cliqué
            
            $controller->episodeModications();
        }
        elseif ($_GET['action'] == 'disconnection') {//on se déconnecte du back et on revient à l'accueil du front
            
            $controller->disconnection();
        }
        elseif ($_GET['action'] == 'addComment') {//rajoute un commentaire, l'associe à un épisode et l'affiche sur la page du dit épisode
            
            $controller->newCom();
        }       
        elseif ($_GET['action'] == 'report') {//pour signaler un commentaire
            
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