<?php

class LoginController extends BaseSpaceTwigController
{
    public $template = "login.twig";

    public function get(array $context)
    {
        parent::get($context);
    }

    public function post(array $context)
    {
        // Обработка данных формы при отправке (POST запрос)

        // Берем значения, введенные пользователем
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Получаем данные пользователя из базы данных
        $query = $this->pdo->prepare("SELECT * FROM user WHERE username = :username");
        $query->bindValue(":username", $username);
        $query->execute();
        $user_data = $query->fetch();

        //Проверяем, существует ли пользователь и совпадает ли введенный пароль с хранимым в базе данных
        if (!$user_data || $password != $user_data['password']) {
            // Если пароль не совпадает, перенаправляем пользователя на страницу входа
            error_log("Incorrect password");
            error_log("Passwordbd:" . $user_data['password']);
            error_log("Password:" . $password);
            error_log("User:" . $user_data['username']);
            header('Location: /login');
            exit;
        }

        // Если пароль совпадает, отмечаем пользователя как залогиненного и перенаправляем на главную страницу
        error_log("Correct password");
        $_SESSION["is_logged"] = true;
        header('Location: /');
        exit;
    }
}
