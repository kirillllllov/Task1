<?php

class BrzObjectUpdateController extends BaseBrzTwigController
{
    public $template = "brz_object_update.twig";
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

        if ($_FILES['image']['size'] > 0) {
            $tmp_name = $_FILES['image']['tmp_name'];
            $title = $_FILES['image']['title'];
            move_uploaded_file($tmp_name, "../public/media/$title");
            $image_url = "/media/$title";
        } else {
            $image_url = $data['image'];
        }

        $sql = "UPDATE brz_cars SET title = :title, description = :description, type = :type, info = :info, image = :image_url WHERE id = :id";

        $query = $this->pdo->prepare($sql);
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("type", $type);
        $query->bindValue("info", $info);
        $query->bindValue("image_url", $image_url);
        $query->bindValue("id", $id);
        $query->execute();

        $context['message'] = 'Вы успешно изменили объект';

        $this->get($context);
    }
}
