<?php

class logoutController extends BaseSpaceTwigController
{

    public function post(array $context)
    {
        // Обработка данных формы при отправке (POST запрос)
        $_SESSION["is_logged"] = false;
        header('Location: /login');
        
    }
}
