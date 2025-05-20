<?php
require_once "BaseSpaceTwigController.php";

class CreateTypeController extends BaseSpaceTwigController
{
  public $template = "create_type.twig";

  public function get(array $context) // добавили параметр
  {
    // echo $_SERVER['REQUEST_METHOD'];

    parent::get($context); // пробросили параметр
  }

  public function post(array $context)
  {
    // получаем значения полей с формы
    $name_type = $_POST['name_type'];

    // вытащил значения из $_FILES
    $tmp_name = $_FILES['image']['tmp_name'];
    $name =  $_FILES['image']['name'];
    move_uploaded_file($tmp_name, "../public/media/$name");
    $image_url = "/media/$name"; // формируем ссылку без адреса сервера

    $sql = <<<EOL
INSERT INTO brz_cars_types(name, image)
VALUES(:name, :image_url)
EOL;

    $query = $this->pdo->prepare($sql);
    $query->bindValue("name", $name_type);
    $query->bindValue("image_url", $image_url); // подвязываем значение ссылки к переменной  image_url
    $query->execute();

    // а дальше как обычно
    $context['message'] = 'Вы успешно создали новый тип';
    $context['id'] = $this->pdo->lastInsertId();

    $this->get($context);
  }
}
