<?php
require_once "BaseBrzTwigController.php";

class ObjectController extends BaseBrzTwigController
{
    public $template = "object.twig";
    public $image_path = "";
    public $info_path = "";

    public function getContext(): array
    {
        $context = parent::getContext();

       
        $query = $this->pdo->prepare("SELECT image, info, title, description, id FROM brz_cars WHERE id= :my_id");
        
        $query->bindValue("my_id", $this->params['id']);

        
        $showType = $_GET['show'] ?? 'default';

        $query->execute();

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

                
                $context['title'] = $data['title'];
                $context['description'] = $data['description'];
                
                $context['info'] = $data['info'];
                $context['_url'] = $info_path;
                $context['info_path'] = $info_path;
                $context['image_path'] = $image_path;

                $context['showType'] = "info";
                break;
            default:
                
                $data = $query->fetch();

                $image_path = $data['id'] . "?show=image";
                $info_path = $data['id'] . "?show=info";

                
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
