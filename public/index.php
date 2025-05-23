<?php
// Iniciar sesión antes de todo
session_start();

// Cargar configuración, autoload y PDO
require_once __DIR__ . '/../app/init.php';

use Core\Router;
use Controllers\AuthController;
use Controllers\SongController;

$router = new Router();

// ───────── RUTA DE PRUEBA ─────────
$router->add('GET', '/desafio3-DSS/public/', function() {
    echo '¡Router funcionando!';
});

// ───────── RUTAS DE AUTENTICACIÓN ─────────
// Mostrar formulario de registro
$router->add('GET',  '/desafio3-DSS/public/register', [new AuthController, 'showRegister']);
// Procesar registro
$router->add('POST', '/desafio3-DSS/public/register', [new AuthController, 'register']);
// Mostrar formulario de login
$router->add('GET',  '/desafio3-DSS/public/login',    [new AuthController, 'showLogin']);
// Procesar login
$router->add('POST', '/desafio3-DSS/public/login',    [new AuthController, 'login']);
// Logout
$router->add('GET',  '/desafio3-DSS/public/logout',   [new AuthController, 'logout']);

// ───────── RUTAS DE CRUD DE CANCIONES ─────────
// Listar canciones
$router->add('GET',  '/desafio3-DSS/public/songs',        [new SongController, 'index']);
// Formulario de creación
$router->add('GET',  '/desafio3-DSS/public/songs/create', [new SongController, 'create']);
// Procesar creación
$router->add('POST', '/desafio3-DSS/public/songs/store',  [new SongController, 'store']);
// Formulario de edición
$router->add('GET',  '/desafio3-DSS/public/songs/edit',   [new SongController, 'edit']);
// Procesar actualización
$router->add('POST', '/desafio3-DSS/public/songs/update', [new SongController, 'update']);
// Eliminar canción
$router->add('GET',  '/desafio3-DSS/public/songs/delete', [new SongController, 'delete']);

$router->dispatch();
