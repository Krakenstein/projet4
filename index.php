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
            if (isset($_GET['nb']) && $_GET['nb'] > 0) {
                episode();
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
        elseif ($_GET['action'] == 'admConnect') {//on affiche la page d'accueil-liste des épisodes dans le back
            session_start();
            if ((isset($_POST['password']) && $_POST['password'] == "123") or ($_SESSION['admConnected'] == true)){
                if ((isset($_POST['nom']) && $_POST['nom'] == "jean") or ($_SESSION['admConnected'] == true)){
                    admConnect();
                }
                else {
                    throw new Exception('Pseudo incorrect');
                }
                
            }
            else {
                throw new Exception('Mot de passe incorrect');
            }
        }
        elseif ($_GET['action'] == 'createEpisode') {//on affiche la page de création d'un nouvel épisode dans le back
            session_start();
            if ($_SESSION['admConnected'] == true) {
                createEpisode();
            }
            else {
                throw new Exception('Connectez-vous');
            }
        }
        elseif ($_GET['action'] == 'profil') {//on affiche la page de création d'un nouvel épisode dans le back
            session_start();
            if ($_SESSION['admConnected'] == true) {
                profil();
            }
            else {
                throw new Exception('Connectez-vous');
            }
        }
        elseif ($_GET['action'] == 'deleteCom') {//on affiche la page de création d'un nouvel épisode dans le back
            session_start();
            if ($_SESSION['admConnected'] == true) {
                commentDelete();
            }
            else {
                throw new Exception('Connectez-vous');
            }
        }
        elseif ($_GET['action'] == 'modifyEpisode') {
            if (isset($_GET['nb']) && $_GET['nb'] > 0) {//on affiche la page de modification d'un épisode dans le back
                modifyEpisode();
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
        elseif ($_GET['action'] == 'addEpisode') { //rajoute un épisode dans la bdd, publié ou archivé en fonction du bouton cliqué
            if (isset($_POST['publish'])) {
                if (!empty($_POST['chapterNumber']) && !empty($_POST['title'])) {
                    addPostedEpisode($_POST['chapterNumber'], $_POST['title'], $_POST['content']);
                    admConnect();
                }
                else {
                    throw new Exception('tous les champs ne sont pas remplis !');
                }
            }
            elseif (isset($_POST['save'])) {
                if (!empty($_POST['chapterNumber']) && !empty($_POST['title'])) {
                    addSavedEpisode($_POST['chapterNumber'], $_POST['title'], $_POST['content']);
                    admConnect();
                }
                else {
                    throw new Exception('tous les champs ne sont pas remplis !');
                }
            }
            else {
                throw new Exception('Erreur : aucun identifiant de billet envoyé');
            }
        }
        elseif ($_GET['action'] == 'modifiedEpisode') { //modifie ou supprime un épisode dans la bdd, publié ou archivé en fonction du bouton cliqué
            if (isset($_POST['publish'])) {
                if (!empty($_POST['nvchapter']) && !empty($_POST['nvtitle'])) {
                    modifyPostedEpisode($_POST['nvchapter'], $_POST['nvtitle'], $_POST['nvcontent']);
                    admConnect();
                }
                else {
                    throw new Exception('tous les champs ne sont pas remplis !');
                }
            }
            elseif (isset($_POST['save'])) {
                if (!empty($_POST['nvchapter']) && !empty($_POST['nvtitle'])) {
                    modifySavedEpisode($_POST['nvchapter'], $_POST['nvtitle'], $_POST['nvcontent']);
                    admConnect();
                }
                else {
                    throw new Exception('tous les champs ne sont pas remplis !');
                }
            }
            elseif (isset($_POST['delete'])) {
                if (isset($_GET['nb']) && $_GET['nb'] > 0) {
                    episodeDelete();
                    admConnect();
                }
                else {
                    throw new Exception(' aucun identifiant de billet envoyé !');
                }
            }
            else {
                throw new Exception('Erreur : aucun identifiant de billet envoyé');
            }
        }
        elseif ($_GET['action'] == 'disconnection') {//on se déconnecte du back et on revient à l'accueil du front
            session_start();
            if ($_SESSION['admConnected'] == true) {
                disconnection();
                homePage();
            }
            else {
                throw new Exception('Connectez-vous');
            }
        }
        elseif ($_GET['action'] == 'addComment') {//on rajoute un commentaire dans la bdd, on le compte dans la table posts et on l'affiche sur la page épisode associée
            if (isset($_GET['nb']) && $_GET['nb'] > 0) {
                if (!empty($_POST['author']) && !empty($_POST['comment'])) {
                    addComment($_GET['nb'], $_POST['author'], $_POST['comment']);
                    countCom($_GET['nb']);
                }
                else {
                    throw new Exception('tous les champs ne sont pas remplis !');
                }
            }
            else {
                throw new Exception('Erreur : aucun identifiant de billet envoyé');
            }
        }
        elseif ($_GET['action'] == 'report') {//pour signaler un commentaire
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                reportComment($_GET['id']);
            }
            else {
                throw new Exception('Erreur : aucun identifiant de commentaire envoyé');
            }
        }
    }
    else {//si aucune action stipulée on affiche l'accueil du front
        homePage();
    }
}
catch(Exception $e) { 
    echo 'Erreur : ' . $e->getMessage();
}