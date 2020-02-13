<?php
require('vendor/autoload.php');

use Projet4\Controller\frontController;
use Projet4\Controller\backController;
use Projet4\Tools\Request;

$actions = 
[
    0 =>'listEpisodes',
    1 =>'connection',
    2 =>'episodePage',
    3 =>'addComment',
    4 =>'report',
    5 =>'admConnect',
    6 =>'episodes',
    7 =>'createEpisode',
    8 =>'resetAdmin',
    9 =>'profil',
    10 =>'deleteComEpisode',
    11 =>'deleteCom',
    12 =>'deleteRep',
    13 =>'commentsPage',
    14 =>'modifyEpisode',
    15 =>'addEpisode',
    16 =>'modifiedEpisode',
    17 =>'disconnection'
];

try {
    if (isset($_GET['action'])) {
        $key = array_search($_GET['action'], $actions); 
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
    }
    else {//si aucune action stipulée on affiche l'accueil du front
        $controller = new frontController();
        $controller->homePage();
    }
}
catch(Exception $e) { 
    echo 'Erreur : ' . $e->getMessage();
}