<?php

require_once('src/model/episodeManager.php');
require_once('src/model/commentManager.php');
require_once('src/controller/controller.php');

class FrontController extends Controller{
        
    public function listEpisodes()//méthode pour récupérer la liste des épisodes publiés
    {
        $episodeManager = new episodeManager();
        $episodes = $episodeManager->getEpisodes();

        if ($episodes === false) {
            require('src/view/front/episodesBlankView.php');
        }
        else {
            $this->render('front/episodesView', 'frontend/templateFront', compact('episodes'));
        }
    }

    public function episode()//méthode pour récupérer un épisode publié en fonction de son numéro de chapitre
    {
        $episodeManager = new episodeManager();
        $commentManager = new commentManager();

        $episode = $episodeManager->getPostedEpisode($_GET['nb']);
        $comments = $commentManager->getComments($_GET['nb']);

        if ($episode === false) {
            $this->render('front/episodeBlankView', 'frontend/templateFront');
        }
        else {
            if (isset($_GET['nb']) && $_GET['nb'] > 0) {
                $this->render('front/episodeView', 'frontend/templateFront', compact('episode', 'comments'));
            }
            else {
                throw new Exception('Aucun numéro dépisode envoyé');
            }
        }
    }

    public function newCom()
    {
        if (isset($_GET['nb']) && $_GET['nb'] > 0) {
            if (!empty($_POST['author']) && !empty($_POST['comment'])) {
                $this->addComment($_GET['nb'], $_POST['author'], $_POST['comment']);
                $this->countCom($_GET['nb']);
            }
            else {
                throw new Exception('tous les champs ne sont pas remplis !');
            }
        }
        else {
            throw new Exception('Erreur : aucun identifiant de billet envoyé');
        }
    }

    public function addComment($episodeNumber, $author, $comment)//méthode pour rajouter un commentaire à un épisode donné en fonction de son numéro de chapitre
    {
        $commentManager = new commentManager();
        
        $affectedLines = $commentManager->postComment($episodeNumber, $author, $comment);

        if ($affectedLines === false) {
            throw new Exception('Impossible d\'ajouter le commentaire !');
        }
        else {
            header('Location: index.php?action=episode&nb=' . $episodeNumber);
        }
    }

    public function countCom($chapterNumber)//méthode pour compter le nbre de commentaires d'un épisode
    {
        $commentManager = new commentManager();
        $numberComments = $commentManager->countComments($chapterNumber);
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

    public function reportComment($id)//méthode pour signalé un commentaire
    {
        $commentManager = new commentManager();
        $episodeManager = new episodeManager();

        $episode = $episodeManager->getEpisode($_GET['nb']);
        $comments = $commentManager->getComments($_GET['nb']);
        $numberComments = $commentManager->reports($id);
        $nbReports = $commentManager->countReports($_GET['chpt']);

        $this->render('front/episodeView', 'frontend/templateFront', compact('episode', 'comments', 'numberComments', 'nbReports'));
    }

    public function homePage()//méthode pour démarrer une session lorsque on affiche la page d'accueil et récupérer le dernier épisode posté
    {
        session_start();
        $episodeManager = new episodeManager();

        $lastEpisode = $episodeManager->getLastEpisode();

        if ($lastEpisode === false) {
            $this->render('front/homePageBlankView', 'frontend/templateFront');
        }
        else {

        $this->render('front/homePageView', 'frontend/templateFront', compact('lastEpisode'));
        }
    }

    public function connectionPage()//méthode pour afficher la page de connection
    {
        $this->render('front/connectionView', 'frontend/templateFrontAdmin');
    }
}

