<?php
declare(strict_types=1);
namespace Projet4\Controller;

use Projet4\Model\EpisodeManager;
use Projet4\Model\CommentManager;
use Projet4\Model\UsersManager;
use Projet4\View\View;
use Projet4\Tools\Request;


class BackController{

    private $episodeManager;
    private $commentManager;
    private $usersManager;
    private $view;
    private $request;
    
   
    public function __construct()
    {
        $this->episodeManager = new EpisodeManager();
        $this->commentManager = new CommentManager();
        $this->usersManager = new UsersManager();
        $this->view = new View();
        $this->request = new Request();
        
    }

    /*function admConnect():void//méthode pour se connecter au back
    {  
        session_start();

        if ((isset($this->request->post('nom')) && !empty($this->request->post('nom'))) && (isset($this->request->post('password')) && !empty($this->request->post('password')))) {
            $infos = $this->usersManager->testInfos($this->request->post('nom'));
            if(!empty($infos)){
                if ((password_verify(($this->request->post('password')), $infos[2]) === true)){
                    $_SESSION['admConnected'] = true;
                    header('Location: index.php?action=episodes');
                    exit();                
                }else{
                    $error = 'Pseudo ou mot de passe incorrect';
                    $this->view->render('front/connection', 'front/layout', compact('error'));
                } 
            }else{
                $error = 'Pseudo ou mot de passe incorrect';
                $this->view->render('front/connection', 'front/layout', compact('error'));
            }           
        }else{
            $error = 'Pseudo ou mot de passe oublié';
            $this->view->render('front/connection', 'front/layout', compact('error'));
        }                
    }*/

    function admConnect():void//méthode pour se connecter au back
    {  
        session_start();

        $error = 'Pseudo ou mot de passe oublié';
        if ((($this->request->post('nom')) !== null && !empty($this->request->post('nom'))) && (($this->request->post('password')) !== null && !empty($this->request->post('password')))) {
            $infos = $this->usersManager->testInfos($this->request->post('nom'));
            if(!empty($infos) && password_verify(($this->request->post('password')), $infos[2]) === true){                
                $_SESSION['admConnected'] = true;
                header('Location: index.php?action=episodes');
                exit();                
            }
        }

        {
        $this->view->render('front/connection', 'front/layout', compact('error'));
        }                       
    }

