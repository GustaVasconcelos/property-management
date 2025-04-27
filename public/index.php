<?php

require_once '../vendor/autoload.php';

use App\Container;
use App\Requests\CreatePropertyRequest;
use App\Requests\UpdatePropertyRequest;

session_start();

$controller = Container::get(App\Controllers\PropertyController::class);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/') {
    header("Location: /propriedades");
    exit();
}

if ($method === 'GET') {
    switch ($uri) {
        case '/propriedades':
            echo $controller->index();
            break;

        case '/propriedades/criar':
            echo $controller->create();
            break;

        case '/propriedades/editar':
            if (isset($_GET['id'])) {
                echo $controller->edit($_GET['id']);
            }
            break;

    }
}

if ($method === 'POST') {
    $data = $_POST;
    $files = $_FILES;

    if (isset($files['image']) && $files['image']['error'] !== 4) {
        $data['image'] = $files['image'];
    }

    switch ($uri) {
        case '/propriedades/criar':
            try {
                echo $controller->store(new CreatePropertyRequest($data));
            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
                header("Location: /propriedades/criar");
                exit();
            }
            break;

        case '/propriedades/editar':
            if (isset($_GET['id'])) {
                echo $controller->update($_GET['id'], new UpdatePropertyRequest($data));
            }
            break;

        case '/propriedades/deletar':
            if (isset($_GET['id'])) {
                echo $controller->destroy($_GET['id']);
            }
            break;
    }
}
