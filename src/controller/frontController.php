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
        $episodesTot = $this->episodeManager->countEpisodesPub();
        $nbByPage = 5;
        $totalpages = ceil($episodesTot[0]/$nbByPage);
        $request = new Request();

        if (($request->get('currentpage')) !== null && is_numeric($request->get('currentpage'))) {
            $currentpage = (int) $request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            } 
        }else{
            $currentpage = 1;
            }

        $offset = ($currentpage - 1) * $nbByPage;
        $pagina = $this->episodeManager->PagineEpisodes($offset, $nbByPage);
        $this->view->render('front/episodes', 'front/layout', compact('episodesTot', 'pagina','nbByPage', 'offset', 'currentpage', 'totalpages'));   
    }        
    
    public function episodePage():void //méthode pour récupérer un épisode publié 
    {
        //$episodes = $this->episodeManager->findEpisodes();
        $episodesTot = $this->episodeManager->countEpisodesPub();
        $nbByPage = 1;
        //$offset = 0;
        $totalpages = ceil($episodesTot[0]/$nbByPage);
        $currentpage=0;
        $request = new Request();

        if (null != ($request->get('er'))){
            $error = $request->get('er');
        }else $error = null;

        if (($request->get('currentpage')) !== null && is_numeric($request->get('currentpage'))) {
            $currentpage = (int) $request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            } 
        }else{
            $currentpage = 1;
            }

        $offset = ($currentpage - 1) * $nbByPage;
        $pagina = $this->episodeManager->PagineEpisodes($offset, $nbByPage);
        $comments = $this->commentManager->findComments($pagina[0]->post_id);
        $this->view->render('front/episodePage', 'front/layout', compact('comments', 'error', 'episodesTot', 'pagina','nbByPage', 'offset', 'currentpage', 'totalpages'));           
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
            $view->render('front/episodeBlankView', 'front/layout');
        }
        else {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $view->render('front/episode', 'front/layout', compact('currentpage', 'totalpages', 'episode', 'comments', 'error'));
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
    }*/

    public function newCom():void
    {
        $request = new Request();

        $episode = $this->episodeManager->findPostedEpisode((int) $_GET['id']);
        $comments = $this->commentManager->findComments((int) $_GET['id']);
        
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            if (!empty($_POST['author']) && !empty($_POST['comment'])) {
                $affectedLines = $this->commentManager->postComment((int) $episode->post_id, (int) $episode->chapterNumber, $_POST['author'], $_POST['comment']);
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

    public function report():void
    {
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            if($_GET['rp'] < 24){
                $this->commentManager->reports((int) $_GET['id']);
                header('Location: index.php?action=episodePage&currentpage=' . ($_GET['currentpage']) . '#headCom');
            }
                header('Location: index.php?action=episodePage&currentpage=' . ($_GET['currentpage']) . '#headCom');
        }
        else {
            throw new Exception('Erreur : aucun identifiant de commentaire envoyé');
        }
    }

    public function homePage():void//méthode pour démarrer une session lorsque on affiche la page d'accueil et récupérer le dernier épisode posté
    {
        session_start();

        $episodesTot = $this->episodeManager->countEpisodesPub();
        $nbByPage = (int) $episodesTot[0];
        $offset = (int) 0;
        $totalpages = $episodesTot;

        $lastEpisode = $this->episodeManager->findLastEpisode();
        $pagina = $this->episodeManager->PagineEpisodes($offset, $nbByPage);

        $this->view->render('front/homePage', 'front/layout', compact('lastEpisode', 'pagina', 'totalpages', 'offset', 'nbByPage', 'episodesTot'));
        
    }

    public function connectionPage():void//méthode pour afficher la page de connection
    {
        $this->view->render('front/connection', 'front/layout');
    }
}

