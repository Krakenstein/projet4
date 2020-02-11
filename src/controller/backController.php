<?php
declare(strict_types=1);
namespace Projet4\Controller;

use Projet4\Manager\episodeManager;
use Projet4\Manager\commentManager;
use Projet4\Manager\usersManager;
use Projet4\View\View;

require_once('src/model/episodeManager.php');
require_once('src/model/commentManager.php');
require_once('src/model/usersManager.php');
require_once('src/view/View.php');

class BackController{

    function admConnect()//méthode pour se connecter au back
    {
        $usersManager = new UsersManager();
        $episodeManager = new episodeManager();

        $hash = $usersManager->getHash();
        $pseudRegister = $usersManager->getPseudo();

        $view = new view();
        
        session_start();


        if ((isset($_POST['nom']) && !empty($_POST['nom'])) && (isset($_POST['password']) && !empty($_POST['password']))) {
            if (($_POST['nom']) === $pseudRegister[0]){
                if (password_verify(($_POST['password']), $hash[0]) === true){
                    $_SESSION['admConnected'] = true;
                    header('Location: index.php?action=episodes');
                    exit();
                }else{
                    $error = 'Pseudo ou mot de passe incorrect';
                    $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
                }
            }else{
                $error = 'Pseudo ou mot de passe incorrect';
                $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
            } 
            
        }else{
            $error = 'Pseudo ou mot de passe oublié';
            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
        }    
              
    }

    function episodes()
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();

        if (isset($_GET['ms'])){
            $message = $_GET['ms'];
        }else $message = null;
        
        $sum = $commentManager->countReports();
        $countcoms = $commentManager->countComs();
        $episodesTot = $episodeManager->countEpisodes();
        $episodesPubTot = $episodeManager->countEpisodesPub();
        $nbByPage = 5;
        $offset = 0;
        $totalpages = ceil($episodesTot[0]/$nbByPage);
        $currentpage=0;

        session_start();

