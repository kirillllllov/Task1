<?php
require_once "BaseBrzTwigController.php";

class BrzObjectCreateController extends BaseBrzTwigController
{
  public $template = "brz_object_create.twig";

  public function get(array $context)
  {
    parent::get($context);
  }

  public function post(array $context)
  {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $type_id = $_POST['type'];
    $info = $_POST['info'];

    // Get type name from ID
    $type_query = $this->pdo->prepare("SELECT name FROM brz_cars_types WHERE id = :type_id");
    $type_query->bindValue("type_id", $type_id);
    $type_query->execute();
    $type_data = $type_query->fetch();
    $type = $type_data['name'];

    $tmp_name = $_FILES['image']['tmp_name'];
    $file_name = $_FILES['image']['name'];
    
    if ($tmp_name && $file_name) {
        move_uploaded_file($tmp_name, "../public/media/$file_name");
        $image_url = "/media/$file_name"; 
    } else {
        $image_url = "";
    }

    $sql = <<<EOL
INSERT INTO brz_cars(title, description, type, info, image)
VALUES(:title, :description, :type, :info, :image_url)
EOL;

    $query = $this->pdo->prepare($sql);
    
    $query->bindValue("title", $title);
    $query->bindValue("description", $description);
    $query->bindValue("type", $type);
    $query->bindValue("info", $info);
    $query->bindValue("image_url", $image_url);

    $query->execute();

    $context['message'] = 'Вы успешно создали объект';
    $context['id'] = $this->pdo->lastInsertId();

    $this->get($context);
  }
}