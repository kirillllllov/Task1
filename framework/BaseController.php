<?php
abstract class BaseController
{
    public PDO $pdo;
    public array $params;

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function setPDO(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getContext(): array
    {
        return [];
    }

    public function process_response()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_set_cookie_params(60*60*10);
            session_start();
            
            // Инициализируем messages как массив, если его нет
            if (!isset($_SESSION['messages'])) {
                $_SESSION['messages'] = [];
            }
        }
        
        // Проверяем инициализацию перед добавлением
        if (!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = [];
        }
        
        // Добавляем URL в messages
        $_SESSION['messages'][] = $_SERVER['REQUEST_URI'];
        
        $method = $_SERVER['REQUEST_METHOD'];
        $context = $this->getContext();
        
        if ($method == 'GET') {
            $this->get($context);
        } else if ($method == 'POST') {
            $this->post($context);
        }
    }

    public function get(array $context) {}
    public function post(array $context) {}
}
