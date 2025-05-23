<?php
namespace Models;

/**
 * Clase User
 * Representa a un usuario de la aplicación y proporciona
 * métodos para crear usuarios y buscarlos por email.
 */
class User
{
    // Propiedades que reflejan las columnas de la tabla `usuarios`
    public $id;          // Identificador único (AUTO_INCREMENT)
    public $username;    // Nombre de usuario (VARCHAR)
    public $email;       // Correo electrónico (VARCHAR)
    public $password;    // Contraseña hasheada (VARCHAR)
    public $created_at;  // Fecha de creación (TIMESTAMP)

    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * @param \PDO   $pdo       Conexión PDO a la BD.
     * @param string $username  Nombre de usuario.
     * @param string $email     Correo electrónico.
     * @param string $password  Contraseña en texto plano.
     *
     * @return int ID del usuario recién creado.
     */
    public static function create(\PDO $pdo, $username, $email, $password)
    {
        // 1. Preparar la consulta con placeholders para evitar inyecciones SQL
        $sql = "INSERT INTO usuarios (username, email, password)
                VALUES (:u, :e, :p)";
        $stmt = $pdo->prepare($sql);

        // 2. Ejecutar la consulta, hasheando la contraseña con BCRYPT
        $stmt->execute([
            ':u' => $username,
            ':e' => $email,
            ':p' => password_hash($password, PASSWORD_BCRYPT)
        ]);

        // 3. Devolver el ID asignado automáticamente por la base de datos
        return $pdo->lastInsertId();
    }

    /**
     * Busca un usuario por su correo electrónico.
     *
     * @param \PDO   $pdo   Conexión PDO a la BD.
     * @param string $email Correo electrónico a buscar.
     *
     * @return User|null Instancia de User si se encuentra, o null si no existe.
     */
    public static function findByEmail(\PDO $pdo, $email)
    {
        // 1. Preparar consulta SELECT para localizar al usuario por email
        $sql = "SELECT * FROM usuarios WHERE email = :e";
        $stmt = $pdo->prepare($sql);

        // 2. Ejecutar con el email proporcionado
        $stmt->execute([':e' => $email]);

        // 3. Devolver el resultado como un objeto de la propia clase User
        return $stmt->fetchObject(self::class);
    }
}
