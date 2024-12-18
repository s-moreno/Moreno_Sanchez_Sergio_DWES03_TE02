<?php
//Configurar según donde tengamos el proyecto guardado.
define('BASE_PATH', 'projects/DWES03_TE02/public/');

// definir el tipo JSON y codificación UTF-8
header('Content-Type: application/json; charset=UTF-8');

require_once '../core/Router.php';
require_once '../core/FrontController.php';
require_once '../app/v1/models/ProductModel.php';
require_once '../app/v1/services/ProductService.php';
require_once '../app/v1/controllers/ErrorController.php';
require_once '../app/v1/controllers/ProductController.php';

// Crear un objeto enrutador
$router = new Router();
// Añadir las rutas definidas (endpoints)
require_once '../app/v1/routes/productRoutes.php';

// Crear instancia del frontcontroller y llamar al método de iniciarlo:
$frontController = new FrontController($router);
$frontController->init();
