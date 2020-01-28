<?php

require_once('src/model/episodeManager.php');
require_once('src/model/commentManager.php');


function listEpisodes()//fonction pour récupérer la liste des épisodes publiés
{
    $episodeManager = new episodeManager();
    $episodes = $episodeManager->getEpisodes();

    if ($episodes === false) {
        require('src/view/front/episodesBlankView.php');
    }
    else {
    require('src/view/front/episodesView.php');
    }
}

function episode()//fonction pour récupérer un épisode publié en fonction de son numéro de chapitre
{
    $episodeManager = new episodeManager();
    $commentManager = new commentManager();

    $episode = $episodeManager->getPostedEpisode($_GET['nb']);
    $comments = $commentManager->getComments($_GET['nb']);

    if ($episode === false) {
        require('src/view/front/episodeBlankView.php');
    }
    else {
    require('src/view/front/episodeView.php');
    }
}

function addComment($episodeNumber, $author, $comment)//fonction pour rajouter un commentaire à un épisode donné en fonction de son numéro de chapitre
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

function countCom($chapterNumber)//fonction pour compter le nbre de commentaires d'un épisode
{
    $commentManager = new commentManager();
    $numberComments = $commentManager->countComments($chapterNumber);
}

function reportComment($id)//fonction pour signalé un commentaire
{
    $commentManager = new commentManager();
    $episodeManager = new episodeManager();

    $episode = $episodeManager->getEpisode($_GET['nb']);
    $comments = $commentManager->getComments($_GET['nb']);
    $numberComments = $commentManager->reports($id);
    $nbReports = $commentManager->countReports($_GET['chpt']);

    require('view/front/episodeView.php');
}

function homePage()//fonction pour démarrer une session lorsque on affiche la page d'accueil et récupérer le dernier épisode posté
{
    session_start();
    $episodeManager = new episodeManager();

    $lastEpisode = $episodeManager->getLastEpisode();

    if ($lastEpisode === false) {
        require('src/view/front/homePageBlankView.php');
    }
    else {

    require('src/view/front/homePageView.php');
    }
}

function connectionPage()//fonction pour afficher la page de connection
{
    require('src/view/front/connectionView.php');
}


