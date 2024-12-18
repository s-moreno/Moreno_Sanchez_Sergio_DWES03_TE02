<?php

class FrontController
{
    protected Router $router;

    /**
     * Método construct para instanciar un frontController con las rutas definidas como endpoints.
     * @param Router $router 
     * @return void 
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Método que inicializa el frontController
     * @return void 
     */
    public function init(): void
    {
        // Obtener la url de la solicitud
        $url = $_SERVER['REQUEST_URI'];

        //Solo para localhost, con un DNS personalizado no es necesario y se podría comentar.
        $url = str_replace(BASE_PATH, '', $url);

        // Mantener el / si es la raíz, si no eliminar.
        $url = $url === '/' ? '/' : rtrim($url, '/');

        // Obtener el método HTTP (get, post, ...)
        $method = $_SERVER['REQUEST_METHOD'];

        // Dividir la url y crear un array con sus elementos
        $urlParams = explode('/', ltrim($url, '/'));

        // Crear un array con el método y la ruta de la solicitud
        $urlArray = array(
            'HTTP' => $method,
            'path' => $url,
        );

        // verificar si la ruta coincide con las definidas por nosotros en el enrutador
        if ($this->router->match($urlArray)) {

            $controller = $this->router->getParams()['controller'];
            $action = $this->router->getParams()['action'];
            $params = []; //añadiremos el ID y/o el request body si existen

            // Añadir el ID a $params si existe y es un número
            // http://../api/v1/products/{{id}} -> id estará en el indice número 3 del array
            if (!empty($urlParams[3]) && ctype_digit($urlParams[3])) {
                $params[] = (int) $urlParams[3];
            }
            // Añadir el cuerpo de la solicitud si existe a $params.
            $body = file_get_contents('php://input');
            if (!empty($body)) {
                $params[] = $body;
            }

            // Instanciar el controlador correspondiente
            $controller = new $controller();

            //Llamamos al método del controlador, pasando los parámetros
            call_user_func_array([$controller, $action], $params);

            // si la ruta no está definida, devolvemos un error 404
        } else {
            $controller = new ErrorController();
            $controller->error404();
        }
    }
}
