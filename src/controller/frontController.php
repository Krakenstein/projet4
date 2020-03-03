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
    
    public function listEpisodes():void //méthode pour afficher la liste paginée des épisodes publiés
    {        
        $episodesTot = $this->episodeManager->countEpisodesPub();
        $nbByPage = 5;
        $totalpages = (int) ceil($episodesTot[0]/$nbByPage);

        $currentpage = 1;
        if (($this->request->get('currentpage')) !== null && ($this->request->get('currentpage')) > '0' &&is_numeric($this->request->get('currentpage'))) {
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
        $this->session->setSessionData('author', null);
        $pseudo = $this->session->getSessionData("author");
        $this->session->setSessionData('comment', null);
        $comment = $this->session->getSessionData("comment");
        
        $episode = $this->episodeManager->findPostedEpisode((int) $this->request->get('id'));
        $episodesTot = $this->episodeManager->countEpisodesPub();
        $token = $this->noCsrf->createToken();
        $pagina = $this->episodeManager->pagineEpisodes( 0, (int) $episodesTot[0]);

        $totalpages = (int) $episodesTot[0];

        if (($this->request->get('er')) ==! null){
            $error = $this->request->get('er');
        }else $error = null;
   
        if (!empty($episode)){
            $currentpage = array_search($episode[0]->post_id, array_column($pagina, 'post_id')) + 1;
            $this->view->render('front/episode', 'front/layout', compact('pagina', 'pseudo', 'comment', 'episodesTot', 'currentpage', 'totalpages', 'episode', 'error', 'token')); 
            exit();
        }
        $this->view->render('front/episode', 'front/layout'); 
    
    } 

    public function previous():void //méthode pour afficher l'épisode précédent
    {       
        $episodesTot = $this->episodeManager->countEpisodesPub();
        $totalpages = (int) $episodesTot[0];
        $episode = $this->episodeManager->findPostedEpisode((int) $this->request->get('id'));
        $pagina = $this->episodeManager->pagineEpisodes( 0, (int) $episodesTot[0]);

        if((array_search($episode[0]->post_id, array_column($pagina, 'post_id')) - 1) >= 0){
            $previous = $this->episodeManager->previousNextEpisode(array_search($episode[0]->post_id, array_column($pagina, 'post_id')) - 1);
            header('Location: index.php?action=episodePage&id=' . (int) $previous[0] . '#title');
            exit();
        }
        header('Location: index.php?action=episodePage&id=' . $this->request->get('id') . '#title');
        exit();
       
    }

    public function next():void //méthode pour afficher l'épisode précédent
    {       
        $episodesTot = $this->episodeManager->countEpisodesPub();
        $totalpages = (int) $episodesTot[0];
        $episode = $this->episodeManager->findPostedEpisode((int) $this->request->get('id'));
        $pagina = $this->episodeManager->pagineEpisodes( 0, (int) $episodesTot[0]);

        if((array_search($episode[0]->post_id, array_column($pagina, 'post_id')) - 1) < ($episodesTot[0] - 2)){
            $previous = $this->episodeManager->previousNextEpisode(array_search($episode[0]->post_id, array_column($pagina, 'post_id')) + 1);
            header('Location: index.php?action=episodePage&id=' . (int) $previous[0] . '#title');
            exit();
        }
        header('Location: index.php?action=episodePage&id=' . $this->request->get('id') . '#title');
        exit();
    }

    public function newCom():void //méthode pour afficher un nouveau commentaire sur la page d'un épisode
    {
        $episode = $this->episodeManager->findPostedEpisode((int) $this->request->get('id'));
        
        if(!empty($this->request->post('author')) || !empty($this->request->post('comment')))
        {
            $this->session->setSessionData('author', $this->request->post('author'));
            $pseudo = $this->session->getSessionData("author");
            $this->session->setSessionData('comment', $this->request->post('comment'));
            $comment = $this->session->getSessionData("comment");
        }
        
        if($this->request->post('csrf') !== null && $this->request->post('csrf') === $this->noCsrf->isTokenValid()){
            if ($this->request->get('id') !== null && $this->request->get('id') > 0 ) {
                if (!empty($this->request->post('author')) && !empty($this->request->post('comment'))) {
                    if (empty($episode)) {
                        header('Location: index.php?action=episodePage&currentpage=' . (int) $this->request->get('currentpage') . '&id=' . (int) $this->request->get('id') . '#headCom');
                        exit();
                    }else {
                        $this->commentManager->postComment((int) $episode[0]->post_id, $this->request->post('author'), $this->request->post('comment'));
                        header('Location: index.php?action=episodePage&currentpage=' . (int) $this->request->get('currentpage') . '&id=' . (int) $this->request->get('id') . '#headCom');
                        exit();
                    }       
                }else {
                    $error = 'Veuillez remplir tous les champs';
                    $episode = $this->episodeManager->findPostedEpisode((int) $this->request->get('id'));
                    $episodesTot = $this->episodeManager->countEpisodesPub();
                    $pagina = $this->episodeManager->pagineEpisodes( 0, (int) $episodesTot[0]);
                    $token = $this->noCsrf->createToken();
                    $totalpages = (int) $episodesTot[0];
                    $currentpage = array_search($episode[0]->post_id, array_column($pagina, 'post_id')) + 1;
                    $this->view->render('front/episode', 'front/layout', compact('pagina', 'pseudo', 'comment', 'episodesTot', 'currentpage', 'totalpages', 'episode', 'error', 'token'));
                }
            }else {
                header('Location: index.php?action=episodePage&currentpage=' . (int) $this->request->get('currentpage') . '&id=' . (int) $this->request->get('id') . '#headCom');
                exit();
            }
        }else {
            $error = 'formulaire invalide';
            $episode = $this->episodeManager->findPostedEpisode((int) $this->request->get('id'));
            $episodesTot = $this->episodeManager->countEpisodesPub();
            $pagina = $this->episodeManager->pagineEpisodes( 0, (int) $episodesTot[0]);
            $token = $this->noCsrf->createToken();
            $totalpages = (int) $episodesTot[0];
            $currentpage = array_search($episode[0]->post_id, array_column($pagina, 'post_id')) + 1;
            $this->view->render('front/episode', 'front/layout', compact('pagina', 'pseudo', 'comment', 'episodesTot', 'currentpage', 'totalpages', 'episode', 'error', 'token'));
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

    public function connectionPage():void//méthode pour afficher la page de connection
    {
        $token = $this->noCsrf->createToken();
        $this->view->render('front/connection', 'front/layout', compact('token'));
    }
}

