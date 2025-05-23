<?php
namespace Controllers;

use Models\Song;

class SongController
{
    // Listado de canciones
    public function index()
    {
        global $pdo;
        if (empty($_SESSION['user_id'])) {
            header('Location: /desafio3-DSS/public/login');
            exit;
        }
        $songs = Song::allByUser($pdo, $_SESSION['user_id']);
        include APP_ROOT . '/app/views/songs/index.php';
    }

    // Mostrar formulario de creación
    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /desafio3-DSS/public/login');
            exit;
        }
        include APP_ROOT . '/app/views/songs/create.php';
    }

    // Procesar creación
    public function store()
    {
        global $pdo;
        $titulo  = trim($_POST['titulo'] ?? '');
        $artista = trim($_POST['artista'] ?? '');
        $ano     = intval($_POST['ano'] ?? 0);
        $enlace  = trim($_POST['enlace'] ?? '');
        $errors  = [];

        // Validaciones simples
        if ($titulo === '')   { $errors[] = 'El título es obligatorio.'; }
        if ($artista === '')  { $errors[] = 'El artista es obligatorio.'; }
        if ($ano <= 0)        { $errors[] = 'El año debe ser un número válido.'; }
        if ($enlace && !filter_var($enlace, FILTER_VALIDATE_URL)) {
            $errors[] = 'El enlace debe ser una URL válida.';
        }

        if ($errors) {
            include APP_ROOT . '/app/views/songs/create.php';
        } else {
            Song::create($pdo, $_SESSION['user_id'], $titulo, $artista, $ano, $enlace);
            $_SESSION['success'] = 'Canción agregada correctamente.';
            header('Location: /desafio3-DSS/public/songs');
            exit;
        }
    }

    // Mostrar formulario de edición
    public function edit()
    {
        global $pdo;
        $id = $_GET['id'] ?? null;
        $song = Song::find($pdo, $id);
        if (!$song || $song->user_id != $_SESSION['user_id']) {
            header('Location: /desafio3-DSS/public/songs');
            exit;
        }
        include APP_ROOT . '/app/views/songs/edit.php';
    }

    // Procesar actualización
    public function update()
    {
        global $pdo;
        $id       = $_POST['id'] ?? null;
        $titulo   = trim($_POST['titulo'] ?? '');
        $artista  = trim($_POST['artista'] ?? '');
        $ano      = intval($_POST['ano'] ?? 0);
        $enlace   = trim($_POST['enlace'] ?? '');
        $errors   = [];

        if ($titulo === '')  { $errors[] = 'El título es obligatorio.'; }
        if ($artista === '') { $errors[] = 'El artista es obligatorio.'; }
        if ($ano <= 0)       { $errors[] = 'El año debe ser un número válido.'; }
        if ($enlace && !filter_var($enlace, FILTER_VALIDATE_URL)) {
            $errors[] = 'El enlace debe ser una URL válida.';
        }

        if ($errors) {
            $song = (object)[
                'id'       => $id,
                'titulo'   => $titulo,
                'artista'  => $artista,
                'ano'      => $ano,
                'enlace'   => $enlace
            ];
            include APP_ROOT . '/app/views/songs/edit.php';
        } else {
            Song::update($pdo, $id, $titulo, $artista, $ano, $enlace);
            $_SESSION['success'] = 'Canción actualizada correctamente.';
            header('Location: /desafio3-DSS/public/songs');
            exit;
        }
    }

    // Eliminar
    public function delete()
    {
        global $pdo;
        $id = $_GET['id'] ?? null;
        Song::delete($pdo, $id);
        $_SESSION['success'] = 'Canción eliminada.';
        header('Location: /desafio3-DSS/public/songs');
        exit;
    }
}
