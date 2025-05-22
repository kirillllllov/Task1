<?php
require_once "BaseBrzTwigController.php";

class CreateTypeController extends BaseBrzTwigController
{
  public $template = "create_type.twig";

  public function get(array $context)
  {
    // echo $_SERVER['REQUEST_METHOD'];

    parent::get($context); 
  }

  public function post(array $context)
  {
    
    $name_type = $_POST['name_type'];

    
    $tmp_name = $_FILES['image']['tmp_name'];
    $name =  $_FILES['image']['name'];
    move_uploaded_file($tmp_name, "../public/media/$name");
    $image_url = "/media/$name"; 

    $sql = <<<EOL
INSERT INTO brz_cars_types(name, image)
VALUES(:name, :image_url)
EOL;

    $query = $this->pdo->prepare($sql);
    $query->bindValue("name", $name_type);
    $query->bindValue("image_url", $image_url);
    $query->execute();

    
    $context['message'] = 'Вы успешно создали новый тип';
    $context['id'] = $this->pdo->lastInsertId();

    $this->get($context);
  }
}
