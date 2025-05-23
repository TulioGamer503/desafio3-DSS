<?php
namespace Controllers;

use Models\User;

/**
 * Class AuthController
 *
 * Controlador encargado de la autenticación:
 * - Registro de nuevos usuarios
 * - Inicio de sesión
 * - Cierre de sesión
 */
class AuthController
{
    /**
     * Muestra el formulario de registro de usuario.
     */
    public function showRegister()
    {
        include APP_ROOT . '/app/views/auth/register.php';
    }

    /**
     * Procesa el registro de un nuevo usuario.
     *
     * - Recoge datos del form (username, email, password).
     * - Aplica validaciones con expresiones regulares.
     * - Verifica que el email no exista ya en la BD.
     * - Si hay errores, recarga la vista con mensajes.
     * - Si todo es válido, crea el usuario y redirige al login.
     */
    public function register()
    {
        global $pdo;

        // 1. Recoger y sanear inputs
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';
        $errors   = [];

        // 2. Validar longitud mínima de username (>= 3 chars)
        if (strlen($username) < 3) {
            $errors[] = 'El usuario debe tener al menos 3 caracteres.';
        }

        // 3. Validar email con regex
        if (!preg_match(
            '/^[\w\.\+\-]+@[A-Za-z0-9\-]+\.[A-Za-z]{2,}(?:\.[A-Za-z]{2,})*$/',
            $email
        )) {
            $errors[] = 'Email no válido.';
        }

        // 4. Validar contraseña: mínimo 6 chars, al menos 1 letra y 1 dígito
        if (!preg_match(
            '/^(?=.*[A-Za-z])(?=.*\d).{6,}$/',
            $password
        )) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres y contener letras y números.';
        }

        // 5. Verificar que el email no esté ya registrado
        if (User::findByEmail($pdo, $email)) {
            $errors[] = 'Ya existe ese email.';
        }

        // 6. Si hubo errores, recargar la vista de registro mostrando mensajes
        if ($errors) {
            include APP_ROOT . '/app/views/auth/register.php';
            return;
        }

        // 7. Si todo está bien, crear el usuario (hasheando la contraseña)
        User::create($pdo, $username, $email, $password);

        // 8. Notificar éxito y redirigir al login
        $_SESSION['success'] = 'Registro exitoso. ¡Ya puedes iniciar sesión!';
        header('Location: /desafio3-DSS/public/login');
        exit;
    }

    /**
     * Muestra el formulario de login.
     */
    public function showLogin()
    {
        include APP_ROOT . '/app/views/auth/login.php';
    }

    /**
     * Procesa el inicio de sesión de un usuario.
     *
     * - Recoge email y password.
     * - Busca el usuario en BD.
     * - Verifica la contraseña con password_verify().
     * - Si es válido, almacena user_id en sesión y redirige al listado de canciones.
     * - Si no, recarga el login con mensaje de error.
     */
    public function login()
    {
        global $pdo;

        // 1. Recoger credenciales
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';

        // 2. Buscar usuario por email
        $user = User::findByEmail($pdo, $email);

        // 3. Verificar credenciales
        if ($user && password_verify($password, $user->password)) {
            // Credenciales válidas: iniciar sesión
            $_SESSION['user_id'] = $user->id;
            header('Location: /desafio3-DSS/public/songs');
            exit;
        }

        // 4. Credenciales inválidas: mostrar error
        $error = 'Credenciales incorrectas.';
        include APP_ROOT . '/app/views/auth/login.php';
    }

    /**
     * Cierra la sesión del usuario.
     *
     * - Destruye la sesión completa.
     * - Redirige al formulario de login.
     */
    public function logout()
    {
        session_destroy();
        header('Location: /desafio3-DSS/public/login');
        exit;
    }
}
