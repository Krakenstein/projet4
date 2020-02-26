<?php
declare(strict_types=1);
namespace Projet4\Controller;

use Projet4\Model\EpisodeManager;
use Projet4\Model\CommentManager;
use Projet4\Model\UsersManager;
use Projet4\View\View;
use Projet4\Tools\Request;
use Projet4\Tools\Session;
use Projet4\Tools\NoCsrf;

class BackController{

    private $episodeManager;
    private $commentManager;
    private $usersManager;
    private $view;
    private $request;
    private $session;
    private $noCsrf;
   
    public function __construct()
    {
        $this->episodeManager = new EpisodeManager();
        $this->commentManager = new CommentManager();
        $this->usersManager = new UsersManager();
        $this->view = new View();
        $this->request = new Request();
        $this->session = new Session();
        $this->noCsrf = new NoCsrf();
    }

    function admConnect():void//méthode pour se connecter au back
    {       
        $error = 'Pseudo ou mot de passe oublié';
        if ($this->request->post('csrf') !== null && $this->request->post('csrf') === $this->session->getSessionData("token")){
            if ((($this->request->post('nom')) !== null && !empty($this->request->post('nom'))) && (($this->request->post('password')) !== null && !empty($this->request->post('password')))) {
                $infos = $this->usersManager->testInfos($this->request->post('nom'));
                if(!empty($infos) && password_verify(($this->request->post('password')), $infos[2]) === true){                
                    $this->session->setSessionData('admConnected', '1');
                    header('Location: index.php?action=episodes');
                    exit();                
                }
            }
        }
        $token = $this->noCsrf->createToken();      
        $this->view->render('front/connection', 'front/layout', compact('error', 'token'));                         
    }

