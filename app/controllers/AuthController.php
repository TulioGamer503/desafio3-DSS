<?php
namespace Controllers;

use Models\User;

class AuthController
{
    public function showRegister()
    {
        include APP_ROOT . '/app/views/auth/register.php';
    }

    public function register()
    {
        global $pdo;
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors   = [];

        // Valida longitud mínima de usuario
        if (strlen($username) < 3) {
            $errors[] = 'El usuario debe tener al menos 3 caracteres.';
        }

        // Valida email con regex
        if (!preg_match(
            '/^[\w\.\+\-]+@[A-Za-z0-9\-]+\.[A-Za-z]{2,}(?:\.[A-Za-z]{2,})*$/',
            $email
        )) {
            $errors[] = 'Email no válido.';
        }

        // Valida contraseña: mínimo 6 caracteres, al menos una letra y un número
        if (!preg_match(
            '/^(?=.*[A-Za-z])(?=.*\d).{6,}$/',
            $password
        )) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres y contener letras y números.';
        }

        // Comprueba duplicado de email
        if (User::findByEmail($pdo, $email)) {
            $errors[] = 'Ya existe ese email.';
        }

        if ($errors) {
            include APP_ROOT . '/app/views/auth/register.php';
        } else {
            User::create($pdo, $username, $email, $password);
            $_SESSION['success'] = 'Registro exitoso. ¡Ya puedes iniciar sesión!';
            header('Location: /desafio3-DSS/public/login');
            exit;
        }
    }

    public function showLogin()
    {
        include APP_ROOT . '/app/views/auth/login.php';
    }

    public function login()
    {
        global $pdo;
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $user     = User::findByEmail($pdo, $email);

        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            header('Location: /desafio3-DSS/public/');
            exit;
        } else {
            $error = 'Credenciales incorrectas.';
            include APP_ROOT . '/app/views/auth/login.php';
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /desafio3-DSS/public/login');
        exit;
    }
}
