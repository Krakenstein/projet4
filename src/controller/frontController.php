<?php
declare(strict_types=1);

require_once('src/model/episodeManager.php');
require_once('src/model/commentManager.php');
require_once('src/view/View.php');

class FrontController{
        
    public function listEpisodes():void //méthode pour récupérer la liste paginée des épisodes publiés
    {
        $episodeManager = new episodeManager();
        $episodes = $episodeManager->getEpisodes();
        $episodesTot = $episodeManager->countEpisodesPub();
        $nbByPage = 5;
        $offset = 0;
        $totalpages = ceil($episodesTot[0]/$nbByPage);
        $currentpage=0;
        $view = new view();


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
             $pagina = $episodeManager->PagineEpisodes($offset, $nbByPage);

             if (empty($pagina)) { 
                $view->render('front/episodesBlankView', 'frontend/templateFront');
            }else{
                $view->render('front/episodesView', 'frontend/templateFront', compact('episodes', 'episodesTot', 'pagina','nbByPage', 'offset', 'currentpage', 'totalpages'));
            }
            
        }        
    
    public function episodePage():void //méthode pour récupérer un épisode publié en fonction de son numéro de chapitre
    {
        $episodeManager = new episodeManager();
        $episodes = $episodeManager->getEpisodes();
        $episodesTot = $episodeManager->countEpisodesPub();
        $nbByPage = 1;
        $offset = 0;
        $totalpages = ceil($episodesTot[0]/$nbByPage);
        $currentpage=0;
        $view = new view();

        if (isset($_GET['er'])){
            $error = $_GET['er'];
        }else $error = null;


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
             $pagina = $episodeManager->PagineEpisodes($offset, $nbByPage);

             if (empty($pagina)) { 
                $view->render('front/episodePageBlankView', 'frontend/templateFront');
            }else{
                $commentManager = new commentManager();
                $comments = $commentManager->getComments($pagina[0]->post_id);
                $view->render('front/episodePageView', 'frontend/templateFront', compact('comments', 'error', 'episodes', 'episodesTot', 'pagina','nbByPage', 'offset', 'currentpage', 'totalpages'));
            }
    }    


    /*public function episode():void //méthode pour récupérer un épisode publié en fonction de son numéro de chapitre
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();

        $episode = $episodeManager->getPostedEpisode($_GET['id']);
        $comments = $commentManager->getComments($_GET['id']);
        $totalpages = $episodeManager->countEpisodesPub();
        $currentpage = 0;

        if (isset($_GET['er'])){
            $error = $_GET['er'];
        }else $error = null;

        
        if (isset($_GET['ps']) && is_numeric($_GET['ps'])) {

            $currentpage = (int) $_GET['ps'];
            } else {

                $currentpage = 1;
             } 
             

             if ($currentpage > $totalpages) {

                $currentpage = $totalpages;
             } 

             if ($currentpage < 1) {
 
                $currentpage = 1;
             } 

             if ($episode === false) {
                $view->render('front/episodeBlankView', 'frontend/templateFront');
            }
            else {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $view->render('front/episodeView', 'frontend/templateFront', compact('currentpage', 'totalpages', 'episode', 'comments', 'error'));
                }
                else {
                    throw new Exception('Aucun numéro dépisode envoyé');
                }
            }
        
    }*/

    public function episode():void //méthode pour récupérer un épisode publié en fonction de son id
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();

        $episodesTot = $episodeManager->countEpisodesPub();
        $nbByPage = 1;
        $offset = 0;
        $totalpages = ceil($episodesTot[0]/$nbByPage);
        $currentpage=$_GET['ps'] + 1;

        $episode = $episodeManager->getPostedEpisode($_GET['id']);
        $comments = $commentManager->getComments($_GET['id']);

        if (isset($_GET['er'])){
            $error = $_GET['er'];
        }else $error = null;

