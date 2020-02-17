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

    /*function admConnect()//méthode pour se connecter au back
    {
        $hash = $this->usersManager->getHash();
        $pseudRegister = $this->usersManager->getPseudo();
        
        session_start();


        if ((isset($_POST['nom']) && !empty($_POST['nom'])) && (isset($_POST['password']) && !empty($_POST['password']))) {
            if (($_POST['nom']) === $pseudRegister[0]){
                if (password_verify(($_POST['password']), $hash[0]) === true){
                    $_SESSION['admConnected'] = true;
                    header('Location: index.php?action=episodes');
                    exit();
                }else{
                    $error = 'Pseudo ou mot de passe incorrect';
                    $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
                }
            }else{
                $error = 'Pseudo ou mot de passe incorrect';
                $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
            } 
            
        }else{
            $error = 'Pseudo ou mot de passe oublié';
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
        }    
              
    }*/

    function admConnect()//méthode pour se connecter au back
    {  
        session_start();

        if ((isset($_POST['nom']) && !empty($_POST['nom'])) && (isset($_POST['password']) && !empty($_POST['password']))) {
            $infos = $this->usersManager->testInfos($_POST['nom']);
            if(!empty($infos)){
                if ((password_verify(($_POST['password']), $infos[2]) === true)){
                    $_SESSION['admConnected'] = true;
                    header('Location: index.php?action=episodes');
                    exit();
                
                }else{
                    $error = 'Pseudo ou mot de passe incorrect';
                    $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
                } 
            }else{
                $error = 'Pseudo ou mot de passe incorrect';
                $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
            }
            
            
        }else{
            $error = 'Pseudo ou mot de passe oublié';
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
        }                
    }

    function episodes()
    {        
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        $episodesTot = $this->episodeManager->countEpisodes();
        $episodesPubTot = $this->episodeManager->countEpisodesPub();
        $nbByPage = 5;
        $totalpages = ceil($episodesTot[0]/$nbByPage);

        session_start();

        if (isset($_SESSION['admConnected'])) {               
            if (($this->request->get('currentpage')) !== null && is_numeric($this->request->get('currentpage'))) {
                $currentpage = (int) $this->request->get('currentpage');
                if ($currentpage > $totalpages) {
                    $currentpage = $totalpages;
                } 
            }else{
                $currentpage = 1;
                } 
            $offset = ($currentpage - 1) * $nbByPage;
            $tablesJoin = $this->episodeManager->joinTables($offset, $nbByPage);
            $this->view->render('back/homePageBack', 'backend/templateBack', compact('episodesPubTot', 'countcoms', 'sum', 'episodesTot', 'tablesJoin','nbByPage', 'offset', 'currentpage', 'totalpages'));                
        }
        else {         
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
        } 

    }

    function reset()
    {
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        $infos = $this->usersManager->testInfos($_POST['pseudo']);
        
        session_start();
        
        if (isset($_SESSION['admConnected'])){
            if ((isset($_POST['pseudo']) && !empty($_POST['pseudo'])) && (isset($_POST['passOld']) && !empty($_POST['passOld'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && (isset($_POST['pass2']) && !empty($_POST['pass2']))) {
                $infos = $this->usersManager->testInfos($_POST['pseudo']);
                if (!empty($infos)){
                    if (password_verify(($_POST['passOld']), $infos[2]) === true){
                        if ($_POST['pass'] != $_POST['pass2']) {// on teste les deux mots de passe
                            $error = 'Les 2 mots de passe sont différents';
                            $message = null;
                            $this->view->render('back/profil', 'backend/templateBack', compact('message', 'error', 'sum', 'countcoms'));
                        }else{
                            if (preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50})", $_POST['pass'])){
                                $infos = $this->usersManager->resetInfos($_POST['pseudo'], password_hash($_POST['pass'], PASSWORD_DEFAULT));
                                $_SESSION['admConnected'] = false;
                                session_destroy();
                                $error = 'Vos changements ont bien été pris en compte';
                                $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
                            }else{
                                $error = 'Le nouveau mot de passe choisi n\'est pas valide';
                                $message = null;
                                $this->view->render('back/profil', 'backend/templateBack', compact('message', 'error', 'sum', 'countcoms'));
                            }     
                        }
                    }else{
                        $error = 'Impossible de modifier les informations';
                        $message = null;
                        $this->view->render('back/profil', 'backend/templateBack', compact('message', 'error', 'sum', 'countcoms'));
                    }
                }else{
                    $error = 'Impossible de modifier les informations';
                    $message = null;
                    $this->view->render('back/profil', 'backend/templateBack', compact('message', 'error', 'sum', 'countcoms'));
                }              
            }else{
                $error = 'Au moins un des champs est vide';
                $message = null;
                $this->view->render('back/profil', 'backend/templateBack', compact('message', 'countcoms', 'error', 'sum'));
            }
        }else{         
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
        }   
    }

    /*function reset()
    {
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        $hash = $this->usersManager->getHash();
        
        session_start();
        
        if (isset($_SESSION['admConnected'])){
            if ((isset($_POST['pseudo']) && !empty($_POST['pseudo'])) && (isset($_POST['passOld']) && !empty($_POST['passOld'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && (isset($_POST['pass2']) && !empty($_POST['pass2']))) {
                if (password_verify(($_POST['passOld']), $hash[0]) === true){
                    if ($_POST['pass'] != $_POST['pass2']) {// on teste les deux mots de passe
                        $error = 'Les 2 mots de passe sont différents';
                        $message = null;
                        $this->view->render('back/profil', 'backend/templateBack', compact('message', 'error', 'sum', 'countcoms'));
                    }else{
                        if (preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,50})", $_POST['pass'])){
                            $infos = $this->usersManager->resetInfos($_POST['pseudo'], password_hash($_POST['pass'], PASSWORD_DEFAULT));
                            $_SESSION['admConnected'] = false;
                            session_destroy();
                            $error = 'Vos changements ont bien été pris en compte';
                            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
                        }else{
                            $error = 'Le nouveau mot de passe choisi n\'est pas valide';
                            $message = null;
                            $this->view->render('back/profil', 'backend/templateBack', compact('message', 'error', 'sum', 'countcoms'));
                        }     
                    }
                }else{
                    $error = 'Impossible de modifier les informations';
                    $message = null;
                    $this->view->render('back/profil', 'backend/templateBack', compact('message', 'error', 'sum', 'countcoms'));
                }

            }else{
                $error = 'Au moins un des champs est vide';
                $message = null;
                $this->view->render('back/profil', 'backend/templateBack', compact('message', 'countcoms', 'error', 'sum'));
            }
        }else{         
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
        }   
    }*/

    function createEpisode()//méthode pour afficher la page de création d'épisode
    {
        session_start();
        $_SESSION['chapterNumber'] = null;
        $_SESSION['title'] = null;
        $_SESSION['content'] = null;
        

        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        $error = null;
                
        if (isset($_SESSION['admConnected'])) {               
            $this->view->render('back/createEpisode', 'backend/templateBack', compact('countcoms', 'sum', 'error'));
        }
        else {         
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
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

                
        if (isset($_SESSION['admConnected'])) { 
        
            if (isset($_POST['publish'])) {
                if (!empty($_POST['chapterNumber']) && !empty($_POST['title'])) {
                    $message = 'Episode ' . $_POST['chapterNumber'] . ' créé et publié';
                    $this->addPostedEpisode($_POST['chapterNumber'], $_POST['title'], $_POST['content']);
                    header('Location: index.php?action=episodes&ms=' . $message . '');
                    exit(); 
                }
                else {
                    $sum = $this->commentManager->countReports();
                    $countcoms = $this->commentManager->countComs();
                    $error = 'Vous devez spécifier le numéro et le titre de l\'épisode';
                    $this->view->render('back/createEpisode', 'backend/templateBack', compact('countcoms', 'sum', 'error'));
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
                    $sum = $this->commentManager->countReports();
                    $countcoms = $this->commentManager->countComs();
                    $error = 'Vous devez spécifier le numéro et le titre de l\'épisode';
                    $this->view->render('back/createEpisode', 'backend/templateBack', compact('countcoms', 'sum', 'error'));
                }
            }
            else {
                throw new Exception('Erreur : aucun identifiant de billet envoyé');
            }
        }
        else {         
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
        }
    }

    function addPostedEpisode(string $episodeNumber, string $title, string $content)//méthode pour ajouter un épisode publié à la bdd
    {
        $postedEpisode = $this->episodeManager->postEpisode($episodeNumber, $title, $content);

    }

    function addSavedEpisode(string $episodeNumber, string $title, string $content)//méthode pour ajouter un épisode archivé à la bdd
    {
        $postedEpisode = $this->episodeManager->saveEpisode($episodeNumber, $title, $content);

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
                
        if (isset($_SESSION['admConnected'])) {
        
            if (isset($_POST['publish'])) {
                if (!empty($_POST['nvchapter']) && !empty($_POST['nvtitle'])) {
                    if(($_GET['dt']) != null){
                        if($_POST['dateChoice'] === 'oldDate'){
                            $this->modifyPostedEpisodeSameDate($_GET['id'], $_POST['nvchapter'], $_POST['nvtitle'], $_POST['nvcontent']);
                            $message = 'Episode ' . $_POST['nvchapter'] . ' modifié et republié à la même date';
                            header('Location: index.php?action=episodes&ms=' . $message . '');
                            exit(); 
                        }else{
                            $this->modifyPostedEpisode($_GET['id'], $_POST['nvchapter'], $_POST['nvtitle'], $_POST['nvcontent']);
                            $message = 'Episode ' . $_POST['nvchapter'] . ' modifié et republié à la date de maintenant';
                            header('Location: index.php?action=episodes&ms=' . $message . '');
                            exit(); 
                        }
                    }else{
                        $this->modifyPostedEpisode($_GET['id'], $_POST['nvchapter'], $_POST['nvtitle'], $_POST['nvcontent']);
                        $message = 'Episode ' . $_POST['nvchapter'] . ' modifié et publié';
                        header('Location: index.php?action=episodes&ms=' . $message . '');
                        exit(); 
                    }    
                }else {
                        $sum = $this->commentManager->countReports();
                        $countcoms = $this->commentManager->countComs();
                        $episode = $this->episodeManager->findEpisode($_GET['id']);
                        $comments = $this->commentManager->findReportedComments($_GET['id']);
                        $error = 'Vous devez spécifier le titre et le numéro de l\'épisode';
                        $this->view->render('back/episodeBack', 'backend/templateBack', compact('countcoms', 'sum', 'error', 'episode', 'comments'));
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
                    $sum = $this->commentManager->countReports();
                    $countcoms = $this->commentManager->countComs();
                    $episode = $this->episodeManager->findEpisode($_GET['id']);
                    $comments = $this->commentManager->findReportedComments($_GET['id']);
                    $error = 'Vous devez spécifier le titre et le numéro de l\'épisode';
                    $this->view->render('back/episodeBack', 'backend/templateBack', compact('countcoms', 'sum', 'error', 'episode', 'comments'));
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
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
        }
    }

    function modifyPostedEpisode(string $post_id, string $nvchapter, string $nvtitle, string $nvcontent)//méthode pour modifier un épisode en le publiant
    {

        $postedModifiedEpisode = $this->episodeManager->postModifiedEpisode($_GET['id'], $nvchapter, $nvtitle, $nvcontent);

    }

    function modifyPostedEpisodeSameDate(string $post_id, string $nvchapter, string $nvtitle, string $nvcontent)//méthode pour modifier un épisode en le publiant
    {

        $postedModifiedEpisode = $this->episodeManager->postModifiedEpisodeSameDate($_GET['id'], $nvchapter, $nvtitle, $nvcontent, $_GET['dt']);

    }

    function modifySavedEpisode(string $post_id, string $nvchapter, string $nvtitle, string $nvcontent)//méthode pour modifier un épisode en l'archivant
    {

        $savedModifiedEpisode = $this->episodeManager->saveModifiedEpisode($_GET['id'], $nvchapter, $nvtitle, $nvcontent);

    }

    function modifyEpisode()//on affiche la page de modification d'un épisode dans le back avec ses commentaires
    {
        $episode = $this->episodeManager->findEpisode($_GET['id']);
        $comments = $this->commentManager->findReportedComments($_GET['id']);
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();
        
        session_start();
        $_SESSION['chapterNumber'] = null;
        $_SESSION['title'] = null;
        $_SESSION['content'] = null;
                
        if (isset($_SESSION['admConnected'])) { 
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $this->view->render('back/episodeBack', 'backend/templateBack', compact('episode', 'comments', 'sum', 'countcoms'));
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
        else {         
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
        }     
    }

    function comPage()//on affiche la page de gestion des commentaires
    {
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();

        $nbByPage = 5;
        $totalpages = ceil($countcoms[0]/$nbByPage);

        session_start();
                
        if (isset($_SESSION['admConnected'])) { 
            if (($this->request->get('currentpage')) !== null && is_numeric($this->request->get('currentpage'))) {
                $currentpage = (int) $this->request->get('currentpage');
                if ($currentpage > $totalpages) {
                    $currentpage = $totalpages;
                } 
            }else{
                $currentpage = 1;
                } 
            $offset = ($currentpage - 1) * $nbByPage;
            $allComs = $this->commentManager->findAllComments($offset, $nbByPage);
            $this->view->render('back/commentsBack', 'backend/templateBack', compact('nbByPage', 'currentpage', 'offset', 'totalpages', 'countcoms', 'allComs', 'sum'));                          
        }else{         
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connectionView', 'frontend/templateFront', compact('error'));
        }     
    }

    function episodeDelete()//méthode pour supprimer un épisode
    {
        $this->episodeManager->deleteEpisode($_GET['id']);
    }

    function commentDelete()//méthode pour supprimer un commentaire depuis la page d'un épisode
    {
        $this->commentManager->deleteComment($_GET['id']);
        $episode = $this->episodeManager->findEpisode($_GET['postid']);
        $comments = $this->commentManager->findReportedComments($_GET['postid']);
        $sum = $this->commentManager->countReports();

        session_start();
                
        if (isset($_SESSION['admConnected'])) {               
            header('Location: index.php?action=modifyEpisode&id='. $_GET['postid'] );
            exit();
        }
        else {         
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
        }
    }

    function comDelete()//méthode pour supprimer un commentaire depuis la page des commentaires
    {   
        session_start();

        if (isset($_SESSION['admConnected'])) { 
            
            $this->commentManager->deleteComment($_GET['id']);
            header('Location: index.php?action=commentsPage');
            exit();
        }
        else {         
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
        }
    }

    function deleteR()//méthode pour supprimer les signalements d'un commentaire depuis la page des commentaires
    {   
        session_start();

        if (isset($_SESSION['admConnected'])) { 
            
            $this->commentManager->deleteReports($_GET['id']);
            header('Location: index.php?action=commentsPage');
            exit();
        }
        else {         
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
        }
    }

    function profil()//méthode pour aller à la page profil
    {
        session_start();
        
        $sum = $this->commentManager->countReports();
        $countcoms = $this->commentManager->countComs();

                
        if (isset($_SESSION['admConnected'])) {               
            $this->view->render('back/profil', 'backend/templateBack', compact('countcoms', 'sum'));
        }
        else {         
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
        }
    }

    function disconnection()//méthode pour se déconnecter du back
    {
        session_start();
                
        if (isset($_SESSION['admConnected'])) {
            $_SESSION['admConnected'] = false;
            session_destroy();
            header('Location: index.php?');
            exit();
        }
        else {
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'frontend/templateFront', compact('error'));
        }
    }
}