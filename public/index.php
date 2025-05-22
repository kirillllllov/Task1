<?php
require_once '../vendor/autoload.php';
require_once "../framework/autoload.php";
require_once '../controllers/MainController.php';
require_once "../controllers/ObjectController.php";
require_once "../controllers/SearchController.php";
require_once "../controllers/BrzObjectCreateController.php";
require_once "../controllers/CreateTypeController.php";
require_once "../controllers/BrzObjectDeleteController.php";
require_once "../controllers/BrzObjectUpdateController.php";
require_once "../controllers/SetWelcomeController.php";
require_once "../controllers/LoginController.php";
require_once "../controllers/logoutController.php";
require_once "../RestAPI/BrzRestController.php";
require_once "../controllers/Controller404.php";
require_once "../middlewares/LoginRequiredMiddleware.php";


    $url = $_SERVER['REQUEST_URI'];

    $matches = [];
    if(preg_match('#^/api/brz(/\d+)?/?#', $url, $matches)) {
        $id = substr($matches[1] ?? "", 1);
        //print_r($matches);
        $controller = new BrzRestController;
        $controller->process($id);
    }



$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader, [
    "debug" => true 
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());


$pdo = new PDO("mysql:host=localhost;dbname=car_brz;charset=utf8", "root", "");

$router = new Router($twig, $pdo);

$router->add("/login", LoginController::class); 
$router->add("/logout", logoutController::class); 
$router->add("/", MainController::class);
$router->add("/set-welcome/", SetWelcomeController::class);
$router->add("/search", SearchController::class);
$router->add("/create", BrzObjectCreateController::class)-> middleware(new LoginRequiredMiddleware);
$router->add("/create_type", CreateTypeController::class)-> middleware(new LoginRequiredMiddleware);
$router->add("/brz_cars/(?P<id>\d+)/delete", BrzObjectDeleteController::class)-> middleware(new LoginRequiredMiddleware);
$router->add("/brz_cars/(?P<id>\d+)/edit", BrzObjectUpdateController::class)-> middleware(new LoginRequiredMiddleware);
$router->add("/brz_cars/(?P<id>\d+)", ObjectController::class);


$router->get_or_default(Controller404::class);
