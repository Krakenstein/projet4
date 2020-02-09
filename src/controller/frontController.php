<?php
declare(strict_types=1);

require_once('src/model/episodeManager.php');
require_once('src/model/commentManager.php');
require_once('src/view/View.php');

class FrontController{
        
    public function listEpisodes()//méthode pour récupérer la liste paginée des épisodes publiés
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
    
        
    public function episode()//méthode pour récupérer un épisode publié en fonction de son numéro de chapitre
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();

        $episode = $episodeManager->getPostedEpisode($_GET['id']);
        $comments = $commentManager->getComments($_GET['id']);
        $error = null;

        if ($episode === false) {
            $view->render('front/episodeBlankView', 'frontend/templateFront');
        }
        else {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $view->render('front/episodeView', 'frontend/templateFront', compact('episode', 'comments', 'error'));
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
    }

    public function previous()
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();
        $error = null;

        $episode = $episodeManager->previousEpisode($_GET['dt']);

        if ($episode === false) {
            $view->render('front/episodeBlankView', 'frontend/templateFront');
        }
        else {
            if (isset($_GET['dt']) && $_GET['dt'] > 0) {
                $comments = $commentManager->getComments($episode->post_id);
                $view->render('front/episodeView', 'frontend/templateFront', compact('episode', 'comments', 'error'));
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
    }

    public function next()
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();
        $view = new view();
        $error = null;

        $episode = $episodeManager->nextEpisode($_GET['dt']);

        if ($episode === false) {
            $view->render('front/episodeBlankView', 'frontend/templateFront');
        }
        else {
            if (isset($_GET['dt']) && $_GET['dt'] > 0) {
                $comments = $commentManager->getComments($episode->post_id);
                $view->render('front/episodeView', 'frontend/templateFront', compact('episode', 'comments', 'error'));
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
    }

    public function newCom()
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
                $error = 'tous les champs ne sont pas remplis !';
                $view->render('front/episodeView', 'frontend/templateFront', compact('episode', 'comments', 'error'));
            }
        }
        else {
            throw new Exception('Erreur : aucun identifiant de billet envoyé');
        }
    }

    public function addComment(string $post_id, string $episodeNumber, string $author, string $comment)//méthode pour rajouter un commentaire à un épisode donné en fonction de son numéro de chapitre
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

    public function countCom(string $post_id)//méthode pour compter le nbre de commentaires d'un épisode
    {
        $commentManager = new commentManager();
        $numberComments = $commentManager->countComments($post_id);
    }

    public function report()
    {
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            $this->reportComment($_GET['id']);
        }
        else {
            throw new Exception('Erreur : aucun identifiant de commentaire envoyé');
        }
    }

    public function reportComment(string $id)//méthode pour signalé un commentaire
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

