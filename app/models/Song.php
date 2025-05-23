<?php
namespace Models;

/**
 * Clase Song
 *
 * Representa una canción registrada por un usuario y proporciona métodos
 * para realizar operaciones CRUD sobre la tabla `canciones`.
 */
class Song
{
    // Propiedades que reflejan las columnas de la tabla `canciones`
    public $id;         // Identificador único (AUTO_INCREMENT)
    public $user_id;    // ID del usuario propietario
    public $titulo;     // Título de la canción
    public $artista;    // Artista o banda
    public $album;      // Álbum al que pertenece (nullable)
    public $ano;        // Año de lanzamiento
    public $enlace;     // URL opcional (p.ej. YouTube, Spotify)
    public $created_at; // Fecha de creación en la BD

    /**
     * Obtiene todas las canciones de un usuario dado.
     *
     * @param \PDO $pdo     Conexión PDO a la base de datos.
     * @param int  $userId  ID del usuario cuyas canciones queremos listar.
     * @return Song[]       Array de objetos Song.
     */
    public static function allByUser(\PDO $pdo, $userId)
    {
        // 1. Preparar consulta para seleccionar por user_id
        $sql = "SELECT * 
                  FROM canciones 
                 WHERE user_id = :u 
              ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);

        // 2. Ejecutar con el parámetro userId
        $stmt->execute([':u' => $userId]);

        // 3. Mapear resultados a objetos de esta clase
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    /**
     * Obtiene todas las canciones de todos los usuarios.
     *
     * @param \PDO $pdo  Conexión PDO.
     * @return Song[]    Array de objetos Song.
     */
    public static function all(\PDO $pdo)
    {
        // Consulta simple sin filtros
        $sql = "SELECT * 
                  FROM canciones 
              ORDER BY created_at DESC";

        // Ejecutar y mapear
        return $pdo
            ->query($sql)
            ->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    /**
     * Obtiene una canción por su ID.
     *
     * @param \PDO $pdo  Conexión PDO.
     * @param int  $id   ID de la canción.
     * @return Song|null Objeto Song o null si no existe.
     */
    public static function find(\PDO $pdo, $id)
    {
        // Preparar SELECT con límite 1
        $sql = "SELECT *
                  FROM canciones
                 WHERE id = :id
                 LIMIT 1";
        $stmt = $pdo->prepare($sql);

        // Ejecutar con el ID proporcionado
        $stmt->execute([':id' => $id]);

        // Mapear a un objeto de esta clase
        return $stmt->fetchObject(self::class);
    }

    /**
     * Inserta una nueva canción en la base de datos.
     *
     * @param \PDO   $pdo     Conexión PDO.
     * @param int    $userId  ID del usuario que crea la canción.
     * @param string $titulo  Título de la canción.
     * @param string $artista Artista o banda.
     * @param string $album   Álbum (puede ser vacío).
     * @param int    $ano     Año de lanzamiento.
     * @param string $enlace  URL externa (puede ser vacío).
     * @return int            ID recién creado.
     */
    public static function create(\PDO $pdo, $userId, $titulo, $artista, $album, $ano, $enlace)
    {
        // 1. Preparar INSERT con placeholders
        $sql = "INSERT INTO canciones
                  (user_id, titulo, artista, album, ano, enlace)
                VALUES
                  (:u, :t, :a, :al, :y, :l)";
        $stmt = $pdo->prepare($sql);

        // 2. Ejecutar con los valores proporcionados
        $stmt->execute([
            ':u'  => $userId,
            ':t'  => $titulo,
            ':a'  => $artista,
            ':al' => $album,
            ':y'  => $ano,
            ':l'  => $enlace
        ]);

        // 3. Devolver el ID generado
        return $pdo->lastInsertId();
    }

    /**
     * Actualiza una canción existente.
     *
     * @param \PDO   $pdo     Conexión PDO.
     * @param int    $id      ID de la canción a actualizar.
     * @param string $titulo  Nuevo título.
     * @param string $artista Nuevo artista.
     * @param string $album   Nuevo álbum.
     * @param int    $ano     Nuevo año.
     * @param string $enlace  Nueva URL.
     * @return bool           True si la operación tuvo éxito.
     */
    public static function update(\PDO $pdo, $id, $titulo, $artista, $album, $ano, $enlace)
    {
        // 1. Preparar la sentencia UPDATE
        $sql = "UPDATE canciones
                   SET titulo  = :t,
                       artista = :a,
                       album   = :al,
                       ano     = :y,
                       enlace  = :l
                 WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        // 2. Ejecutar con los nuevos valores
        return $stmt->execute([
            ':t'  => $titulo,
            ':a'  => $artista,
            ':al' => $album,
            ':y'  => $ano,
            ':l'  => $enlace,
            ':id' => $id
        ]);
    }

    /**
     * Elimina una canción de la base de datos.
     *
     * @param \PDO $pdo Conexión PDO.
     * @param int  $id  ID de la canción a borrar.
     * @return bool     True si la eliminación fue exitosa.
     */
    public static function delete(\PDO $pdo, $id)
    {
        // Preparar DELETE
        $sql = "DELETE FROM canciones WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        // Ejecutar
        return $stmt->execute([':id' => $id]);
    }
}
