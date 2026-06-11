<?php
require_once '../config/db.php';
require_once '../config/constants.php';

session_start();

$url = $_GET['url'] ?? 'home';
$url = rtrim($url, '/');
$parts = explode('/', $url);

$controllerName = ucfirst($parts[0] ?? 'home') . 'Controller';
$action = $parts[1] ?? 'index';

// Route par défaut
if ($parts[0] === '' || $parts[0] === 'home') {
    $controllerName = 'HomeController';
    $action = 'index';
}

$controllerFile = '../app/Controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        http_response_code(404);
        echo "Action introuvable : $action";
    }
} else {
    http_response_code(404);
    echo "Contrôleur introuvable : $controllerName";
}
