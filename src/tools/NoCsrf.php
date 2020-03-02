<?php
declare(strict_types=1);

namespace Projet4\Tools;

use Projet4\Tools\Session;
use Projet4\Tools\Request;

class NoCsrf 
{
    private $session;
    private $request;
   
    public function __construct()
    {
        $this->session = new Session();
        $this->request = new Request();
    }
     
    public function createToken(): ?string
    {
        $this->session->setSessionData("token", hash('sha256', strval(bin2hex(random_bytes(64)))));
        return($this->session->getSessionData("token"));
    }

    public function isTokenValid(): ?string
    {
        return($this->session->getSessionData("token"));
    }
}