    function episodes():void //méthode pour afficher la page des épisodes paginés
    {        
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        $episodesTot = $this->episodeManager->countEpisodes();
        $episodesPubTot = $this->episodeManager->countEpisodesPub();
        $nbByPage = 5;
        $totalpages = (int) ceil($episodesTot[0]/$nbByPage);
 
        $this->session->sessionVerify();
        
        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) !== '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            } 
        }

        $offset = ($currentpage - 1) * $nbByPage;
        $tablesJoin = $this->episodeManager->listBackEpisodes((int) $offset, (int) $nbByPage);
        $this->view->render('back/homePageBack', 'back/layout', compact('episodesPubTot', 'countcoms', 'sum', 'episodesTot', 'tablesJoin','nbByPage', 'offset', 'currentpage', 'totalpages'));                
    }

    function createEpisode():void//méthode pour afficher la page de création d'épisode
    { 
        $this->session->setSessionData('chapterNumber', null);
        $chapterNumber = $this->session->getSessionData("chapterNumber");
        $this->session->setSessionData('title', null);
        $titleEp = $this->session->getSessionData("title");
        $this->session->setSessionData('content', null);
        $content = $this->session->getSessionData("content");
        
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        $error = null;
                
        $this->session->sessionVerify();  
        $token = $this->noCsrf->createToken();
        $this->view->render('back/createEpisode', 'back/layout', compact('countcoms', 'sum', 'error', 'token', 'chapterNumber', 'titleEp', 'content'));      
    }

    function addEpisode():void//méthode pour ajouter un épisode dans la bdd archivé ou publié
    {      
        if(!empty($this->request->post('chapterNumber')) || !empty($this->request->post('title')) || !empty($this->request->post('content')))
        {
            $this->session->setSessionData('chapterNumber', $this->request->post('chapterNumber'));
            $chapterNumber = $this->session->getSessionData("chapterNumber");
            $this->session->setSessionData('title', $this->request->post('title'));
            $titleEp = $this->session->getSessionData("title");
            $this->session->setSessionData('content', $this->request->post('content'));
            $content = $this->session->getSessionData("content");
        }
      
        $this->session->sessionVerify();

        if ($this->request->post('csrf') !== null && $this->request->post('csrf') === $this->session->getSessionData("token")){
            if (($this->request->post('publish')) !== null && !empty($this->request->post('chapterNumber')) && !empty($this->request->post('title'))) {          
                $message = 'Episode ' . $this->request->post('chapterNumber') . ' créé et publié';
                $this->episodeManager->postEpisode((int) $this->request->post('chapterNumber'), $this->request->post('title'), $this->request->post('content'));
                header('Location: index.php?action=episodes&ms=' . $message . '');
                exit();             
            }
            elseif (($this->request->post('save')) !== null && !empty($this->request->post('chapterNumber')) && !empty($this->request->post('title'))) {
                $message = 'Episode ' . $this->request->post('chapterNumber') . ' créé et sauvegardé';
                $this->episodeManager->saveEpisode((int) $this->request->post('chapterNumber'), $this->request->post('title'), $this->request->post('content'));
                header('Location: index.php?action=episodes&ms=' . $message . '');
                exit(); 
            }
        }
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        $token = $this->noCsrf->createToken();
        $error = 'Vous devez spécifier le numéro et le titre de l\'épisode';
        $this->view->render('back/createEpisode', 'back/layout', compact('countcoms', 'sum', 'error', 'token', 'chapterNumber', 'titleEp', 'content'));  
    }

    function modifyEpisode():void//on affiche la page de modification d'un épisode dans le back avec ses commentaires
    {
        $episode = $this->episodeManager->findEpisode((int) $this->request->get('id'));
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        
        $this->session->setSessionData('chapterNumber', null);
        $chapterNumber = $this->session->getSessionData("chapterNumber");
        $this->session->setSessionData('title', null);
        $titleEp = $this->session->getSessionData("title");
        $this->session->setSessionData('content', null);
        $content = $this->session->getSessionData("content");
                
        $this->session->sessionVerify();

        $token = $this->noCsrf->createToken();
        $this->view->render('back/episodeBack', 'back/layout', compact('episode', 'sum', 'countcoms', 'token', 'chapterNumber', 'titleEp', 'content'));                      
    }

    function episodeModications():void//méthode pour modifier un épisode et le sauvegarder ou le republier à son ancienne date ou maintenant
    {  
        $isError = true;

        if(!empty($this->request->post('nvchapter')) || !empty($this->request->post('nvtitle')) || !empty($this->request->post('nvcontent')))
        {
            $this->session->setSessionData('chapterNumber', $this->request->post('nvchapter'));
            $chapterNumber = $this->session->getSessionData("chapterNumber");
            $this->session->setSessionData('title', $this->request->post('nvtitle'));
            $titleEp = $this->session->getSessionData("title");
            $this->session->setSessionData('content', $this->request->post('nvcontent'));
            $content = $this->session->getSessionData("content");
        }
                
        $this->session->sessionVerify();

        if ($this->request->post('csrf') !== null && $this->request->post('csrf') === $this->session->getSessionData("token")){
            if (($this->request->post('publish')) !== null && !empty($this->request->post('nvchapter')) && !empty($this->request->post('nvtitle'))) {           
                if(empty($this->request->get('dt'))){
                    $this->episodeManager->postModifiedEpisode((int) $this->request->get('postId'), (int) $this->request->post('nvchapter'), $this->request->post('nvtitle'), $this->request->post('nvcontent'));
                    $message = 'Episode ' . $this->request->post('nvchapter') . ' modifié et publié';                 
                }
                elseif($this->request->post('dateChoice') === 'oldDate'){
                    $this->episodeManager->postModifiedEpisodeSameDate((int) $this->request->get('postId'), (int) $this->request->post('nvchapter'), $this->request->post('nvtitle'), $this->request->post('nvcontent'));
                    $message = 'Episode ' . $this->request->post('nvchapter') . ' modifié et republié à la même date';                
                }
                elseif($this->request->post('dateChoice') === 'newDate'){
                    $this->episodeManager->postModifiedEpisode((int) $this->request->get('postId'), (int) $this->request->post('nvchapter'), $this->request->post('nvtitle'), $this->request->post('nvcontent'));
                    $message = 'Episode ' . $this->request->post('nvchapter') . ' modifié et republié à la date de maintenant';                
                }
                $isError = false;
            }              
            elseif (($this->request->post('save')) !== null && !empty($this->request->post('nvchapter')) && !empty($this->request->post('nvtitle'))) {
                $this->episodeManager->saveModifiedEpisode((int) $this->request->get('postId'), (int) $this->request->post('nvchapter'), $this->request->post('nvtitle'), $this->request->post('nvcontent'));
                $message = 'Episode ' . $this->request->post('nvchapter') . ' modifié et sauvegardé';
                $isError = false; 
            }
            elseif (($this->request->post('delete')) !== null && ($this->request->get('postId')) !== null && $this->request->get('postId') > 0) {
                $this->episodeManager->deleteEpisode((int) $this->request->get('postId'));
                $message = 'Episode ' . $this->request->post('nvchapter') . ' Supprimé';
                $isError = false;
            }
        }  
        if($isError === false){
            header('Location: index.php?action=episodes&ms=' . $message . '');
            exit();
        }      
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        $token = $this->noCsrf->createToken();
        $episode = $this->episodeManager->findEpisode((int) $this->request->get('postId'));
        $error = 'Vous devez spécifier le titre et le numéro de l\'épisode';
        $this->view->render('back/episodeBack', 'back/layout', compact('countcoms', 'sum', 'error', 'episode', 'token', 'chapterNumber', 'titleEp', 'content'));      
    }

    function comPage():void//on affiche la page de gestion des commentaires
    {
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();

        $nbByPage = 5;
        $totalpages = (int) ceil($countcoms[0]/$nbByPage);
     
        $this->session->sessionVerify();

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) !== '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            } 
        }

        $offset = ($currentpage - 1) * $nbByPage;
        $allComs = $this->commentManager->findAllComments( (int) $offset, (int) $nbByPage);
        $this->view->render('back/commentsBack', 'back/layout', compact('nbByPage', 'currentpage', 'offset', 'totalpages', 'countcoms', 'allComs', 'sum'));                          
    }

    function commentDelete():void//méthode pour supprimer un commentaire depuis la page d'un épisode
    {
        $this->commentManager->deleteComment((int) $this->request->get('id'));
        $episode = $this->episodeManager->findEpisode((int) $this->request->get('postid'));
        $comments = $this->commentManager->findReportedComments((int) $this->request->get('postid'));
        $sum = $this->commentManager->countReports();
                
        $this->session->sessionVerify();

        header('Location: index.php?action=modifyEpisode&id='. $this->request->get('postid') .'#headCom' );
        exit();
    }
    
    function deleteReportsFromEp():void//méthode pour supprimer les signalements d'un commentaire depuis la page d'un épisode
    {   
        $this->session->sessionVerify();

        $this->commentManager->deleteReports((int) $this->request->get('id'));
        header('Location: index.php?action=modifyEpisode&id='. $this->request->get('postid') .'#headCom' );
        exit();
    }

    function comDelete():void//méthode pour supprimer un commentaire depuis la page des commentaires
    {   
        $this->session->sessionVerify();

        $this->commentManager->deleteComment((int) $this->request->get('id'));
        header('Location: index.php?action=comPage');
        exit();
    }

    function deleteR():void//méthode pour supprimer les signalements d'un commentaire depuis la page des commentaires
    {   
        $this->session->sessionVerify();

        $this->commentManager->deleteReports((int) $this->request->get('id'));
        header('Location: index.php?action=comPage');
        exit();
    }

    function profil():void//méthode pour afficher la page profil
    { 
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();

                
        $this->session->sessionVerify();
        $token = $this->noCsrf->createToken();

        $this->view->render('back/profil', 'back/layout', compact('countcoms', 'sum', 'token'));
    }

    function reset():void //méthode pour réinitialiser les informations de l'administrateur
    {
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        $message = null;
        $isError = true;
        
        $this->session->sessionVerify();

        $error = 'Une erreure est survenue';
        if ($this->request->post('csrf') !== null && $this->request->post('csrf') === $this->session->getSessionData("token"))
        {
            $error = 'Au moins un des champs est vide';
            if ((($this->request->post('pseudo')) !== null && !empty($this->request->post('pseudo'))) 
            && (($this->request->post('passOld')) !== null && !empty($this->request->post('passOld'))) 
            && (($this->request->post('pass')) !== null && !empty($this->request->post('pass'))) 
            && (($this->request->post('pass2')) !== null && !empty($this->request->post('pass2')))) 
            {
                $infos = $this->usersManager->testInfos($this->request->post('pseudo'));
                $error = 'Impossible de modifier les informations';
                if (!empty($infos) && password_verify($this->request->post('passOld'), $infos[2]) === true)
                {   
                    $error = 'Le nouveau mot de passe choisi n\'est pas valide';
                    if ($this->request->post('pass') !== $this->request->post('pass2')) {// on teste les deux mots de passe
                        $error = 'Les 2 mots de passe sont différents';                       
                    }
                    elseif (preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50})", $this->request->post('pass')))
                    {
                        $this->usersManager->resetInfos($this->request->post('pseudo'), password_hash($this->request->post('pass'), PASSWORD_DEFAULT));
                        session_destroy();
                        session_start();
                        $token = $this->noCsrf->createToken(); 
                        $error = 'Vos changements ont bien été pris en compte';
                        $this->view->render('front/connection', 'front/layout', compact('error', 'token'));
                        $isError = false;  
                    }                          
                }              
            }
        }
        if($isError){
            $token = $this->noCsrf->createToken();
            $this->view->render('back/profil', 'back/layout', compact('message', 'error', 'sum', 'countcoms', 'token'));
        }             
    }

    function disconnection():void//méthode pour se déconnecter du back
    {       
        $this->session->sessionVerify();
        
        
        session_unset();
        session_destroy();
        session_write_close();
        header('Location: index.php?');
        exit();
    }   
}