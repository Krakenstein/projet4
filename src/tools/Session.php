<?php
declare(strict_types=1);

namespace Projet4\Tools;

use Projet4\View\View;

class Session
{
    
    private $view;
   
    public function __construct()
    {
        $this->view = new View();
    }
    
    public function sessionVerify()
    {
                    
        if (!isset($_SESSION['admConnected'])){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }
    }
}