<?php

class LoginController extends BaseBrzTwigController
{
    public $template = "login.twig";

    public function get(array $context)
    {
        parent::get($context);
    }

    public function post(array $context)
    {
        
        $username = $_POST['username'];
        $password = $_POST['password'];

        
        $query = $this->pdo->prepare("SELECT * FROM user WHERE username = :username");
        $query->bindValue(":username", $username);
        $query->execute();
        $user_data = $query->fetch();

        
        if (!$user_data || $password != $user_data['password']) {
            
            error_log("Incorrect password");
            error_log("Passwordbd:" . $user_data['password']);
            error_log("Password:" . $password);
            error_log("User:" . $user_data['username']);
            header('Location: /login');
            exit;
        }

        
        error_log("Correct password");
        $_SESSION["is_logged"] = true;
        header('Location: /');
        exit;
    }
}
