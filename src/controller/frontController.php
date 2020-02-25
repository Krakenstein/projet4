<?php
declare(strict_types=1);

namespace Projet4\Controller;

use Projet4\Model\EpisodeManager;
use Projet4\Model\CommentManager;
use Projet4\View\View;
use Projet4\Tools\Request;
use Projet4\Tools\NoCsrf;
use Projet4\Tools\Session;


class FrontController{
        
    private $episodeManager;
    private $commentManager;
    private $view;
    private $request;
    private $noCsrf;
    private $session;
   
    public function __construct()
    {
        $this->episodeManager = new EpisodeManager();
        $this->commentManager = new CommentManager();
        $this->view = new View();
        $this->request = new Request();
        $this->noCsrf = new NoCsrf();
        $this->session = new Session();
    }
       
    public function listEpisodes():void //méthode pour afficher la liste paginée des épisodes publiés
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
    
    public function episodePage():void //méthode pour afficher un épisode publié en fonction de son id avec ses commentaires
    {
        $episode = $this->episodeManager->findPostedEpisode((int) $this->request->get('id'));
        $episodesTot = $this->episodeManager->countEpisodesPub();
        $token = $this->noCsrf->createToken();

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

        $this->view->render('front/episode', 'front/layout', compact('episodesTot', 'currentpage', 'totalpages', 'episode', 'error', 'token'));     
    } 

    public function previousNext():void //méthode pour afficher l'épisode suivant ou précédent
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

    public function newCom():void //méthode pour afficher un nouveau commentaire sur la page d'un épisode
    {
        $episode = $this->episodeManager->findPostedEpisode((int) $this->request->get('id'));
        
        
        if ($this->request->get('id') !== null && $this->request->get('id') > 0 
        && $this->request->post('csrf') !== null && $this->request->post('csrf') === $this->session->getSessionData("token")) {
            if (!empty($this->request->post('author')) && !empty($this->request->post('comment'))) {
                if (empty($episode)) {
                    header('Location: index.php?action=episodePage&currentpage=' . (int) $this->request->get('currentpage') . '&id=' . (int) $this->request->get('id') . '#headCom');
                    exit();
                }else {
                    $affectedLines = $this->commentManager->postComment((int) $episode[0]->post_id, $this->request->post('author'), $this->request->post('comment'));
                    header('Location: index.php?action=episodePage&currentpage=' . (int) $this->request->get('currentpage') . '&id=' . (int) $this->request->get('id') . '#headCom');
                    exit();
                }       
            }else {
                $error = 'Veuillez remplir tous les champs';
                header('Location: index.php?action=episodePage&currentpage=' . (int) $this->request->get('currentpage') . '&id=' . (int) $this->request->get('id') . '&er=' . $error . '#makeComment');
                exit();
            }
        }else {
            header('Location: index.php?action=episodePage&currentpage=' . (int) $this->request->get('currentpage') . '&id=' . (int) $this->request->get('id') . '#headCom');
            exit();
        }
        
    }

    public function report():void //méthode pour afficher un signalement de plus à un commentaire de la page d'un épisode
    {
        if (($this->request->get('id')) !== null && $this->request->get('id') > 0) {
            if($this->request->get('rp') < 24){
                $this->commentManager->reports((int) $this->request->get('comId'));
            }
            header('Location: index.php?action=episodePage&currentpage=' .(int) $this->request->get('currentpage') . '&id=' .(int) ($this->request->get('id')) . '#headCom');
            exit();
        }
    }

    public function homePage():void//méthode pour afficher la page d'accueil et récupérer le dernier épisode posté
    {
        $episodesTot = $this->episodeManager->countEpisodesPub();
        $nbByPage = (int) $episodesTot[0];
        $offset = (int) 0;
        $totalpages = $episodesTot;

        $lastEpisode = $this->episodeManager->findLastEpisode();
        
        $pagina = $this->episodeManager->pagineEpisodes( (int) $offset, (int) $nbByPage);

        $this->view->render('front/homePage', 'front/layout', compact('lastEpisode', 'pagina', 'totalpages', 'offset', 'nbByPage', 'episodesTot'));
        
    }

    public function connectionPage():void//méthode pour afficher la page de connection
    {
        $token = $this->noCsrf->createToken();
        $this->view->render('front/connection', 'front/layout', compact('token'));
    }
}

