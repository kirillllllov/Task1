<?php
require_once "BaseSpaceTwigController.php";

class ObjectController extends BaseSpaceTwigController
{
    public $template = "object.twig"; // указываем шаблон
    public $image_path = "";
    public $info_path = "";

    public function getContext(): array
    {
        $context = parent::getContext();

        // готовим запрос к БД, тут уже указываю конкретные поля, там более грамотно
        // создам запрос, под параметр создаем переменную my_id в запросе
        $query = $this->pdo->prepare("SELECT image, info, title, description, id FROM brz_cars WHERE id= :my_id");
        // подвязываем значение в my_id 
        $query->bindValue("my_id", $this->params['id']);

        // Получаем параметр запроса 'show'
        $showType = $_GET['show'] ?? 'default';

        $query->execute(); // выполняем запрос

        switch ($showType) {
            case 'image':
                $data = $query->fetch();
                $image_path = "";
                $info_path = "/brz_cars/" . $data['id'] . "?show=info";

                $context['title'] = $data['title'];
                $context['description'] = $data['description'];

                $context['image'] = $data['image'];
                $context['_url'] = $image_path;
                $context['image_path'] = $image_path;
                $context['info_path'] = $info_path;

                $context['showType'] = "image";

                break;
            case 'info':
                $data = $query->fetch();

                $image_path = $info_path = "/brz_cars/" . $data['id'] . "?show=image";
                $info_path = "";

                // передаем описание из БД в контекст
                $context['title'] = $data['title'];
                $context['description'] = $data['description'];
                
                $context['info'] = $data['info'];
                $context['_url'] = $info_path;
                $context['info_path'] = $info_path;
                $context['image_path'] = $image_path;

                $context['showType'] = "info";
                break;
            default:
                // тянем данные
                $data = $query->fetch();

                $image_path = $data['id'] . "?show=image";
                $info_path = $data['id'] . "?show=info";

                // передаем описание из БД в контекст
                $context['description'] = $data['description'];
                $context['title'] = $data['title'];

                $context['image_path'] = $image_path;
                $context['info_path'] = $info_path;
                $context['showType'] = "";
                break;
        }
        $context["messages"] = isset($_SESSION['messages']) ? $_SESSION['messages'] : "";
        return $context;
    }
}
