<?php
require_once "BaseBrzTwigController.php";

class MainController extends BaseBrzTwigController
{
    public $template = "main.twig";
    public $title = "Главная";

    
    public function getContext(): array
    {
        $context = parent::getContext();

        if (isset($_GET['type'])) {
            $query = $this->pdo->prepare("SELECT brz_cars.* FROM brz_cars 
                                         INNER JOIN brz_cars_types ON brz_cars.type = brz_cars_types.name 
                                         WHERE brz_cars_types.id = :type_id");
            $query->bindValue("type_id", $_GET['type']);
            $query->execute();
        } else {
            $query = $this->pdo->query("SELECT * FROM brz_cars");
        }

        $context['brz_cars'] = $query->fetchAll();

        return $context;
    }
}
