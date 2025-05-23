<?php
namespace Controllers;

use Models\Song;

class SongController
{
    // Listar canciones
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

    // Formulario de creación
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
        $titulo  = trim($_POST['titulo']  ?? '');
        $artista = trim($_POST['artista'] ?? '');
        $album   = trim($_POST['album']   ?? '');  // ← nuevo
        $ano     = intval($_POST['ano']    ?? 0);
        $enlace  = trim($_POST['enlace']  ?? '');
        $errors  = [];

        // Validaciones
        if ($titulo === '')  { $errors[] = 'El título es obligatorio.'; }
        if ($artista === '') { $errors[] = 'El artista es obligatorio.'; }
        // album es opcional, pero limitamos longitud
        if (strlen($album) > 255) {
            $errors[] = 'El nombre del álbum no puede exceder 255 caracteres.';
        }
        if ($ano <= 0)       { $errors[] = 'El año debe ser un número válido.'; }
        if ($enlace && !filter_var($enlace, FILTER_VALIDATE_URL)) {
            $errors[] = 'El enlace debe ser una URL válida.';
        }

        if ($errors) {
            include APP_ROOT . '/app/views/songs/create.php';
        } else {
            Song::create($pdo,
                         $_SESSION['user_id'],
                         $titulo,
                         $artista,
                         $album,       // ← pasamos album
                         $ano,
                         $enlace);
            $_SESSION['success'] = 'Canción agregada correctamente.';
            header('Location: /desafio3-DSS/public/songs');
            exit;
        }
    }

    // Formulario de edición
    public function edit()
    {
        global $pdo;
        $id   = $_GET['id'] ?? null;
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
        $id       = $_POST['id']    ?? null;
        $titulo   = trim($_POST['titulo']  ?? '');
        $artista  = trim($_POST['artista'] ?? '');
        $album    = trim($_POST['album']   ?? '');  // ← nuevo
        $ano      = intval($_POST['ano']    ?? 0);
        $enlace   = trim($_POST['enlace']  ?? '');
        $errors   = [];

        if ($titulo === '')  { $errors[] = 'El título es obligatorio.'; }
        if ($artista === '') { $errors[] = 'El artista es obligatorio.'; }
        if (strlen($album) > 255) {
            $errors[] = 'El nombre del álbum no puede exceder 255 caracteres.';
        }
        if ($ano <= 0)       { $errors[] = 'El año debe ser un número válido.'; }
        if ($enlace && !filter_var($enlace, FILTER_VALIDATE_URL)) {
            $errors[] = 'El enlace debe ser una URL válida.';
        }

        if ($errors) {
            // reinyectamos datos en la vista
            $song = (object)[
                'id'       => $id,
                'titulo'   => $titulo,
                'artista'  => $artista,
                'album'    => $album,
                'ano'      => $ano,
                'enlace'   => $enlace
            ];
            include APP_ROOT . '/app/views/songs/edit.php';
        } else {
            Song::update($pdo,
                         $id,
                         $titulo,
                         $artista,
                         $album,       // ← pasamos album
                         $ano,
                         $enlace);
            $_SESSION['success'] = 'Canción actualizada correctamente.';
            header('Location: /desafio3-DSS/public/songs');
            exit;
        }
    }

    // Eliminar canción
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
