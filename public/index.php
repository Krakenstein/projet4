<?php
require('../vendor/autoload.php');

use Projet4\Controller\frontController;
use Projet4\Controller\backController;
use Projet4\Tools\Request;

session_start();

$request = new Request();

$actionFront = 
[
    'listEpisodes',
    'connectionPage',
    'episodePage',
    'newCom',
    'report',
    'previous',
    'next'
];

$actionBack =
[
    'admConnect',
    'episodes',
    'createEpisode',
    'reset',
    'profil',
    'commentDelete',
    'comDelete',
    'deleteR',
    'deleteReportsFromEp',
    'comPage',
    'modifyEpisode',
    'addEpisode',
    'episodeModications',
    'disconnection'
];

if (($request->get('action')) !== null){
    $key = array_search($request->get('action'), $actionFront);
    $methode = $actionFront[$key]; 
    if ($methode === $request->get('action')){
        $controller = new FrontController();
        $controller->$methode();
        exit();
    }
    $key = array_search($request->get('action'), $actionBack);
    $methode = $actionBack[$key]; 
    if ($methode === $request->get('action')){
        $controller = new BackController();
        $controller->$methode();
        exit();
    }
}

$controller = new FrontController();
$controller->homePage();

    