<?php
require_once "BaseBrzTwigController.php";

class SearchController extends BaseBrzTwigController
{
    public $template = "search.twig";

    public function getContext(): array
    {
        $context = parent::getContext();

        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $title = isset($_GET['title']) ? $_GET['title'] : '';
        $info = isset($_GET['info']) ? $_GET['info'] : '';

        if ($type == "все") {
            $sql = <<<EOL
                SELECT id, title
                FROM brz_cars
                WHERE (:title= '' OR title like CONCAT('%', :title, '%')) AND (:info= '' OR info like CONCAT('%', :info, '%'))
                EOL;
        } else {
            $sql = <<<EOL
                SELECT brz_cars.id, brz_cars.title
                FROM brz_cars
                INNER JOIN brz_cars_types ON brz_cars.type = brz_cars_types.name
                WHERE (:title= '' OR brz_cars.title like CONCAT('%', :title, '%')) 
                AND (:info= '' OR brz_cars.info like CONCAT('%', :info, '%'))
                AND brz_cars_types.id = :type_id
                EOL;
        }

        $query = $this->pdo->prepare($sql);

        $query->bindValue("title", $title);
        $query->bindValue("type_id", $type);
        $query->bindValue("info", $info);
        $query->execute();

        $context['objects'] = $query->fetchAll();

        // echo "<pre>";
        // print_r($context['objects']);
        // echo "</pre>";

        return $context;
    }
}
