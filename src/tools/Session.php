<?php
declare(strict_types=1);

namespace Projet4\Tools;

class Session
{
    private $_session;
   

    public function sessionVerify():void
    {                   
        if (empty($_SESSION['admConnected'])){
            header('Location: index.php');
            exit();
        }
    }

    public function setSessionData(string $session_name ,  ?string $data ):void
    {
        $_SESSION[$session_name] = $data;
    }
    
    public function getSessionData(string $session_name ): ?string
    {
        return $_SESSION[$session_name];
    }
}