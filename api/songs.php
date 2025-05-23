<?php
// api/songs.php
header('Content-Type: application/json');
require_once __DIR__ . '/../app/init.php';
session_start();

use Models\Song;
global $pdo;

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (!empty($_SESSION['user_id'])) {
            $data = Song::allByUser($pdo, $_SESSION['user_id']);
        } else {
            $data = Song::all($pdo);
        }
        echo json_encode($data);
        break;

    case 'POST':
        $input  = json_decode(file_get_contents('php://input'), true);
        $t      = trim($input['titulo']  ?? '');
        $a      = trim($input['artista'] ?? '');
        $al     = trim($input['album']   ?? '');
        $y      = $input['ano'] ?? '';
        $l      = trim($input['enlace']  ?? '');
        $errors = [];

        if (empty($_SESSION['user_id'])) {
            $errors[] = 'No autorizado. Debes iniciar sesión.';
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
            $newId = Song::create(
                $pdo, $_SESSION['user_id'], $t, $a, $al, intval($y), $l
            );
            http_response_code(201);
            echo json_encode(['id' => $newId]);
        }
        break;

    case 'PUT':
        $input  = json_decode(file_get_contents('php://input'), true);
        $id     = $input['id']      ?? null;
        $t      = trim($input['titulo']  ?? '');
        $a      = trim($input['artista'] ?? '');
        $al     = trim($input['album']   ?? '');
        $y      = $input['ano'] ?? '';
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
            Song::update(
                $pdo, $id, $t, $a, $al, intval($y), $l
            );
            echo json_encode(['message' => 'Actualización exitosa.']);
        }
        break;

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
            Song::delete($pdo, $id);
            echo json_encode(['message' => 'Eliminación exitosa.']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no soportado']);
        break;
}
