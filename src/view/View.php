<?php
declare(strict_types=1);

namespace projet4\view ;

class View{

    protected $viewPath = 'templates/';

    public function render($view, $template, $variables = []){ // méthode pour afficher le rendu des views
        ob_start();
        extract($variables);
        require($this->viewPath . $view . '.php');
        $content = ob_get_clean();
        require($this->viewPath . 'templates/' . $template . '.php');


    }

}