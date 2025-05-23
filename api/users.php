<?php
// api/users.php
header('Content-Type: application/json');
require_once __DIR__ . '/../app/init.php';
session_start();

use Models\User;

// Sólo aceptamos POST con JSON
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido, use POST']);
    exit;
}

// Leer cuerpo JSON
$data = json_decode(file_get_contents('php://input'), true);

$username = trim($data['username'] ?? '');
$email    = trim($data['email']    ?? '');
$password = $data['password']      ?? '';

$errors = [];

// Validar username (mínimo 3 chars)
if (!preg_match('/^[\w\-]{3,}$/', $username)) {
    $errors[] = 'El usuario debe tener al menos 3 caracteres alfanuméricos o guiones.';
}

// Validar email
if (!preg_match(
    '/^[\w\.\+\-]+@[A-Za-z0-9\-]+\.[A-Za-z]{2,}(?:\.[A-Za-z]{2,})*$/',
    $email
)) {
    $errors[] = 'Email no válido.';
}

// Validar contraseña: mínimo 6 caracteres, letras y números
if (!preg_match(
    '/^(?=.*[A-Za-z])(?=.*\d).{6,}$/',
    $password
)) {
    $errors[] = 'La contraseña debe tener al menos 6 caracteres y contener letras y números.';
}

// Verificar duplicado de email
if (User::findByEmail($pdo, $email)) {
    $errors[] = 'Ya existe un usuario con ese email.';
}

if ($errors) {
    http_response_code(400);
    echo json_encode(['errors' => $errors]);
    exit;
}

// Crear usuario
try {
    $newId = User::create($pdo, $username, $email, $password);
    http_response_code(201);
    echo json_encode(['id' => $newId, 'message' => 'Usuario creado correctamente.']);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al crear usuario.', 'detail' => $e->getMessage()]);
}
