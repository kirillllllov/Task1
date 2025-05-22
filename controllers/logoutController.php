<?php

class logoutController extends BaseBrzTwigController
{

    public function post(array $context)
    {
        
        $_SESSION["is_logged"] = false;
        header('Location: /login');
        
    }
}
