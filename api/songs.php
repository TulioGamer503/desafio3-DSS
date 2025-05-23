<?php
// api/songs.php
//
// Endpoint REST para manejar canciones: LISTAR, CREAR, ACTUALIZAR y BORRAR
// Responde con JSON y códigos HTTP adecuados.

header('Content-Type: application/json');

require_once __DIR__ . '/../app/init.php';
session_start();

use Models\Song;
global $pdo;

// Determinar método HTTP de la petición
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    /**
     * GET /api/songs.php
     * --------------------------------
     * - Si hay sesión iniciada, devuelve sólo las canciones del usuario.
     * - Si no hay sesión, devuelve todas las canciones de la BD.
     */
    case 'GET':
        if (!empty($_SESSION['user_id'])) {
            // Usuario autenticado: traer sólo sus canciones
            $data = Song::allByUser($pdo, $_SESSION['user_id']);
        } else {
            // Sin sesión: traer todas las canciones
            $data = Song::all($pdo);
        }
        echo json_encode($data);
        break;

    /**
     * POST /api/songs.php
     * --------------------------------
     * Crea una nueva canción. Recibe JSON en el body con:
     * {
     *   "titulo": "...",
     *   "artista": "...",
     *   "album": "...",
     *   "ano": "2023",
     *   "enlace": "https://..."
     * }
     * Valida con regex y devuelve:
     * - 400 con lista de errores si falla validación o sin sesión.
     * - 201 con { "id": nuevoId } si se crea OK.
     */
    case 'POST':
        $input  = json_decode(file_get_contents('php://input'), true);
        $t      = trim($input['titulo']  ?? '');
        $a      = trim($input['artista'] ?? '');
        $al     = trim($input['album']   ?? '');
        $y      = $input['ano']          ?? '';
        $l      = trim($input['enlace']  ?? '');
        $errors = [];

        // Autorización: sólo usuarios logueados
        if (empty($_SESSION['user_id'])) {
            $errors[] = 'No autorizado. Debes iniciar sesión.';
        }
        // Validaciones:
        if ($t === '') {
            $errors[] = 'El título es obligatorio.';
        }
        if ($a === '') {
            $errors[] = 'El artista es obligatorio.';
        }
        if (strlen($al) > 255) {
            $errors[] = 'El álbum no puede exceder 255 caracteres.';
        }
        // Año: cuatro dígitos numéricos
        if (!preg_match('/^[0-9]{4}$/', $y) || intval($y) <= 0) {
            $errors[] = 'El año debe ser un número de cuatro dígitos válido.';
        }
        // Enlace: opcional pero debe cumplir patrón
        if ($l && !preg_match('#^https?://\S+$#', $l)) {
            $errors[] = 'El enlace debe comenzar con http:// o https://';
        }

        // Si hay errores, responder 400 con el array de errores
        if ($errors) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
        } else {
            // Crear canción y responder 201 con su nuevo ID
            $newId = Song::create(
                $pdo,
                $_SESSION['user_id'],
                $t,
                $a,
                $al,
                intval($y),
                $l
            );
            http_response_code(201);
            echo json_encode(['id' => $newId]);
        }
        break;

    /**
     * PUT /api/songs.php
     * --------------------------------
     * Actualiza una canción existente. Recibe JSON con:
     * {
     *   "id": 5,
     *   "titulo": "...",
     *   "artista": "...",
     *   "album": "...",
     *   "ano": "2023",
     *   "enlace": "https://..."
     * }
     * - Valida sesión, existencia de ID y campos.
     * - Responde 400 si hay errores de validación.
     * - Devuelve 200 con mensaje de éxito si se actualiza.
     */
    case 'PUT':
        $input  = json_decode(file_get_contents('php://input'), true);
        $id     = $input['id']         ?? null;
        $t      = trim($input['titulo']  ?? '');
        $a      = trim($input['artista'] ?? '');
        $al     = trim($input['album']   ?? '');
        $y      = $input['ano']         ?? '';
        $l      = trim($input['enlace']  ?? '');
        $errors = [];

        if (empty($_SESSION['user_id'])) {
            $errors[] = 'No autorizado.';
        }
        if (!$id) {
            $errors[] = 'ID de canción requerido.';
        }
        if ($t === '') {
            $errors[] = 'El título es obligatorio.';
        }
        if ($a === '') {
            $errors[] = 'El artista es obligatorio.';
        }
        if (strlen($al) > 255) {
            $errors[] = 'El álbum no puede exceder 255 caracteres.';
        }
        if (!preg_match('/^[0-9]{4}$/', $y) || intval($y) <= 0) {
            $errors[] = 'El año debe ser un número de cuatro dígitos válido.';
        }
        if ($l && !preg_match('#^https?://\S+$#', $l)) {
            $errors[] = 'El enlace debe comenzar con http:// o https://';
        }

        if ($errors) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
        } else {
            // Ejecutar actualización
            Song::update($pdo, $id, $t, $a, $al, intval($y), $l);
            echo json_encode(['message' => 'Actualización exitosa.']);
        }
        break;

    /**
     * DELETE /api/songs.php
     * --------------------------------
     * Elimina una canción. Recibe JSON con { "id": 5 }.
     * - Verifica sesión y presencia de ID.
     * - Responde 401 si no autorizado, 400 si falta ID.
     * - Devuelve 200 con mensaje de éxito tras borrado.
     */
    case 'DELETE':
        $input = json_decode(file_get_contents('php://input'), true);
        $id    = $input['id'] ?? null;

        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autorizado.']);
        } elseif (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de canción requerido.']);
        } else {
            // Borrar canción
            Song::delete($pdo, $id);
            echo json_encode(['message' => 'Eliminación exitosa.']);
        }
        break;

    /**
     * Cualquier otro método
     */
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no soportado']);
        break;
}
