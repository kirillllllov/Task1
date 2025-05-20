<?php
require_once "BaseSpaceTwigController.php";

class SearchController extends BaseSpaceTwigController
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
                SELECT id, title
                FROM brz_cars
                WHERE (:title= '' OR title like CONCAT('%', :title, '%')) AND (:info= '' OR info like CONCAT('%', :info, '%'))
                    AND (type = :type)
                EOL;
        }

        $query = $this->pdo->prepare($sql);

        $query->bindValue("title", $title);
        $query->bindValue("type", $type);
        $query->bindValue("info", $info);
        $query->execute();

        $context['objects'] = $query->fetchAll();

        // echo "<pre>";
        // print_r($context['objects']);
        // echo "</pre>";

        return $context;
    }
}
