<?php

class Controller{

    protected $viewPath = 'src/view/';

    public function render($view, $template, $variables = []){
        ob_start();
        extract($variables);
        require($this->viewPath . $view . '.php');
        $content = ob_get_clean();
        require($this->viewPath . 'templates/' . $template . '.php');


    }

}