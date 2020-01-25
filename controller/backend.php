<?php

require_once('model/episodeManager.php');
require_once('model/commentManager.php');

function admConnect()//fonction pour se connecter au back
{
    $_SESSION['admConnected'] = true;

    $episodeManager = new episodeManager();
    $commentManager = new commentManager();

    $allComs = $commentManager->getAllComments();
    $allEpisodes = $episodeManager->getAllEpisodes();
    
    require('view/back/homePageBackView.php');
}

function createEpisode()//fonction pour afficher la page de création d'épisode
{
    require('view/back/createEpisodeView.php');
}

function addPostedEpisode($episodeNumber, $title, $content)//fonction pour ajouter un épisode publié à la bdd
{
    $episodeManager = new episodeManager();
    $postedEpisode = $episodeManager->postEpisode($episodeNumber, $title, $content);

}

function addSavedEpisode($episodeNumber, $title, $content)//fonction pour ajouter un épisode archivé à la bdd
{
    $episodeManager = new episodeManager();
    $postedEpisode = $episodeManager->saveEpisode($episodeNumber, $title, $content);

}

function modifyPostedEpisode($nvchapter, $nvtitle, $nvcontent)//fonction pour modifier un épisode
{
    $episodeManager = new episodeManager();
    $postedModifiedEpisode = $episodeManager->postModifiedEpisode($nvchapter, $nvtitle, $nvcontent);

}

function modifySavedEpisode($nvchapter, $nvtitle, $nvcontent)//fonction pour modifier un épisode
{
    $episodeManager = new episodeManager();
    $savedModifiedEpisode = $episodeManager->saveModifiedEpisode($nvchapter, $nvtitle, $nvcontent);

}

function modifyEpisode()
{
    $episodeManager = new episodeManager();
    $commentManager = new commentManager();

    $episode = $episodeManager->getEpisode($_GET['nb']);
    $comments = $commentManager->getReportedComments($_GET['nb']);
    
    require('view/back/episodeBackView.php');
}

function episodeDelete()//fonction pour supprimer un épisode
{
    $episodeManager = new episodeManager();
    $commentManager = new commentManager();

    $episodeManager->deleteEpisode($_GET['nb']);
    $commentManager->deleteComments($_GET['nb']);

}

function commentDelete()//fonction pour supprimer un commentaire
{
    $episodeManager = new episodeManager();
    $commentManager = new commentManager();

    $commentManager->deleteComment($_GET['id']);
    $episode = $episodeManager->getEpisode($_GET['nb']);
    $comments = $commentManager->getReportedComments($_GET['nb']);
    $subComments = $commentManager->substractComments($_GET['chpt']);

    require('view/back/episodeBackView.php');

}

function profil()//fonction pour se déconnecter du back
{
    require('view/back/profilView.php');
}

function disconnection()//fonction pour se déconnecter du back
{
    $_SESSION['admConnected'] = false;
    session_destroy();
}