    function episodes():void //méthode pour afficher la page des épisodes paginés
    {        
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        $episodesTot = $this->episodeManager->countEpisodes();
        $episodesPubTot = $this->episodeManager->countEpisodesPub();
        $nbByPage = 5;
        $totalpages = (int) ceil($episodesTot[0]/$nbByPage);

        session_start();

        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) !== '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            } 
        }

        $offset = ($currentpage - 1) * $nbByPage;
        $tablesJoin = $this->episodeManager->joinTables((int) $offset, (int) $nbByPage);
        $this->view->render('back/homePageBack', 'back/layout', compact('episodesPubTot', 'countcoms', 'sum', 'episodesTot', 'tablesJoin','nbByPage', 'offset', 'currentpage', 'totalpages'));                
    }

    function reset():void //méthode pour réinitialiser les informations de l'administrateur
    {
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        //$infos = $this->usersManager->testInfos($this->request->post('pseudo'));
        $message = null;
        $isError = true;
        session_start();
        
        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }
        $error = 'Au moins un des champs est vide';
        if ((($this->request->post('pseudo')) !== null && !empty($this->request->post('pseudo'))) && (($this->request->post('passOld')) !== null && !empty($this->request->post('passOld'))) && (($this->request->post('pass')) !== null && !empty($this->request->post('pass'))) && (($this->request->post('pass2')) !== null && !empty($this->request->post('pass2')))) {
            $infos = $this->usersManager->testInfos($this->request->post('pseudo'));
            $error = 'Impossible de modifier les informations';
            if (!empty($infos) && password_verify($this->request->post('passOld'), $infos[2]) === true){   
                $error = 'Le nouveau mot de passe choisi n\'est pas valide';
                if ($this->request->post('pass') !== $this->request->post('pass2')) {// on teste les deux mots de passe
                    $error = 'Les 2 mots de passe sont différents';                       
                }
                elseif (preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50})", $this->request->post('pass'))){
                    $this->usersManager->resetInfos($this->request->post('pseudo'), password_hash($this->request->post('pass'), PASSWORD_DEFAULT));
                    $_SESSION['admConnected'] = false;
                    session_destroy();
                    $error = 'Vos changements ont bien été pris en compte';
                    $this->view->render('front/connection', 'front/layout', compact('error'));
                    $isError = false;  
                }                          
            }              
        }

        if($isError){
            $this->view->render('back/profil', 'back/layout', compact('message', 'error', 'sum', 'countcoms'));
        }
               
    }

    function createEpisode():void//méthode pour afficher la page de création d'épisode
    {
        session_start();
        $_SESSION['chapterNumber'] = null;
        $_SESSION['title'] = null;
        $_SESSION['content'] = null;
        

        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        $error = null;
                
        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }                 
        $this->view->render('back/createEpisode', 'back/layout', compact('countcoms', 'sum', 'error'));
        
    }

    function addEpisode():void//méthode pour ajouter un épisode dans la bdd archivé ou publié
    {
        session_start();

        if(($this->request->post) !== null && !empty($this->request->post))
        {
            $_SESSION['chapterNumber'] = $this->request->post('chapterNumber');
            $_SESSION['title'] = $this->request->post('title');
            $_SESSION['content'] = $this->request->post('content');
        };
      
        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }
        if (($this->request->post('publish')) !== null && !empty($this->request->post('chapterNumber')) && !empty($this->request->post('title'))) {          
            $message = 'Episode ' . $this->request->post('chapterNumber') . ' créé et publié';
            $postedEpisode = $this->episodeManager->postEpisode((int) $this->request->post('chapterNumber'), $this->request->post('title'), $this->request->post('content'));
            header('Location: index.php?action=episodes&ms=' . $message . '');
            exit();             
        }
        elseif (($this->request->post('save')) !== null && !empty($this->request->post('chapterNumber')) && !empty($this->request->post('title'))) {
            $message = 'Episode ' . $this->request->post('chapterNumber') . ' créé et sauvegardé';
            $postedEpisode = $this->episodeManager->saveEpisode((int) $this->request->post('chapterNumber'), $this->request->post('title'), $this->request->post('content'));
            header('Location: index.php?action=episodes&ms=' . $message . '');
            exit(); 
        }

        {
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        $error = 'Vous devez spécifier le numéro et le titre de l\'épisode';
        $this->view->render('back/createEpisode', 'back/layout', compact('countcoms', 'sum', 'error'));
        }    
    }

    /*function episodeModications():void//méthode pour modifier un épisode et le sauvegarder ou le republier à son ancienne date ou maintenant
    {
        session_start();
        if(isset($this->request->post) && !empty($this->request->post))
        {
            $_SESSION('chapterNumber') = $this->request->post('nvchapter');
            $_SESSION('title') = $this->request->post('nvtitle');
            $_SESSION('content') = $this->request->post('nvcontent');
        };
                
        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }        
        if (isset($this->request->post('publish'))) {
            if (!empty($this->request->post('nvchapter')) && !empty($this->request->post('nvtitle'))) {
                if(($this->request->get('dt')) != null){
                    if($this->request->post('dateChoice') === 'oldDate'){
                        $this->modifyPostedEpisodeSameDate((int) $this->request->get('id'), (int) $this->request->post('nvchapter'), $this->request->post('nvtitle'), $this->request->post('nvcontent'));
                        $message = 'Episode ' . $this->request->post('nvchapter') . ' modifié et republié à la même date';
                        header('Location: index.php?action=episodes&ms=' . $message . '');
                        exit(); 
                    }else{
                        $this->modifyPostedEpisode((int) $this->request->get('id'), (int) $this->request->post('nvchapter'), $this->request->post('nvtitle'), $this->request->post('nvcontent'));
                        $message = 'Episode ' . $this->request->post('nvchapter') . ' modifié et republié à la date de maintenant';
                        header('Location: index.php?action=episodes&ms=' . $message . '');
                        exit(); 
                    }
                }else{
                    $this->modifyPostedEpisode((int) $this->request->get('id'), (int) $this->request->post('nvchapter'), $this->request->post('nvtitle'), $this->request->post('nvcontent'));
                    $message = 'Episode ' . $this->request->post('nvchapter') . ' modifié et publié';
                    header('Location: index.php?action=episodes&ms=' . $message . '');
                    exit(); 
                }    
            }else {
                    $sum = $this->commentManager->countReports();
                    $countcoms = $this->commentManager->countComs();
                    $episode = $this->episodeManager->findEpisode($this->request->get('id'));
                    $comments = $this->commentManager->findReportedComments($this->request->get('id'));
                    $error = 'Vous devez spécifier le titre et le numéro de l\'épisode';
                    $this->view->render('back/episodeBack', 'back/layout', compact('countcoms', 'sum', 'error', 'episode', 'comments'));
                }
            
        }elseif (isset($this->request->post('save'))) {
            if (!empty($this->request->post('nvchapter')) && !empty($this->request->post('nvtitle'))) {
                $this->modifySavedEpisode((int) $this->request->get('id'), (int) $this->request->post('nvchapter'), $this->request->post('nvtitle'), $this->request->post('nvcontent'));
                $message = 'Episode ' . $this->request->post('nvchapter') . ' modifié et sauvegardé';
                header('Location: index.php?action=episodes&ms=' . $message . '');
                exit(); 
            }else {
                $sum = $this->commentManager->countReports();
                $countcoms = $this->commentManager->countComs();
                $episode = $this->episodeManager->findEpisode($this->request->get('id'));
                $comments = $this->commentManager->findReportedComments($this->request->get('id'));
                $error = 'Vous devez spécifier le titre et le numéro de l\'épisode';
                $this->view->render('back/episodeBack', 'back/layout', compact('countcoms', 'sum', 'error', 'episode', 'comments'));
            }
        }elseif (isset($this->request->post('delete'))) {
            if (isset($this->request->get('id')) && $this->request->get('id') > 0) {
                $this->episodeDelete();
                $message = 'Episode ' . $this->request->post('nvchapter') . ' Supprimé';
                header('Location: index.php?action=episodes&ms=' . $message . '');
                exit(); 
            }else {
                throw new Exception(' aucun identifiant envoyé !');
            }
        }else {
            throw new Exception('Erreur : aucun identifiant envoyé');
        }
    }*/

    function episodeModications():void//méthode pour modifier un épisode et le sauvegarder ou le republier à son ancienne date ou maintenant
    {
        session_start();

        $isError = true;

        if(($this->request->post) !== null && !empty($this->request->post))
        {
            $_SESSION['nvchapter'] = $this->request->post('nvchapter');
            $_SESSION['nvtitle'] = $this->request->post('nvtitle');
            $_SESSION['content'] = $this->request->post('nvcontent');
        };
                
        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }        
        if (($this->request->post('publish')) !== null && !empty($this->request->post('nvchapter')) && !empty($this->request->post('nvtitle'))) {           
            if(empty($this->request->get('dt'))){
                $this->episodeManager->postModifiedEpisode((int) $this->request->get('id'), (int) $this->request->post('nvchapter'), $this->request->post('nvtitle'), $this->request->post('nvcontent'));
                $message = 'Episode ' . $this->request->post('nvchapter') . ' modifié et publié';                 
            }
            elseif($this->request->post('dateChoice') === 'oldDate'){
                $this->episodeManager->postModifiedEpisodeSameDate((int) $this->request->get('id'), (int) $this->request->post('nvchapter'), $this->request->post('nvtitle'), $this->request->post('nvcontent'));
                $message = 'Episode ' . $this->request->post('nvchapter') . ' modifié et republié à la même date';                
            }
            elseif($this->request->post('dateChoice') === 'newDate'){
                $this->episodeManager->postModifiedEpisode((int) $this->request->get('id'), (int) $this->request->post('nvchapter'), $this->request->post('nvtitle'), $this->request->post('nvcontent'));
                $message = 'Episode ' . $this->request->post('nvchapter') . ' modifié et republié à la date de maintenant';                
            }
            $isError = false;
        }              
        elseif (($this->request->post('save')) !== null && !empty($this->request->post('nvchapter')) && !empty($this->request->post('nvtitle'))) {
            $this->episodeManager->saveModifiedEpisode((int) $this->request->get('id'), (int) $this->request->post('nvchapter'), $this->request->post('nvtitle'), $this->request->post('nvcontent'));
            $message = 'Episode ' . $this->request->post('nvchapter') . ' modifié et sauvegardé';
            $isError = false; 
        }
        elseif (($this->request->post('delete')) !== null && ($this->request->get('id')) !== null && $this->request->get('id') > 0) {
            $this->episodeManager->deleteEpisode((int) $this->request->get('id'));
            $message = 'Episode ' . $this->request->post('nvchapter') . ' Supprimé';
            $isError = false;
        }
        
        if($isError === false){
            header('Location: index.php?action=episodes&ms=' . $message . '');
            exit();
        }
        
        {
            $sum = $this->commentManager->countReports();
            $countcoms = $this->commentManager->countComs();
            $episode = $this->episodeManager->findEpisode((int) $this->request->get('id'));
            $comments = $this->commentManager->findReportedComments((int) $this->request->get('id'));
            $error = 'Vous devez spécifier le titre et le numéro de l\'épisode';
            $this->view->render('back/episodeBack', 'back/layout', compact('countcoms', 'sum', 'error', 'episode', 'comments'));
        }
    }

    /*function modifyPostedEpisode(int $postId, int $nvchapter, string $nvtitle, string $nvcontent)//méthode pour modifier un épisode en le publiant
    {

        $postedModifiedEpisode = $this->episodeManager->postModifiedEpisode((int) $this->request->get('id'), (int) $nvchapter, $nvtitle, $nvcontent);

    }

    function modifyPostedEpisodeSameDate(int $postId, int $nvchapter, string $nvtitle, string $nvcontent)//méthode pour modifier un épisode en le publiant
    {

        $postedModifiedEpisode = $this->episodeManager->postModifiedEpisodeSameDate((int) $this->request->get('id'), (int) $nvchapter, $nvtitle, $nvcontent, $this->request->get('dt'));

    }

    function modifySavedEpisode(int $postId, int $nvchapter, string $nvtitle, string $nvcontent)//méthode pour modifier un épisode en l'archivant
    {

        $savedModifiedEpisode = $this->episodeManager->saveModifiedEpisode((int) $this->request->get('id'), (int) $nvchapter, $nvtitle, $nvcontent);

    }*/

    function modifyEpisode():void//on affiche la page de modification d'un épisode dans le back avec ses commentaires
    {
        $episode = $this->episodeManager->findEpisode((int) $this->request->get('id'));
        $comments = $this->commentManager->findReportedComments((int) $this->request->get('id'));
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        
        session_start();
        $_SESSION['chapterNumber'] = null;
        $_SESSION['title'] = null;
        $_SESSION['content'] = null;
                
        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }  
        if($episode === false){
            echo 'coco';
        }else{
            if (($this->request->get('id')) !== null && $this->request->get('id') > 0) {
            $this->view->render('back/episodeBack', 'back/layout', compact('episode', 'comments', 'sum', 'countcoms'));
            } 
        }            
    }

    function comPage():void//on affiche la page de gestion des commentaires
    {
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();

        $nbByPage = 5;
        $totalpages = (int) ceil($countcoms[0]/$nbByPage);

        session_start();
                
        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }
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

        session_start();
                
        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }              
        header('Location: index.php?action=modifyEpisode&id='. $this->request->get('postid') );
        exit();
    }

    function comDelete():void//méthode pour supprimer un commentaire depuis la page des commentaires
    {   
        session_start();

        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }   
        $this->commentManager->deleteComment((int) $this->request->get('id'));
        header('Location: index.php?action=comPage');
        exit();
    }

    function deleteR():void//méthode pour supprimer les signalements d'un commentaire depuis la page des commentaires
    {   
        session_start();

        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }            
        $this->commentManager->deleteReports((int) $this->request->get('id'));
        header('Location: index.php?action=comPage');
        exit();
    }

    function profil():void//méthode pour aller à la page profil
    {
        session_start();
        
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();

                
        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }               
        $this->view->render('back/profil', 'back/layout', compact('countcoms', 'sum'));
    }

    function disconnection():void//méthode pour se déconnecter du back
    {
        session_start();
                
        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }
        $_SESSION['admConnected'] = false;
        session_destroy();
        header('Location: index.php?');
        exit();
    }
        
}