<?php
// api/songs.php
header('Content-Type: application/json');
require_once __DIR__ . '/../app/init.php';
session_start();

// Solo usuarios autenticados
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

use Models\Song;
global $pdo;

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        // listar todas las canciones del usuario
        $songs = Song::allByUser($pdo, $_SESSION['user_id']);
        echo json_encode($songs);
        break;

    case 'POST':
        // crear nueva canción (body en JSON)
        $data = json_decode(file_get_contents('php://input'), true);
        $errors = [];

        $t = trim($data['titulo']  ?? '');
        $a = trim($data['artista'] ?? '');
        $y = intval($data['ano']   ?? 0);
        $l = trim($data['enlace']  ?? '');

        if ($t === '')   { $errors[] = 'El título es obligatorio.'; }
        if ($a === '')   { $errors[] = 'El artista es obligatorio.'; }
        if ($y <= 0)     { $errors[] = 'El año debe ser un número válido.'; }
        if ($l && !filter_var($l, FILTER_VALIDATE_URL)) {
            $errors[] = 'El enlace debe ser una URL válida.';
        }

        if ($errors) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
        } else {
            $id = Song::create($pdo, $_SESSION['user_id'], $t, $a, $y, $l);
            http_response_code(201);
            echo json_encode(['id' => $id]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no soportado']);
        break;
}
