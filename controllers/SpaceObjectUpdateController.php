<?php
//require_once "BaseSpaceTwigController.php";
class SpaceObjectUpdateController extends BaseSpaceTwigController
{
    public $template = "space_object_update.twig";
    public function get(array $context)
    {
        $id = $this->params['id'];
        $sql = <<<EOL
        SELECT * FROM brz_cars WHERE id = :id
        EOL;
        $query = $this->pdo->prepare($sql);
        $query->bindValue("id", $id);
        $query->execute();
        $data = $query->fetch();
        $context['object'] = $data;

        parent::get($context);
    }
    public function post(array $context)
    {
        $id = $this->params['id'];
        $sql = <<<EOL
        SELECT * FROM brz_cars WHERE id = :id
        EOL;
        $query = $this->pdo->prepare($sql);
        $query->bindValue("id", $id);
        $query->execute();

        $data = $query->fetch();
        $context['object'] = $data;
        $context['id'] = $id;

        echo "<pre>";
        print_r($id);
        echo "</pre>";

        // Получаем значения полей с формы
        $title = $_POST['title'];
        $description = $_POST['description'];
        $type = $_POST['type'];
        $info = $_POST['info'];

        // Проверяем, было ли загружено новое изображение
        if ($_FILES['image']['size'] > 0) {
            // Если загружено новое изображение, обрабатываем его
            $tmp_name = $_FILES['image']['tmp_name'];
            $title = $_FILES['image']['title'];
            move_uploaded_file($tmp_name, "../public/media/$title");
            $image_url = "/media/$title"; // Формируем ссылку без адреса сервера
        } else {
            // Если изображение не загружено, используем существующий URL изображения
            $image_url = $data['image']; // Предполагается, что в таблице space_objects есть столбец 'image' с URL текущего изображения
        }

        // // SQL запрос на обновление данных
        $sql = "UPDATE brz_cars SET title = :title, description = :description, type = :type, info = :info, image = :image_url WHERE id = :id";

        $query = $this->pdo->prepare($sql);
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("type", $type);
        $query->bindValue("info", $info);
        $query->bindValue("image_url", $image_url);
        $query->bindValue("id", $id);
        $query->execute();

        // Вывод сообщения о успехе
        $context['message'] = 'Вы успешно изменили объект';

        // Передаем обновленный контекст в метод render для отображения шаблона
        $this->get($context);
    }
}
