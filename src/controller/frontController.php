<?php
declare(strict_types=1);

namespace Projet4\Controller;

use Projet4\Model\EpisodeManager;
use Projet4\Model\CommentManager;
use Projet4\View\View;
use Projet4\Tools\Request;


class FrontController{
        
    private function getInstances($method) 
    {
        if ($method === 'listEpisodes'){
            $episodeManager = new episodeManager();
            $view = new view();
            $request = new Request();
        }
    }
       
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
        $request = new Request();


        if (null != ($request->get('currentpage')) && is_numeric($request->get('currentpage'))) {

            $currentpage = (int) $request->get('currentpage');

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

            $view->render('front/episodes', 'frontend/templateFront', compact('episodes', 'episodesTot', 'pagina','nbByPage', 'offset', 'currentpage', 'totalpages'));
            
            
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
        $request = new Request();

        if (null != ($request->get('er'))){
            $error = $request->get('er');
        }else $error = null;


        if (null != ($request->get('currentpage')) && is_numeric($request->get('currentpage'))) {

            $currentpage = (int) $request->get('currentpage');
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

            $commentManager = new commentManager();
            $comments = $commentManager->getComments($pagina[0]->post_id);
            $view->render('front/episodePage', 'frontend/templateFront', compact('comments', 'error', 'episodes', 'episodesTot', 'pagina','nbByPage', 'offset', 'currentpage', 'totalpages'));
            
    }    

    /*public function episode():void //méthode pour récupérer un épisode publié en fonction de son id
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();
        $request = new Request();

        if (null != ($request->get('ps'))){
            $position = $request->get('ps');
        }else $position = null;

        $episodesTot = $episodeManager->countEpisodesPub();
        $nbByPage = 1;
        $offset = 0;
        $totalpages = ceil($episodesTot[0]/$nbByPage);
        $currentpage= $position + 1;

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
                $view->render('front/episode', 'frontend/templateFront', compact('currentpage', 'totalpages', 'episode', 'comments', 'error'));
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
        $request = new Request();

        $episode = $episodeManager->getPostedEpisode($_GET['id']);
        $comments = $commentManager->getComments($_GET['id']);
        
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            if (!empty($_POST['author']) && !empty($_POST['comment'])) {
                $this->addComment($_GET['id'], $_GET['nb'], $_POST['author'], $_POST['comment']);
            }
            else {
                $error = 'Veuillez remplir tous les champs';
                header('Location: index.php?action=episodePage&currentpage=' . $_GET['currentpage'] . '&er=' . $error . '#makeComment');
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
            header('Location: index.php?action=episodePage&currentpage=' . $_GET['currentpage'] . '#headCom');
            exit();
        }
    }

    public function report()
    {
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            if($_GET['rp'] < 24){
                $this->reportComment($_GET['id']);
            }
                header('Location: index.php?action=episodePage&currentpage=' . ($_GET['currentpage']) . '#headCom');
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

        header('Location: index.php?action=episodePage&currentpage=' . ($_GET['currentpage']) . '#headCom');
    }

    public function homePage()//méthode pour démarrer une session lorsque on affiche la page d'accueil et récupérer le dernier épisode posté
    {
        session_start();
        $episodeManager = new episodeManager();
        $view = new view();

        $episodesTot = $episodeManager->countEpisodesPub();
        $nbByPage = $episodesTot[0];
        $offset = 0;
        $totalpages = $episodesTot;

        $lastEpisode = $episodeManager->getLastEpisode();
        $pagina = $episodeManager->PagineEpisodes($offset, $nbByPage);

        $view->render('front/homePage', 'frontend/templateFront', compact('lastEpisode', 'pagina', 'totalpages', 'offset', 'nbByPage', 'episodesTot'));
        
    }

    public function connectionPage()//méthode pour afficher la page de connection
    {
        $view = new view();
        $error = null;


        $view->render('front/connection', 'frontend/templateFrontAdmin', compact('error'));
    }
}

