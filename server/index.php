<?php


header ('Content-type: json/application');

include_once 'utils/controller.php';
include_once 'utils/db.php';

//rewrite is switched on in .htaccess file
//в файле .htaccess включен rewrite
$params = explode('/', rtrim($_GET['q'], '/'));
$controller = $params[0];

if (!file_exists('./controllers/' . $controller . '.php')){
    die('Что-то пошло не так');
};

require './controllers/' . $controller . '.php';

if (!class_exists($controller)){
    die('Что-то пошло не так');
};

$controller = new $controller($db);

$action = $_SERVER['REQUEST_METHOD'];

if (!method_exists($controller, $action)){
    die('Что-то пошло не так');
};

switch($action){
    case 'POST':
        $controller->$action($_POST);
        break;
    case 'GET':
        $page = $params[1] ? $params[1] : 1;
        $token = $_GET['token'] ? $_GET['token'] : 1;
        $controller->$action($page, $token);
        break;
    case 'DELETE':
        $controller->$action($params[1]);
        break;
}




