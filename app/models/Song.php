<?php
namespace Models;

class Song
{
    public $id;
    public $user_id;
    public $titulo;
    public $artista;
    public $ano;
    public $enlace;
    public $created_at;

    /**
     * Obtiene todas las canciones de un usuario
     */
    public static function allByUser(\PDO $pdo, $userId)
    {
        $sql = "SELECT * FROM canciones WHERE user_id = :u ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':u' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    /**
     * Obtiene una canción por su ID
     */
    public static function find(\PDO $pdo, $id)
    {
        $sql = "SELECT * FROM canciones WHERE id = :id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchObject(self::class);
    }

    /**
     * Crea una nueva canción
     */
    public static function create(\PDO $pdo, $userId, $titulo, $artista, $ano, $enlace)
    {
        $sql = "INSERT INTO canciones
                  (user_id, titulo, artista, ano, enlace)
                VALUES
                  (:u, :t, :a, :y, :l)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':u' => $userId,
            ':t' => $titulo,
            ':a' => $artista,
            ':y' => $ano,
            ':l' => $enlace
        ]);
        return $pdo->lastInsertId();
    }

    /**
     * Actualiza una canción existente
     */
    public static function update(\PDO $pdo, $id, $titulo, $artista, $ano, $enlace)
    {
        $sql = "UPDATE canciones
                   SET titulo  = :t,
                       artista = :a,
                       ano     = :y,
                       enlace  = :l
                 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':t'  => $titulo,
            ':a'  => $artista,
            ':y'  => $ano,
            ':l'  => $enlace,
            ':id' => $id
        ]);
    }

    /**
     * Elimina una canción
     */
    public static function delete(\PDO $pdo, $id)
    {
        $sql = "DELETE FROM canciones WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
