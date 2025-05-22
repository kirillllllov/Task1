<?php
require_once "BaseController.php";

class TwigBaseController extends BaseController {
    public $title = ""; 
    public $template = ""; 
    protected \Twig\Environment $twig;

    public $_url = "";
    public $test = "";
    
    public $image_path = "";
    public $info_path = "";

    public $objects;

    public $types_bd;

    
    public function setTwig($twig) {
        $this->twig = $twig;
    }
    
    
    public function getContext() : array
    {
        $context = parent::getContext();
        $context['title'] = $this->title; 

        $context['image_path'] = $this->image_path;
        $context['info_path'] = $this->info_path;
        $context['objects'] = $this->objects;
        $context['types_bd'] = $this->types_bd;
        $context["messages"] = isset($_SESSION['messages']) ? $_SESSION['messages'] : "";

        return $context;
    }
    
    public function get(array $context) { 
        echo $this->twig->render($this->template, $context); 
    }
}