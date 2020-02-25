<?php
declare(strict_types=1);

namespace Projet4\Tools;

class Session
{
    private $_session;
   

    public function sessionVerify()
    {                   
        if (empty($_SESSION['admConnected'])){
            header('Location: index.php');
            exit();
        }
    }

    public function setSessionData( $session_name , $data ){
        $_SESSION[$session_name] = $data;
    }
    
    public function getSessionData( $session_name ){
        return $_SESSION[$session_name];
    }
}