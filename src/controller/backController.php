<?php

require_once('src/model/episodeManager.php');
require_once('src/model/commentManager.php');
require_once('src/controller/controller.php');

class BackController extends Controller{

    function admConnect()//fonction pour se connecter au back
    {
        $episodeManager = new episodeManager();

        $tablesJoin = $episodeManager->joinTables();
        
        session_start();

        if ((isset($_POST['password']) && $_POST['password'] == "123") or ($_SESSION['admConnected'] == true)){
            if ((isset($_POST['nom']) && $_POST['nom'] == "jean") or ($_SESSION['admConnected'] == true)){
                $_SESSION['admConnected'] = true;
                require('src/view/back/homePageBackView.php');
            }
            else {
                throw new Exception('Pseudo incorrect');
            }        
        }
        else {
            throw new Exception('Mot de passe incorrect');
        }
        
    }

    function createEpisode()//fonction pour afficher la page de création d'épisode
    {
        session_start();
                
        if ($_SESSION['admConnected'] == true) {               
            require('src/view/back/createEpisodeView.php');
        }
        else {         
            throw new Exception('Connectez-vous');
        }  
    }

    function addEpisode()
    {
        session_start();
                
        if ($_SESSION['admConnected'] == true) { 
        
            if (isset($_POST['publish'])) {
                if (!empty($_POST['chapterNumber']) && !empty($_POST['title'])) {
                    $this->addPostedEpisode($_POST['chapterNumber'], $_POST['title'], $_POST['content']);
                    $episodeManager = new episodeManager();
                    $tablesJoin = $episodeManager->joinTables();
                    require('src/view/back/homePageBackView.php');
                }
                else {
                    throw new Exception('tous les champs ne sont pas remplis !');
                }
            }
            elseif (isset($_POST['save'])) {
                if (!empty($_POST['chapterNumber']) && !empty($_POST['title'])) {
                    $this->addSavedEpisode($_POST['chapterNumber'], $_POST['title'], $_POST['content']);
                    $episodeManager = new episodeManager();
                    $tablesJoin = $episodeManager->joinTables();
                    require('src/view/back/homePageBackView.php');
                }
                else {
                    throw new Exception('tous les champs ne sont pas remplis !');
                }
            }
            else {
                throw new Exception('Erreur : aucun identifiant de billet envoyé');
            }
        }
        else {         
            throw new Exception('Connectez-vous');
        }
    }

    function addPostedEpisode($episodeNumber, $title, $content)//fonction pour ajouter un épisode publié à la bdd
    {
        $episodeManager = new episodeManager();
        $postedEpisode = $episodeManager->postEpisode($episodeNumber, $title, $content);

    }

    function addSavedEpisode($episodeNumber, $title, $content)//fonction pour ajouter un épisode archivé à la bdd
    {
        $episodeManager = new episodeManager();
        $postedEpisode = $episodeManager->saveEpisode($episodeNumber, $title, $content);

    }

    function episodeModications()
    {
        session_start();
                
        if ($_SESSION['admConnected'] == true) {
        
            if (isset($_POST['publish'])) {
                if (!empty($_POST['nvchapter']) && !empty($_POST['nvtitle'])) {
                    $this->modifyPostedEpisode($_POST['nvchapter'], $_POST['nvtitle'], $_POST['nvcontent']);
                    $episodeManager = new episodeManager();
                    $tablesJoin = $episodeManager->joinTables();
                    require('src/view/back/homePageBackView.php');
                }
                else {
                    throw new Exception('tous les champs ne sont pas remplis !');
                }
            }
            elseif (isset($_POST['save'])) {
                if (!empty($_POST['nvchapter']) && !empty($_POST['nvtitle'])) {
                    $this->modifySavedEpisode($_POST['nvchapter'], $_POST['nvtitle'], $_POST['nvcontent']);
                    $episodeManager = new episodeManager();
                    $tablesJoin = $episodeManager->joinTables();
                    require('src/view/back/homePageBackView.php');
                }
                else {
                    throw new Exception('tous les champs ne sont pas remplis !');
                }
            }
            elseif (isset($_POST['delete'])) {
                if (isset($_GET['nb']) && $_GET['nb'] > 0) {
                    $this->episodeDelete();
                    $episodeManager = new episodeManager();
                    $tablesJoin = $episodeManager->joinTables();
                    require('src/view/back/homePageBackView.php');
                }
                else {
                    throw new Exception(' aucun identifiant de billet envoyé !');
                }
            }
            else {
                throw new Exception('Erreur : aucun identifiant de billet envoyé');
            }
        }
        else {         
            throw new Exception('Connectez-vous');
        }
    }

    function modifyPostedEpisode($nvchapter, $nvtitle, $nvcontent)//fonction pour modifier un épisode
    {
        $episodeManager = new episodeManager();
        $postedModifiedEpisode = $episodeManager->postModifiedEpisode($nvchapter, $nvtitle, $nvcontent);

    }

    function modifySavedEpisode($nvchapter, $nvtitle, $nvcontent)//fonction pour modifier un épisode
    {
        $episodeManager = new episodeManager();
        $savedModifiedEpisode = $episodeManager->saveModifiedEpisode($nvchapter, $nvtitle, $nvcontent);

    }

    function modifyEpisode()//on affiche la page de modification d'un épisode dans le back avec ses commentaires
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();

        $episode = $episodeManager->getEpisode($_GET['nb']);
        $comments = $commentManager->getReportedComments($_GET['nb']);
        
        session_start();
                
        if ($_SESSION['admConnected'] == true) { 
            if (isset($_GET['nb']) && $_GET['nb'] > 0) {
                require('src/view/back/episodeBackView.php');
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
        else {         
            throw new Exception('Connectez-vous');
        }     
    }

    function episodeDelete()//fonction pour supprimer un épisode
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();

        $episodeManager->deleteEpisode($_GET['nb']);
        $commentManager->deleteComments($_GET['nb']);

    }

    function commentDelete()//fonction pour supprimer un commentaire
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();

        $commentManager->deleteComment($_GET['id']);
        $episode = $episodeManager->getEpisode($_GET['nb']);
        $comments = $commentManager->getReportedComments($_GET['nb']);
        $subComments = $commentManager->substractComments($_GET['chpt']);

        session_start();
                
        if ($_SESSION['admConnected'] == true) {               
            require('src/view/back/episodeBackView.php');
        }
        else {         
            throw new Exception('Connectez-vous');
        }
    }

    function profil()//fonction pour se déconnecter du back
    {
        session_start();
                
        if ($_SESSION['admConnected'] == true) {               
            require('src/view/back/profilView.php');
        }
        else {         
            throw new Exception('Connectez-vous');
        }  
    }

    function disconnection()//fonction pour se déconnecter du back
    {
        session_start();
                
        if ($_SESSION['admConnected'] == true) {
            $_SESSION['admConnected'] = false;
            session_destroy();
        }
        else {
            throw new Exception('Connectez-vous');
        }
    }
}