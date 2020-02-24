<?php
declare(strict_types=1);

namespace Projet4\Tools;

use Projet4\View\View;

class Session
{
    
    private $view;
    private $_session;
   
    public function __construct()
    {
        $this->view = new View();
        $this->_session = $_SESSION;
    }
    
    public function sessionVerify()
    {                   
        if (($this->session('admConnected') === null)){
            $error = 'Vous devez vous connecter';
            $this->view->render('front/connection', 'front/layout', compact('error'));
            exit();
        }
    }

    public function session($key = null, $default = null)
    {
        return $this->checkGlobal($this->_session, $key, $default);
    }

    private function checkGlobal($global, $key = null, $default = null)
    {
        if ($key) {
        if (isset($global[$key])) {
            return $global[$key];
        } else {
            return $default ?: null;
        }
        }
        return $global;
    }
}