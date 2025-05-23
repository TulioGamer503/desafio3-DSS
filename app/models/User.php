<?php
namespace Models;

class User
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $created_at;

    public static function create(\PDO $pdo, $username, $email, $password)
    {
        $sql = "INSERT INTO usuarios (username, email, password)
                VALUES (:u, :e, :p)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':u' => $username,
            ':e' => $email,
            ':p' => password_hash($password, PASSWORD_BCRYPT)
        ]);
        return $pdo->lastInsertId();
    }

    public static function findByEmail(\PDO $pdo, $email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = :e";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':e' => $email]);
        return $stmt->fetchObject(self::class);
    }
}
