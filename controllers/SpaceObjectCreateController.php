<?php
require_once "BaseSpaceTwigController.php";

class SpaceObjectCreateController extends BaseSpaceTwigController
{
  public $template = "space_object_create.twig";

  public function get(array $context)
  {
    parent::get($context);
  }

  public function post(array $context)
  {
    // получаем значения полей с формы
    $title = $_POST['title'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $info = $_POST['info'];

    // вытащил значения из $_FILES
    $tmp_name = $_FILES['image']['tmp_name'];
    $file_name = $_FILES['image']['name'];
    
    // проверяем, что файл был загружен
    if ($tmp_name && $file_name) {
        move_uploaded_file($tmp_name, "../public/media/$file_name");
        $image_url = "/media/$file_name"; // формируем ссылку без адреса сервера
    } else {
        $image_url = ""; // или установите значение по умолчанию
    }

    // создаем текст запрос
    $sql = <<<EOL
INSERT INTO brz_cars(title, description, type, info, image)
VALUES(:title, :description, :type, :info, :image_url)
EOL;

    // подготавливаем запрос к БД
    $query = $this->pdo->prepare($sql);
    // привязываем параметры
    $query->bindValue("title", $title);
    $query->bindValue("description", $description);
    $query->bindValue("type", $type);
    $query->bindValue("info", $info);
    $query->bindValue("image_url", $image_url);

    // выполняем запрос
    $query->execute();

    $context['message'] = 'Вы успешно создали объект';
    $context['id'] = $this->pdo->lastInsertId();

    $this->get($context);
  }
}