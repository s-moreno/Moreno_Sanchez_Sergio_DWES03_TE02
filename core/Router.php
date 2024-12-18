<?php

class Router
{
    protected $routes = array();
    protected $params = array();

    /**
     * Añade una ruta (endPoint) al enrutador.
     * 
     * @param string $route ruta realativa o path
     * @param string $method GET, POST, PUT, PATCH, DELETE
     * @param array $params [controller, action]
     * @return void 
     */

    public function add($route, $method, $params): void
    {
        $this->routes[$route][$method] = $params;
    }

    /**
     * Devuelve un array las rutas establecidas (endPoints)
     * 
     * @return array 
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Devuelve un array con los parámetros [controller, action]
     * de una ruta específica
     * 
     * @return array [controller, action]
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Si la ruta (path) y el método http coincide
     * con un endpoint configurado, devuelve true. En caso contrario,
     * devuelve false.
     * @param array $url [http, path]
     * @return bool 
     */
    public function match($url): bool
    {
        foreach ($this->routes as $route => $methods) {

            $pattern = str_replace(['{id}', '/'], ['([0-9]+)', '\/'], $route);
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $url['path'])) {
                foreach ($methods as $method => $params) {
                    if ($url['HTTP'] == $method) {
                        // Guardamos los parámetros (controlador y acción) del binomio ruta/método:
                        $this->params = $params;
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
