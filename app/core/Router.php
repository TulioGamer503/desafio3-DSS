<?php
// app/core/Router.php
namespace Core;

class Router
{
    protected $routes = [];

    public function add(string $method, string $path, $handler)
    {
        $this->routes[] = compact('method','path','handler');
    }

    public function dispatch()
    {
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $uri) {
                // Si es Closure o [Controller, 'method']
                return call_user_func($route['handler']);
            }
        }

        // Ruta no encontrada
        header("HTTP/1.0 404 Not Found");
        echo "404 - Página no encontrada";
    }
}
