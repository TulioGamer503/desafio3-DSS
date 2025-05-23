<?php
namespace Controllers;

use Models\Song;

/**
 * Class SongController
 *
 * Controlador encargado de manejar todas las operaciones CRUD
 * relacionadas con las canciones del usuario autenticado.
 */
class SongController
{
    /**
     * Muestra el listado de canciones del usuario.
     * Verifica que la sesión esté activa y carga la vista.
     */
    public function index()
    {
        global $pdo;

        // Si no hay usuario en sesión, redirige al login
        if (empty($_SESSION['user_id'])) {
            header('Location: /desafio3-DSS/public/login');
            exit;
        }

        // Obtiene las canciones del usuario a través del modelo
        $songs = Song::allByUser($pdo, $_SESSION['user_id']);

        // Incluye la vista que renderiza el listado
        include APP_ROOT . '/app/views/songs/index.php';
    }

    /**
     * Muestra el formulario para agregar una nueva canción.
     * Solo accesible si hay sesión activa.
     */
    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /desafio3-DSS/public/login');
            exit;
        }
        include APP_ROOT . '/app/views/songs/create.php';
    }

    /**
     * Procesa la creación de una canción.
     * Valida campos con regex y guarda mediante el modelo.
     */
    public function store()
    {
        global $pdo;

        // Recoger y sanear datos del formulario
        $titulo  = trim($_POST['titulo']  ?? '');
        $artista = trim($_POST['artista'] ?? '');
        $album   = trim($_POST['album']   ?? '');
        $ano     = trim($_POST['ano']     ?? '');
        $enlace  = trim($_POST['enlace']  ?? '');
        $errors  = [];

        // Validaciones:
        // - Título obligatorio
        if ($titulo === '') {
            $errors[] = 'El título es obligatorio.';
        }
        // - Artista obligatorio
        if ($artista === '') {
            $errors[] = 'El artista es obligatorio.';
        }
        // - Álbum opcional, máximo 255 caracteres
        if (strlen($album) > 255) {
            $errors[] = 'El nombre del álbum no puede exceder 255 caracteres.';
        }
        // - Año: sólo 4 dígitos numéricos
        if (!preg_match('/^[0-9]{4}$/', $ano) || intval($ano) <= 0) {
            $errors[] = 'El año debe ser un número de cuatro dígitos válido.';
        }
        // - Enlace: debe comenzar con http:// o https://
        if ($enlace && !preg_match('#^https?://\S+$#', $enlace)) {
            $errors[] = 'El enlace debe comenzar con http:// o https:// y no contener espacios.';
        }

        // Si hay errores, recarga la vista de creación con mensajes
        if ($errors) {
            include APP_ROOT . '/app/views/songs/create.php';
            return;
        }

        // Si todo es válido, crea la canción
        Song::create(
            $pdo,
            $_SESSION['user_id'],
            $titulo,
            $artista,
            $album,
            intval($ano),
            $enlace
        );

        // Mensaje de éxito y redirección al listado
        $_SESSION['success'] = 'Canción agregada correctamente.';
        header('Location: /desafio3-DSS/public/songs');
        exit;
    }

    /**
     * Muestra el formulario para editar una canción existente.
     * Verifica propiedad y sesión.
     */
    public function edit()
    {
        global $pdo;
        $id   = $_GET['id'] ?? null;
        $song = Song::find($pdo, $id);

        // Si la canción no existe o no pertenece al usuario, redirige
        if (!$song || $song->user_id != $_SESSION['user_id']) {
            header('Location: /desafio3-DSS/public/songs');
            exit;
        }
        include APP_ROOT . '/app/views/songs/edit.php';
    }

    /**
     * Procesa la actualización de una canción.
     * Reaplica validaciones antes de guardar.
     */
    public function update()
    {
        global $pdo;

        // Recoger datos del formulario
        $id       = $_POST['id']    ?? null;
        $titulo   = trim($_POST['titulo']  ?? '');
        $artista  = trim($_POST['artista'] ?? '');
        $album    = trim($_POST['album']   ?? '');
        $ano      = trim($_POST['ano']     ?? '');
        $enlace   = trim($_POST['enlace']  ?? '');
        $errors   = [];

        // Validaciones idénticas a las de store()
        if ($titulo === '') {
            $errors[] = 'El título es obligatorio.';
        }
        if ($artista === '') {
            $errors[] = 'El artista es obligatorio.';
        }
        if (strlen($album) > 255) {
            $errors[] = 'El nombre del álbum no puede exceder 255 caracteres.';
        }
        if (!preg_match('/^[0-9]{4}$/', $ano) || intval($ano) <= 0) {
            $errors[] = 'El año debe ser un número de cuatro dígitos válido.';
        }
        if ($enlace && !preg_match('#^https?://\S+$#', $enlace)) {
            $errors[] = 'El enlace debe comenzar con http:// o https:// y no contener espacios.';
        }

        // Si hay errores, recarga la vista de edición con los datos ingresados
        if ($errors) {
            $song = (object)[
                'id'      => $id,
                'titulo'  => $titulo,
                'artista' => $artista,
                'album'   => $album,
                'ano'     => $ano,
                'enlace'  => $enlace
            ];
            include APP_ROOT . '/app/views/songs/edit.php';
            return;
        }

        // Actualiza la canción en la base de datos
        Song::update(
            $pdo,
            $id,
            $titulo,
            $artista,
            $album,
            intval($ano),
            $enlace
        );

        // Mensaje de éxito y redirección al listado
        $_SESSION['success'] = 'Canción actualizada correctamente.';
        header('Location: /desafio3-DSS/public/songs');
        exit;
    }

    /**
     * Elimina una canción por su ID y redirige al listado.
     */
    public function delete()
    {
        global $pdo;
        $id = $_GET['id'] ?? null;

        // Llamada al modelo para borrar
        Song::delete($pdo, $id);

        // Mensaje de éxito y redirección
        $_SESSION['success'] = 'Canción eliminada.';
        header('Location: /desafio3-DSS/public/songs');
        exit;
    }
}