        if (isset($_SESSION['admConnected'])) {               
            if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {

                $currentpage = (int) $_GET['currentpage'];
                } else {
    
                    $currentpage = 1;
                 } 
                 
    
                 if ($currentpage > $totalpages) {
    
                    $currentpage = $totalpages;
                 } 
    
                 if ($currentpage < 1) {
     
                    $currentpage = 1;
                 } 
    
                 $offset = ($currentpage - 1) * $nbByPage;
                 $tablesJoin = $episodeManager->joinTables($offset, $nbByPage);
    
                 if (empty($tablesJoin)) { 
                    $view->render('back/homePageBlankBackView', 'backend/templateBack', compact('episodesPubTot', 'countcoms', 'message', 'sum', 'episodesTot', 'tablesJoin','nbByPage', 'offset', 'currentpage', 'totalpages'));
                }else{
                    $view->render('back/homePageBackView', 'backend/templateBack', compact('episodesPubTot', 'countcoms', 'message', 'sum', 'episodesTot', 'tablesJoin','nbByPage', 'offset', 'currentpage', 'totalpages'));
                }
        }
        else {         
            $error = 'Vous devez vous connecter';
            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
        } 

    }

    function reset()
    {
        $usersManager = new UsersManager();
        $commentManager = new commentManager();
        $view = new view();

        $sum = $commentManager->countReports();
        $countcoms = $commentManager->countComs();
        $hash = $usersManager->getHash();
        
        session_start();
        
        if (isset($_SESSION['admConnected'])){
            if ((isset($_POST['pseudo']) && !empty($_POST['pseudo'])) && (isset($_POST['passOld']) && !empty($_POST['passOld'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && (isset($_POST['pass2']) && !empty($_POST['pass2']))) {
                if (password_verify(($_POST['passOld']), $hash[0]) === true){
                    if ($_POST['pass'] != $_POST['pass2']) {// on teste les deux mots de passe
                        $error = 'Les 2 mots de passe sont différents';
                        $message = null;
                        $view->render('back/profilView', 'backend/templateBack', compact('message', 'error', 'sum', 'countcoms'));
                    }
                    else {
                        if (preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50})", $_POST['pass'])){
                            $infos = $usersManager->resetInfos($_POST['pseudo'], password_hash($_POST['pass'], PASSWORD_DEFAULT));
                            $_SESSION['admConnected'] = false;
                            session_destroy();
                            $error = 'Vos changements ont bien été pris en compte';
                            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
                        }else{
                            $error = 'Le nouveau mot de passe choisi n\'est pas valide';
                            $message = null;
                            $view->render('back/profilView', 'backend/templateBack', compact('message', 'error', 'sum', 'countcoms'));
                        }
                        
                        
                    }
                }else{
                    $error = 'Impossible de modifier les informations';
                    $message = null;
                    $view->render('back/profilView', 'backend/templateBack', compact('message', 'error', 'sum', 'countcoms'));
                }

            }
            else {
                $error = 'Au moins un des champs est vide';
                $message = null;
                $view->render('back/profilView', 'backend/templateBack', compact('message', 'countcoms', 'error', 'sum'));
            }
        }else {         
            $error = 'Vous devez vous connecter';
            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
        }   
            
        
    }

    function createEpisode()//méthode pour afficher la page de création d'épisode
    {
        session_start();
        $_SESSION['chapterNumber'] = null;
        $_SESSION['title'] = null;
        $_SESSION['content'] = null;
        
        $view = new view();
        $commentManager = new commentManager();

        $sum = $commentManager->countReports();
        $countcoms = $commentManager->countComs();
        $error = null;
                
        if (isset($_SESSION['admConnected'])) {               
            $view->render('back/createEpisodeView', 'backend/templateBack', compact('countcoms', 'sum', 'error'));
        }
        else {         
            $error = 'Vous devez vous connecter';
            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
        }  
    }

    function addEpisode()//méthode pour ajouter un épisode dans la bdd archivé ou publié
    {
        session_start();
        if(isset($_POST) && !empty($_POST))
        {
            $_SESSION['chapterNumber'] = $_POST['chapterNumber'];
            $_SESSION['title'] = $_POST['title'];
            $_SESSION['content'] = $_POST['content'];
        };

        $commentManager = new commentManager();
        $view = new view();
                
        if (isset($_SESSION['admConnected'])) { 
        
            if (isset($_POST['publish'])) {
                if (!empty($_POST['chapterNumber']) && !empty($_POST['title'])) {
                    $message = 'Episode ' . $_POST['chapterNumber'] . ' créé et posté';
                    $this->addPostedEpisode($_POST['chapterNumber'], $_POST['title'], $_POST['content']);
                    header('Location: index.php?action=episodes&ms=' . $message . '');
                    exit(); 
                }
                else {
                    $sum = $commentManager->countReports();
                    $countcoms = $commentManager->countComs();
                    $error = 'Vous devez spécifier le numéro et le titre de l\'épisode';
                    $view->render('back/createEpisodeView', 'backend/templateBack', compact('countcoms', 'sum', 'error'));
                }
            }
            elseif (isset($_POST['save'])) {
                if (!empty($_POST['chapterNumber']) && !empty($_POST['title'])) {
                    $message = 'Episode ' . $_POST['chapterNumber'] . ' créé et sauvegardé';
                    $this->addSavedEpisode($_POST['chapterNumber'], $_POST['title'], $_POST['content']);
                    header('Location: index.php?action=episodes&ms=' . $message . '');
                    exit(); 
                }
                else {
                    $sum = $commentManager->countReports();
                    $countcoms = $commentManager->countComs();
                    $error = 'Vous devez spécifier le numéro et le titre de l\'épisode';
                    $view->render('back/createEpisodeView', 'backend/templateBack', compact('countcoms', 'sum', 'error'));
                }
            }
            else {
                throw new Exception('Erreur : aucun identifiant de billet envoyé');
            }
        }
        else {         
            $error = 'Vous devez vous connecter';
            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
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
        if(isset($_POST) && !empty($_POST))
        {
            $_SESSION['chapterNumber'] = $_POST['nvchapter'];
            $_SESSION['title'] = $_POST['nvtitle'];
            $_SESSION['content'] = $_POST['nvcontent'];
        };

        $commentManager = new commentManager();
        $episodeManager = new episodeManager();
        $view = new view();
                
        if (isset($_SESSION['admConnected'])) {
        
            if (isset($_POST['publish'])) {
                if (!empty($_POST['nvchapter']) && !empty($_POST['nvtitle'])) {
                    $this->modifyPostedEpisode($_GET['id'], $_POST['nvchapter'], $_POST['nvtitle'], $_POST['nvcontent']);
                    $message = 'Episode ' . $_POST['nvchapter'] . ' modifié et posté';
                    header('Location: index.php?action=episodes&ms=' . $message . '');
                    exit(); 
                }
                else {
                    $sum = $commentManager->countReports();
                    $countcoms = $commentManager->countComs();
                    $episode = $episodeManager->getEpisode($_GET['id']);
                    $comments = $commentManager->getReportedComments($_GET['id']);
                    $error = 'Vous devez spécifier le titre et le numéro de l\'épisode';
                    $view->render('back/episodeBackView', 'backend/templateBack', compact('countcoms', 'sum', 'error', 'episode', 'comments'));
                }
            }
            elseif (isset($_POST['save'])) {
                if (!empty($_POST['nvchapter']) && !empty($_POST['nvtitle'])) {
                    $this->modifySavedEpisode($_GET['id'], $_POST['nvchapter'], $_POST['nvtitle'], $_POST['nvcontent']);
                    $message = 'Episode ' . $_POST['nvchapter'] . ' modifié et sauvegardé';
                    header('Location: index.php?action=episodes&ms=' . $message . '');
                    exit(); 
                }
                else {
                    $sum = $commentManager->countReports();
                    $countcoms = $commentManager->countComs();
                    $episode = $episodeManager->getEpisode($_GET['id']);
                    $comments = $commentManager->getReportedComments($_GET['id']);
                    $error = 'Vous devez spécifier le titre et le numéro de l\'épisode';
                    $view->render('back/episodeBackView', 'backend/templateBack', compact('countcoms', 'sum', 'error', 'episode', 'comments'));
                }
            }
            elseif (isset($_POST['delete'])) {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $this->episodeDelete();
                    $message = 'Episode ' . $_POST['nvchapter'] . ' Supprimé';
                    header('Location: index.php?action=episodes&ms=' . $message . '');
                    exit(); 
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
            $error = 'Vous devez vous connecter';
            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
        }
    }

    function modifyPostedEpisode(string $post_id, string $nvchapter, string $nvtitle, string $nvcontent)//méthode pour modifier un épisode en le publiant
    {
        $episodeManager = new episodeManager();
        $postedModifiedEpisode = $episodeManager->postModifiedEpisode($_GET['id'], $nvchapter, $nvtitle, $nvcontent);

    }

    function modifySavedEpisode(string $post_id, string $nvchapter, string $nvtitle, string $nvcontent)//méthode pour modifier un épisode en l'archivant
    {
        $episodeManager = new episodeManager();
        $savedModifiedEpisode = $episodeManager->saveModifiedEpisode($_GET['id'], $nvchapter, $nvtitle, $nvcontent);

    }

    function modifyEpisode()//on affiche la page de modification d'un épisode dans le back avec ses commentaires
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();

        $episode = $episodeManager->getEpisode($_GET['id']);
        $comments = $commentManager->getReportedComments($_GET['id']);
        $sum = $commentManager->countReports();
        $countcoms = $commentManager->countComs();
        $error = null;
        
        session_start();
        $_SESSION['chapterNumber'] = null;
        $_SESSION['title'] = null;
        $_SESSION['content'] = null;
                
        if (isset($_SESSION['admConnected'])) { 
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $view->render('back/episodeBackView', 'backend/templateBack', compact('episode', 'comments', 'sum', 'countcoms', 'error'));
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
        else {         
            $error = 'Vous devez vous connecter';
            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
        }     
    }

    function comPage()//on affiche la page de gestion des commentaires
    {
        $commentManager = new commentManager();
        $view = new view();

        $sum = $commentManager->countReports();
        $countcoms = $commentManager->countComs();

        $nbByPage = 5;
        $offset = 0;
        $totalpages = ceil($countcoms[0]/$nbByPage);
        $currentpage=0;

        session_start();
                
        if (isset($_SESSION['admConnected'])) { 
            if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {

                $currentpage = (int) $_GET['currentpage'];

                } else {
    
                    $currentpage = 1;
                 } 
                 
    
                 if ($currentpage > $totalpages) {
    
                    $currentpage = $totalpages;
                 } 
    
                 if ($currentpage < 1) {
     
                    $currentpage = 1;
                 } 
    
                 $offset = ($currentpage - 1) * $nbByPage;
                 $allComs = $commentManager->getAllComments($offset, $nbByPage);

                 if (empty($allComs)) { 
                    $view->render('back/commentsBackBlankView', 'backend/templateBack', compact('nbByPage', 'currentpage', 'offset', 'totalpages', 'countcoms', 'allComs', 'sum'));
                }else{
                    $view->render('back/commentsBackView', 'backend/templateBack', compact('nbByPage', 'currentpage', 'offset', 'totalpages', 'countcoms', 'allComs', 'sum'));
                }           
        }
        else {         
            $error = 'Vous devez vous connecter';
            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
        }     
    }

    function episodeDelete()//méthode pour supprimer un épisode
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();

        $episodeManager->deleteEpisode($_GET['id']);
        $commentManager->deleteComments($_GET['id']);

    }

    function commentDelete()//méthode pour supprimer un commentaire depuis la page d'un épisode
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();

        $commentManager->deleteComment($_GET['id']);
        $episode = $episodeManager->getEpisode($_GET['postid']);
        $comments = $commentManager->getReportedComments($_GET['postid']);
        $sum = $commentManager->countReports();
        $error = null;

        session_start();
                
        if (isset($_SESSION['admConnected'])) {               
            $view->render('back/episodeBackView', 'backend/templateBack', compact('episode', 'comments', 'sum', 'error'));
        }
        else {         
            $error = 'Vous devez vous connecter';
            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
        }
    }

    function comDelete()//méthode pour supprimer un commentaire depuis la page des commentaires
    {   
        $view = new view();

        session_start();

        if (isset($_SESSION['admConnected'])) { 
            $commentManager = new commentManager();
            $commentManager->deleteComment($_GET['id']);
            header('Location: index.php?action=commentsPage');
            exit();
        }
        else {         
            $error = 'Vous devez vous connecter';
            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
        }
    }

    function deleteR()//méthode pour supprimer un commentaire depuis la page des commentaires
    {   
        $view = new view();

        session_start();

        if (isset($_SESSION['admConnected'])) { 
            $commentManager = new commentManager();
            $commentManager->deleteReports($_GET['id']);
            header('Location: index.php?action=commentsPage');
            exit();
        }
        else {         
            $error = 'Vous devez vous connecter';
            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
        }
    }

    function profil()//méthode pour aller à la page profil
    {
        session_start();
        $view = new view();
        $commentManager = new commentManager();
        $sum = $commentManager->countReports();
        $countcoms = $commentManager->countComs();
        $error = null;
        $message = null;
                
        if (isset($_SESSION['admConnected'])) {               
            $view->render('back/profilView', 'backend/templateBack', compact('countcoms', 'sum', 'error', 'message'));
        }
        else {         
            $error = 'Vous devez vous connecter';
            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
        }  
    }

    function disconnection()//méthode pour se déconnecter du back
    {
        $view = new view();
        
        session_start();
                
        if (isset($_SESSION['admConnected'])) {
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
            $error = 'Vous devez vous connecter';
            $view->render('front/connectionView', 'frontend/templateFront', compact('error'));
        }
    }
}