        if ($episode === false) {
            $view->render('front/episodeBlankView', 'frontend/templateFront');
        }
        else {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $view->render('front/episodeView', 'frontend/templateFront', compact('currentpage', 'totalpages', 'episode', 'comments', 'error'));
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
    }
    /*public function previous():void
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();
        $error = null;
        $totalpages = $episodeManager->countEpisodesPub();
        $currentpage = (int) $_GET['currentpage'];

        $episode = $episodeManager->previousEpisode($_GET['chpt'], $_GET['dt']);

        if ($episode === false) {
            header('Location: index.php?action=episode&id=' . $_GET['id']);
            exit();
        }
        else {
            if (isset($_GET['chpt']) && $_GET['chpt'] > 0) {
                $comments = $commentManager->getComments($episode->post_id);
                $view->render('front/episodeView', 'frontend/templateFront', compact('currentpage', 'totalpages', 'episode', 'comments', 'error'));
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
    }

    public function next():void
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();
        $error = null;
        $totalpages = $episodeManager->countEpisodesPub();
        $currentpage = (int) $_GET['currentpage'];

        $episode = $episodeManager->nextEpisode($_GET['chpt'], $_GET['dt']);

        if ($episode === false) {
            header('Location: index.php?action=episode&id=' . $_GET['id']);
            exit();
        }
        else {
            if (isset($_GET['chpt']) && $_GET['chpt'] > 0) {
                $comments = $commentManager->getComments($episode->post_id);
                $view->render('front/episodeView', 'frontend/templateFront', compact('currentpage', 'totalpages', 'episode', 'comments', 'error'));
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
    }*/

    public function newCom():void
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();

        $episode = $episodeManager->getPostedEpisode($_GET['id']);
        $comments = $commentManager->getComments($_GET['id']);
        
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            if (!empty($_POST['author']) && !empty($_POST['comment'])) {
                $this->addComment($_GET['id'], $_GET['nb'], $_POST['author'], $_POST['comment']);
                $this->countCom($_GET['id']);
            }
            else {
                $error = 'Veuillez remplir tous les champs';
                header('Location: index.php?action=episode&id=' . $_GET['id'] . '&er=' . $error . '#makeComment');
                exit();
            }
        }
        else {
            throw new Exception('Erreur : aucun identifiant de billet envoyé');
        }
    }

    public function addComment(string $post_id, string $episodeNumber, string $author, string $comment):bolean //méthode pour rajouter un commentaire à un épisode donné en fonction de son numéro de chapitre
    {
        $commentManager = new commentManager();
        
        $affectedLines = $commentManager->postComment($post_id, $episodeNumber, $author, $comment);

        if ($affectedLines === false) {
            throw new Exception('Impossible d\'ajouter le commentaire !');
        }
        else {
            header('Location: index.php?action=episode&id=' . $post_id . '#headCom');
            exit();
        }
    }

    public function countCom(string $post_id):bolean //méthode pour compter le nbre de commentaires d'un épisode
    {
        $commentManager = new commentManager();
        $numberComments = $commentManager->countComments($post_id);
    }

    public function report()
    {
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            if($_GET['rp'] < 24){
                $this->reportComment($_GET['id']);
            }
                header('Location: index.php?action=episode&id=' . ($_GET['postid']) . '#headCom');
        }
        else {
            throw new Exception('Erreur : aucun identifiant de commentaire envoyé');
        }
    }

    public function reportComment(string $id)//méthode pour signaler un commentaire
    {
        $commentManager = new commentManager();
        $episodeManager = new episodeManager();

        $episode = $episodeManager->getEpisode($_GET['postid']);
        $comments = $commentManager->getComments($_GET['id']);
        $numberComments = $commentManager->reports($id);

        header('Location: index.php?action=episode&id=' . ($_GET['postid']) . '#headCom');
    }

    public function homePage()//méthode pour démarrer une session lorsque on affiche la page d'accueil et récupérer le dernier épisode posté
    {
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

    public function connectionPage()//méthode pour afficher la page de connection
    {
        $view = new view();
        $error = null;

        $view->render('front/connectionView', 'frontend/templateFrontAdmin', compact('error'));
    }
}

