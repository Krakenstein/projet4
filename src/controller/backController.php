<?php
declare(strict_types=1);

require_once('src/model/episodeManager.php');
require_once('src/model/commentManager.php');
require_once('src/view/View.php');

class BackController{

    function admConnect()//méthode pour se connecter au back
    {
        $episodeManager = new episodeManager();
        $tablesJoin = $episodeManager->joinTables();

        $view = new view();
        
        session_start();

        if ((isset($_POST['password']) && $_POST['password'] == "123") or ($_SESSION['admConnected'] == true)){
            if ((isset($_POST['nom']) && $_POST['nom'] == "jean") or ($_SESSION['admConnected'] == true)){
                $_SESSION['admConnected'] = true;
                $view->render('back/homePageBackView', 'backend/templateBack', compact('tablesJoin'));
            }
            else {
                throw new Exception('Pseudo incorrect');
            }        
        }
        else {
            throw new Exception('Mot de passe incorrect');
        }
        
    }

    function createEpisode()//méthode pour afficher la page de création d'épisode
    {
        session_start();
        $view = new view();
                
        if ($_SESSION['admConnected'] == true) {               
            $view->render('back/createEpisodeView', 'backend/templateBack');
        }
        else {         
            throw new Exception('Connectez-vous');
        }  
    }

    function addEpisode()//méthode pour ajouter un épisode dans la bdd archivé ou publié
    {
        session_start();
        $view = new view();
                
        if ($_SESSION['admConnected'] == true) { 
        
            if (isset($_POST['publish'])) {
                if (!empty($_POST['chapterNumber']) && !empty($_POST['title'])) {
                    $this->addPostedEpisode($_POST['chapterNumber'], $_POST['title'], $_POST['content']);
                    $episodeManager = new episodeManager();
                    $tablesJoin = $episodeManager->joinTables();
                    $view->render('back/homePageBackView', 'backend/templateBack', compact('tablesJoin'));
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
                    $view->render('back/homePageBackView', 'backend/templateBack', compact('tablesJoin'));
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

    function addPostedEpisode(string $episodeNumber, string $title, string $content)//méthode pour ajouter un épisode publié à la bdd
    {
        $episodeManager = new episodeManager();
        $postedEpisode = $episodeManager->postEpisode($episodeNumber, $title, $content);

    }

    function addSavedEpisode(string $episodeNumber, string $title, string $content)//méthode pour ajouter un épisode archivé à la bdd
    {
        $episodeManager = new episodeManager();
        $postedEpisode = $episodeManager->saveEpisode($episodeNumber, $title, $content);

    }

    function episodeModications()//méthode pour modifier un épisode
    {
        session_start();
        $view = new view();
                
        if ($_SESSION['admConnected'] == true) {
        
            if (isset($_POST['publish'])) {
                if (!empty($_POST['nvchapter']) && !empty($_POST['nvtitle'])) {
                    $this->modifyPostedEpisode($_POST['nvchapter'], $_POST['nvtitle'], $_POST['nvcontent']);
                    $episodeManager = new episodeManager();
                    $tablesJoin = $episodeManager->joinTables();
                    $view->render('back/homePageBackView', 'backend/templateBack', compact('tablesJoin'));
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
                    $view->render('back/homePageBackView', 'backend/templateBack', compact('tablesJoin'));
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
                    $view->render('back/homePageBackView', 'backend/templateBack', compact('tablesJoin'));
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

    function modifyPostedEpisode(string $nvchapter, string $nvtitle, string $nvcontent)//méthode pour modifier un épisode en le publiant
    {
        $episodeManager = new episodeManager();
        $postedModifiedEpisode = $episodeManager->postModifiedEpisode($nvchapter, $nvtitle, $nvcontent);

    }

    function modifySavedEpisode(string $nvchapter, string $nvtitle, string $nvcontent)//méthode pour modifier un épisode en l'archivant
    {
        $episodeManager = new episodeManager();
        $savedModifiedEpisode = $episodeManager->saveModifiedEpisode($nvchapter, $nvtitle, $nvcontent);

    }

    function modifyEpisode()//on affiche la page de modification d'un épisode dans le back avec ses commentaires
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();

        $episode = $episodeManager->getEpisode($_GET['nb']);
        $comments = $commentManager->getReportedComments($_GET['nb']);
        
        session_start();
                
        if ($_SESSION['admConnected'] == true) { 
            if (isset($_GET['nb']) && $_GET['nb'] > 0) {
                $view->render('back/episodeBackView', 'backend/templateBack', compact('episode', 'comments'));
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
        else {         
            throw new Exception('Connectez-vous');
        }     
    }

    function episodeDelete()//méthode pour supprimer un épisode
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();

        $episodeManager->deleteEpisode($_GET['nb']);
        $commentManager->deleteComments($_GET['nb']);

    }

    function commentDelete()//méthode pour supprimer un commentaire
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();

        $commentManager->deleteComment($_GET['id']);
        $episode = $episodeManager->getEpisode($_GET['nb']);
        $comments = $commentManager->getReportedComments($_GET['nb']);
        $subComments = $commentManager->substractComments($_GET['chpt']);

        session_start();
                
        if ($_SESSION['admConnected'] == true) {               
            $view->render('back/episodeBackView', 'backend/templateBack', compact('episode', 'comments', 'subComments'));
        }
        else {         
            throw new Exception('Connectez-vous');
        }
    }

    function profil()//méthode pour aller à la page profil
    {
        session_start();
        $view = new view();
                
        if ($_SESSION['admConnected'] == true) {               
            $view->render('back/profilView', 'backend/templateBack');
        }
        else {         
            throw new Exception('Connectez-vous');
        }  
    }

    function disconnection()//méthode pour se déconnecter du back
    {
        session_start();
                
        if ($_SESSION['admConnected'] == true) {
            $_SESSION['admConnected'] = false;
            session_destroy();
            session_start();
            $episodeManager = new episodeManager();
            $view = new view();

            $lastEpisode = $episodeManager->getLastEpisode();

            if ($lastEpisode === false) {
                $view->render('front/homePageBlankView', 'frontend/templateFront');
            }
            else {   
                $view->render('front/homePageView', 'frontend/templateFront', compact('lastEpisode'));
            }
        }
        else {
            throw new Exception('Connectez-vous');
        }
    }
}