<?php
require_once "BaseSpaceTwigController.php";

class MainController extends BaseSpaceTwigController
{
    public $template = "main.twig";
    public $title = "Главная";

    // добавим метод getContext()
    public function getContext(): array
    {
        $context = parent::getContext();

        if (isset($_GET['type'])) {
            $query = $this->pdo->prepare("SELECT * FROM brz_cars WHERE type = :type");
            $query->bindValue("type", $_GET['type']);
            $query->execute();
        } else {
            // подготавливаем запрос SELECT * FROM space_objects
            // вообще звездочку не рекомендуется использовать, но на первый раз пойдет
            $query = $this->pdo->query("SELECT * FROM brz_cars");
        }

        // стягиваем данные через fetchAll() и сохраняем результат в контекст
        $context['brz_cars'] = $query->fetchAll();

        return $context;
    }
}
