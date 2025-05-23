<?php
// app/config/database.php

// Datos de conexión (ajusta usuario/clave si los cambiaste)
$host     = '127.0.0.1';
$dbname   = 'canciones_app';
$user     = 'root';
$password = '';
$charset  = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Esta variable $pdo quedará disponible globalmente
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    // Si hay un error, muéstralo (solo en dev)
    exit('Error de conexión BD: ' . $e->getMessage());
}
