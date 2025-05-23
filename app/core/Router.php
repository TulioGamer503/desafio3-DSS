<?php
// app/core/Router.php

namespace Core;

/**
 * Class Router
 *
 * Encapsula la lógica de enrutamiento de las peticiones HTTP,
 * mapeando rutas a handlers (closures o métodos de controlador).
 */
class Router
{
    /**
     * @var array Lista de rutas registradas.
     * Cada ruta es un array con claves 'method', 'path' y 'handler'.
     */
    protected $routes = [];

    /**
     * Registra una nueva ruta en el router.
     *
     * @param string        $method  Método HTTP (GET, POST, PUT, DELETE…).
     * @param string        $path    Ruta URI exacta que debe coincidir.
     * @param callable|array $handler Closure o array [Controlador, 'método'].
     */
    public function add(string $method, string $path, $handler)
    {
        // Compact crea un array asociativo con las variables dadas
        $this->routes[] = compact('method', 'path', 'handler');
    }

    /**
     * Procesa la petición actual:
     * - Extrae el URI y el método HTTP
     * - Busca una ruta coincidente
     * - Invoca el handler si lo encuentra
     * - Si no, responde con un 404
     */
    public function dispatch()
    {
        // parse_url elimina la query string y devuelve solo el path
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Itera sobre todas las rutas registradas
        foreach ($this->routes as $route) {
            // Comprueba coincidencia exacta de método y URI
            if ($route['method'] === $method && $route['path'] === $uri) {
                // Llama al handler: puede ser un Closure o un método de controlador
                return call_user_func($route['handler']);
            }
        }

        // Si no se encontró ninguna ruta, enviamos un 404
        header("HTTP/1.0 404 Not Found");
        echo "404 - Página no encontrada";
    }
}
