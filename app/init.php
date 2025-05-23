<?php
// 1. Definir ruta base
define('APP_ROOT', realpath(__DIR__ . '/../'));

// 2. Cargar configuración de BD
require_once APP_ROOT . '/app/config/database.php';

// 3. Auto-cargar clases
spl_autoload_register(function($class) {
    $base_dir = APP_ROOT . '/app/';
    $file     = $base_dir . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// 4. (Opcional) Carga helpers globales
// require_once APP_ROOT . '/app/helpers.php';
