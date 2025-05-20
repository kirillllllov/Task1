<?php
require_once '../vendor/autoload.php';
require_once "../framework/autoload.php";
require_once '../controllers/MainController.php'; // добавим в самом верху ссылку на наш контроллер
require_once "../controllers/ObjectController.php"; // добавил 
require_once "../controllers/SearchController.php";
require_once "../controllers/SpaceObjectCreateController.php";
require_once "../controllers/CreateTypeController.php";
require_once "../controllers/SpaceObjectDeleteController.php";
require_once "../controllers/SpaceObjectUpdateController.php";
require_once "../controllers/SetWelcomeController.php";
require_once "../controllers/LoginController.php";
require_once "../controllers/logoutController.php";

require_once "../controllers/Controller404.php";
require_once "../middlewares/LoginRequiredMiddleware.php";

$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader, [
    "debug" => true // добавляем тут debug режим
]);
$twig->addExtension(new \Twig\Extension\DebugExtension()); // и активируем расширение

// создаем экземпляр класса и передаем в него параметры подключения
// создание класса автоматом открывает соединение
$pdo = new PDO("mysql:host=localhost;dbname=car_brz;charset=utf8", "root", "");

$router = new Router($twig, $pdo);

$router->add("/login", LoginController::class); 
$router->add("/logout", logoutController::class); 
$router->add("/", MainController::class);
$router->add("/set-welcome/", SetWelcomeController::class);
// помните нашу регулярку, которую выше, делали, собственно вот сюда ее и загнали
$router->add("/search", SearchController::class);
$router->add("/create", SpaceObjectCreateController::class)-> middleware(new LoginRequiredMiddleware);
$router->add("/create_type", CreateTypeController::class)-> middleware(new LoginRequiredMiddleware);
$router->add("/brz_cars/(?P<id>\d+)/delete", SpaceObjectDeleteController::class)-> middleware(new LoginRequiredMiddleware);
$router->add("/brz_cars/(?P<id>\d+)/edit", SpaceObjectUpdateController::class)-> middleware(new LoginRequiredMiddleware);
$router->add("/brz_cars/(?P<id>\d+)", ObjectController::class);


$router->get_or_default(Controller404::class);
