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
    'previousNext',
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




/*if (isset($request->get('action'))) {
    $key = array_search($request->get('action'), $actions); 
    if ($key < 5){
                $controller = new FrontController();
                switch ($key) {
                    case 0:
                        $controller->listEpisodes();
                        break;
                    case 1:
                        $controller->connectionPage();  
                        break;
                    case 2:
                        $controller->episodePage();
                        break;
                    case 3:
                        $controller->newCom();
                        break;
                    case 4:
                        $controller->report();
                        break;        
                }        

    }elseif ($key > 4){    
                $controller = new BackController();
                switch ($key) {
                    case 5:
                        $controller->admConnect();
                        break;
                    case 6:
                        $controller->episodes();  
                        break;
                    case 7:
                        $controller->createEpisode();
                        break;
                    case 8:
                        $controller->reset();
                        break;
                    case 9:
                        $controller->profil();
                        break;
                    case 10:
                        $controller->commentDelete();
                        break;
                    case 11:
                        $controller->comDelete(); 
                        break;
                    case 12:
                        $controller->deleteR();
                        break;
                    case 13:
                        $controller->comPage();
                        break;
                    case 14:
                        $controller->modifyEpisode();
                        break;
                    case 15:
                        $controller->addEpisode();
                        break;
                    case 16:
                        $controller->episodeModications();
                        break;
                    case 17:
                        $controller->disconnection();
                        break;  
                }
        }    
}else {//si aucune action stipulée on affiche l'accueil du front
    $controller = new frontController();
    $actions = 'homePage';
    //$controller->homePage();
    $controller->$actions();
    }*/

    