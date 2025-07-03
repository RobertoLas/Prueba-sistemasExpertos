<?php
require_once __DIR__ . '/../core/Database.php';

class Bodega
{
    public $codigo;
    public $nombre;
    public $direccion;
    public $dotacion;
    public $encargado_id;

    /**
     * Constructor de la clase Bodega
     */
    public function __construct($nombre, $direccion, $dotacion, $encargado_id)
    {
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->dotacion = $dotacion;
        $this->encargado_id = $encargado_id;
    }

    /**
     * Guarda una nueva bodega y su encargado asociado en la base de datos.
     */
    public function guardar()
    {
        $db = Database::connect();
        try {
            $db->beginTransaction();

            $codigo = $this->generarCodigo($db);
            $this->codigo = $codigo;

            $stmt = $db->prepare("INSERT INTO bodega (codigo, nombre, direccion, dotacion) 
                                  VALUES (:codigo, :nombre, :direccion, :dotacion)");
            $stmt->execute([
                ':codigo' => $codigo,
                ':nombre' => $this->nombre,
                ':direccion' => $this->direccion,
                ':dotacion' => $this->dotacion
            ]);

            $stmt2 = $db->prepare("INSERT INTO bodega_encargado (id, bodega_codigo, encargado_id) 
                                   VALUES (DEFAULT, :bodega_codigo, :encargado_id)");
            $stmt2->execute([
                ':bodega_codigo' => $codigo,
                ':encargado_id' => $this->encargado_id
            ]);

            $db->commit();
            return true;

        } catch (PDOException $e) {
            $db->rollBack();
            throw new Exception("Error al guardar la bodega: " . $e->getMessage());
        }
    }

    /**
     * Genera un nuevo código incremental tipo 'B0001'
     */
    private function generarCodigo($db)
    {
        try {
            $stmt = $db->query("SELECT MAX(codigo) AS ultimo FROM bodega WHERE codigo LIKE 'B____'");
            $row = $stmt->fetch();

            if ($row && $row['ultimo']) {
                $numero = intval(substr($row['ultimo'], 1)) + 1;
            } else {
                $numero = 1;
            }

            return 'B' . str_pad($numero, 4, '0', STR_PAD_LEFT);
        } catch (PDOException $e) {
            throw new Exception("Error al generar código: " . $e->getMessage());
        }
    }

    /**
     * Obtiene todas las bodegas con información de su encargado
     */
    public static function obtenerTodas()
    {
        $pdo = Database::connect();
        try {
            $stmt = $pdo->query("
                SELECT b.codigo, b.nombre, b.direccion, b.dotacion, b.estado, b.creado_en AS creado_en,
                       e.nombre AS encargado_nombre, e.apellido_paterno AS encargado_paterno, e.apellido_materno AS encargado_materno
                FROM bodega b
                LEFT JOIN bodega_encargado be ON b.codigo = be.bodega_codigo
                LEFT JOIN encargado e ON be.encargado_id = e.id
                ORDER BY b.creado_en DESC
            ");

            $bodegas = $stmt->fetchAll();

            foreach ($bodegas as &$bodega) {
                if (!empty($bodega['creado_en'])) {
                    $fecha = new DateTime($bodega['creado_en']);
                    $bodega['creado_en'] = $fecha->format('d-m-Y H:i');
                } else {
                    $bodega['creado_en'] = '';
                }
            }

            return $bodegas;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener bodegas: " . $e->getMessage());
        }
    }

    /**
     * Cambia el estado de una bodega
     */
    public static function cambiarEstado(string $codigo, string $nuevoEstado): bool
    {
        $pdo = Database::connect();
        try {
            $stmt = $pdo->prepare("UPDATE bodega SET estado = :estado WHERE codigo = :codigo");
            return $stmt->execute([
                ':estado' => $nuevoEstado,
                ':codigo' => $codigo
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error al cambiar estado: " . $e->getMessage());
        }
    }

    /**
     * Actualiza los datos de una bodega y su encargado
     */
    public static function actualizar($codigo, $nombre, $direccion, $dotacion, $encargado_id): bool
    {
        $pdo = Database::connect();
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("
                UPDATE bodega SET nombre = :nombre, direccion = :direccion, dotacion = :dotacion
                WHERE codigo = :codigo
            ");
            $bodegaActualizada = $stmt->execute([
                ':codigo' => $codigo,
                ':nombre' => $nombre,
                ':direccion' => $direccion,
                ':dotacion' => $dotacion
            ]);

            $stmt2 = $pdo->prepare("UPDATE bodega_encargado SET encargado_id = :encargado_id WHERE bodega_codigo = :codigo");
            $encargadoActualizado = $stmt2->execute([
                ':codigo' => $codigo,
                ':encargado_id' => $encargado_id
            ]);

            if ($bodegaActualizada && $encargadoActualizado) {
                $pdo->commit();
                return true;
            } else {
                $pdo->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw new Exception("Error al actualizar bodega: " . $e->getMessage());
        }
    }
}
