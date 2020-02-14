<?php
declare(strict_types=1);

namespace Projet4\Controller;

use Projet4\Model\EpisodeManager;
use Projet4\Model\CommentManager;
use Projet4\View\View;
use Projet4\Tools\Request;


class FrontController{
        
    private $episodeManager;
    private $commentManager;
    private $view;
   
    public function __construct()
    {
        $this->episodeManager = new EpisodeManager();
        $this->commentManager = new CommentManager();
        $this->view = new View();
    }
       
    public function listEpisodes():void //méthode pour récupérer la liste paginée des épisodes publiés
    {
        
        $episodes = $this->episodeManager->findEpisodes();
        $episodesTot = $this->episodeManager->countEpisodesPub();
        $nbByPage = 5;
        $offset = 0;
        $totalpages = ceil($episodesTot[0]/$nbByPage);
        $currentpage=0;
        
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
             $pagina = $this->episodeManager->PagineEpisodes($offset, $nbByPage);

             $this->view->render('front/episodes', 'frontend/templateFront', compact('episodes', 'episodesTot', 'pagina','nbByPage', 'offset', 'currentpage', 'totalpages'));
            
            
        }        
    
    public function episodePage():void //méthode pour récupérer un épisode publié en fonction de son numéro de chapitre
    {
        $episodes = $this->episodeManager->findEpisodes();
        $episodesTot = $this->episodeManager->countEpisodesPub();
        $nbByPage = 1;
        $offset = 0;
        $totalpages = ceil($episodesTot[0]/$nbByPage);
        $currentpage=0;
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
             $pagina = $this->episodeManager->PagineEpisodes($offset, $nbByPage);

            $comments = $this->commentManager->findComments($pagina[0]->post_id);
            $this->view->render('front/episodePage', 'frontend/templateFront', compact('comments', 'error', 'episodes', 'episodesTot', 'pagina','nbByPage', 'offset', 'currentpage', 'totalpages'));
            
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
        $request = new Request();

        $episode = $this->episodeManager->findPostedEpisode($_GET['id']);
        $comments = $this->commentManager->findComments($_GET['id']);
        
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            if (!empty($_POST['author']) && !empty($_POST['comment'])) {
                $affectedLines = $this->commentManager->postComment($episode->post_id, $episode->chapterNumber, $_POST['author'], $_POST['comment']);

                if ($affectedLines === false) {
                    throw new Exception('Impossible d\'ajouter le commentaire !');
                }else {
                    header('Location: index.php?action=episodePage&currentpage=' . $_GET['currentpage'] . '#headCom');
                    exit();
                }
        
            }else {
                $error = 'Veuillez remplir tous les champs';
                header('Location: index.php?action=episodePage&currentpage=' . $_GET['currentpage'] . '&er=' . $error . '#makeComment');
                exit();
            }
        }else {
            throw new Exception('Erreur : aucun identifiant de billet envoyé');
        }
    }

    public function report()
    {
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            if($_GET['rp'] < 24){
                $numberComments = $this->commentManager->reports($_GET['id']);
                header('Location: index.php?action=episodePage&currentpage=' . ($_GET['currentpage']) . '#headCom');
            }
                header('Location: index.php?action=episodePage&currentpage=' . ($_GET['currentpage']) . '#headCom');
        }
        else {
            throw new Exception('Erreur : aucun identifiant de commentaire envoyé');
        }
    }

    public function homePage()//méthode pour démarrer une session lorsque on affiche la page d'accueil et récupérer le dernier épisode posté
    {
        session_start();

        $episodesTot = $this->episodeManager->countEpisodesPub();
        $nbByPage = $episodesTot[0];
        $offset = 0;
        $totalpages = $episodesTot;

        $lastEpisode = $this->episodeManager->findLastEpisode();
        $pagina = $this->episodeManager->PagineEpisodes($offset, $nbByPage);

        $this->view->render('front/homePage', 'frontend/templateFront', compact('lastEpisode', 'pagina', 'totalpages', 'offset', 'nbByPage', 'episodesTot'));
        
    }

    public function connectionPage()//méthode pour afficher la page de connection
    {
        $error = null;


        $this->view->render('front/connection', 'frontend/templateFrontAdmin', compact('error'));
    }
}

