<?php
require_once "BaseController.php"; // обязательно импортим BaseController

class TwigBaseController extends BaseController {
    public $title = ""; // название страницы
    public $template = ""; // шаблон страницы
    protected \Twig\Environment $twig; // ссылка на экземпляр twig, для рендернига

    public $_url = "";
    public $test = "";
    
    public $image_path = "";
    public $info_path = "";

    public $objects;

    public $types_bd;

    // добавляем
    public function setTwig($twig) {
        $this->twig = $twig;
    }
    
    // переопределяем функцию контекста
    public function getContext() : array
    {
        $context = parent::getContext(); // вызываем родительский метод
        $context['title'] = $this->title; // добавляем title в контекст

        $context['image_path'] = $this->image_path;
        $context['info_path'] = $this->info_path;
        $context['objects'] = $this->objects;
        $context['types_bd'] = $this->types_bd;
        $context["messages"] = isset($_SESSION['messages']) ? $_SESSION['messages'] : "";

        return $context;
    }
    
    public function get(array $context) { // добавил аргумент в get
        echo $this->twig->render($this->template, $context); // а тут поменяем getContext на просто $context
    }
}