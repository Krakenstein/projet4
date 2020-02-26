<?php
declare(strict_types=1);

namespace Projet4\Tools;


class NoCsrf 
{
    public function createToken(): ?string
    {
        $_SESSION["token"] = hash('sha256', strval(random_int ( 100 , 10000 )));
        return($_SESSION["token"]);
    }
}