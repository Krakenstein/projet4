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
    private $request;
   
    public function __construct()
    {
        $this->episodeManager = new EpisodeManager();
        $this->commentManager = new CommentManager();
        $this->view = new View();
        $this->request = new Request();
    }
       
    public function listEpisodes():void //méthode pour récupérer la liste paginée des épisodes publiés
    {        
        $episodesTot = $this->episodeManager->countEpisodesPub();
        $nbByPage = 5;
        $totalpages = (int) ceil($episodesTot[0]/$nbByPage);

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) !== '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            } 
        }

        $offset = ($currentpage - 1) * $nbByPage;
        $pagina = $this->episodeManager->pagineEpisodes((int) $offset, (int) $nbByPage);
        $this->view->render('front/episodes', 'front/layout', compact('episodesTot', 'pagina','nbByPage', 'offset', 'currentpage', 'totalpages'));   
    }        
    
    public function episodePage():void //méthode pour récupérer un épisode publié
    {
        $episode = $this->episodeManager->findPostedEpisodeWithComs((int) $this->request->get('id'));
        $episodesTot = $this->episodeManager->countEpisodesPub();

        $totalpages = (int) $episodesTot[0];

        if (($this->request->get('er')) ==! null){
            $error = $this->request->get('er');
        }else $error = null;

        
        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) !== '0' &&is_numeric($this->request->get('currentpage'))) {
            $currentpage = (int) $this->request->get('currentpage');
            if ($currentpage > $totalpages) {
                $currentpage = $totalpages;
            } 
        }
        $this->view->render('front/episode', 'front/layout', compact('episodesTot', 'currentpage', 'totalpages', 'episode', 'error')); 
     
    } 

    public function previousNext():void //méthode pour récupérer un épisode publié
    {       
        $episodesTot = $this->episodeManager->countEpisodesPub();
        $totalpages = (int) $episodesTot[0];
        
        $currentpage = $this->request->get('currentpage');
        if ($currentpage < 1){
            $currentpage = 1;
        }elseif ($currentpage > $totalpages) {
            $currentpage = $totalpages;
        }
        $offset = $currentpage - 1;
        $previous = $this->episodeManager->previousNextEpisode($offset);
        header('Location: index.php?action=episodePage&currentpage=' . (int) $this->request->get('currentpage') . '&id=' . (int) $previous[0] . '#title');
        exit();
    }

    /*public function previous():void //méthode pour récupérer un épisode publié
    {
        $previous = $this->episodeManager->previousEpisode($this->request->get['chapter'], $this->request->get['date']);
        header('Location: index.php?action=episodePage&currentpage=' . (int) $this->request->get('currentpage') . '&id=' . (int) $previous[0] . '#title');
        exit();
    }

    public function next():void //méthode pour récupérer un épisode publié
    {
        $next = $this->episodeManager->nextEpisode($this->request->get['chapter'], $this->request->get['date']);
        header('Location: index.php?action=episodePage&currentpage=' . (int) $this->request->get('currentpage') . '&id=' . (int) $next[0] . '#title');
        exit();
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
        $pagina = $this->episodeManager->pagineEpisodes($offset, $nbByPage);
        $comments = $this->commentManager->findComments($pagina[0]->post_id);
        $this->view->render('front/episodePage', 'front/layout', compact('comments', 'error', 'episodesTot', 'pagina','nbByPage', 'offset', 'currentpage', 'totalpages'));           
    }*/

    public function newCom():void
    {
        $request = new Request();

        $episode = $this->episodeManager->findPostedEpisodeWithComs((int) $this->request->get('id'));
        
        
        if (($this->request->get('id')) !== null && $this->request->get('id') > 0) {
            if (!empty($this->request->post('author')) && !empty($this->request->post('comment'))) {
                $affectedLines = $this->commentManager->postComment((int) $episode[0]->post_id, (int) $episode[0]->chapterNumber, $this->request->post('author'), $this->request->post('comment'));
                if ($affectedLines === false) {
                    throw new Exception('Impossible d\'ajouter le commentaire !');
                }else {
                    header('Location: index.php?action=episodePage&currentpage=' . (int) $this->request->get('currentpage') . '&id=' . (int) $this->request->get('id') . '#headCom');
                    exit();
                }       
            }else {
                $error = 'Veuillez remplir tous les champs';
                header('Location: index.php?action=episodePage&currentpage=' . (int) $this->request->get('currentpage') . '&id=' . (int) $this->request->get('id') . '&er=' . $error . '#makeComment');
                exit();
            }
        }else {
            throw new Exception('Erreur : aucun identifiant de billet envoyé');
        }
    }

    public function report():void
    {
        if (($this->request->get('id')) !== null && $this->request->get('id') > 0) {
            if($this->request->get('rp') < 24){
                $this->commentManager->reports((int) $this->request->get('comId'));
                header('Location: index.php?action=episodePage&currentpage=' .(int) $this->request->get('currentpage') . '&id=' .(int) ($this->request->get('id')) . '#headCom');
            }
                header('Location: index.php?action=episodePage&currentpage=' .(int) $this->request->get('currentpage') . '&id=' .(int) ($this->request->get('id')) . '#headCom');
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
        $pagina = $this->episodeManager->pagineEpisodes($offset, $nbByPage);

        $this->view->render('front/homePage', 'front/layout', compact('lastEpisode', 'pagina', 'totalpages', 'offset', 'nbByPage', 'episodesTot'));
        
    }

    public function connectionPage():void//méthode pour afficher la page de connection
    {
        $this->view->render('front/connection', 'front/layout');
    }
}

