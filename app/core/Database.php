<?php
class Database
{
    private static $pdo = null;

    public static function connect()
    {
        if (self::$pdo === null) {
            // aqui definimos de donde sacaremos la conexion con la base de datos y esamos PDO para evitar la inyeccion SQL
            $config = require __DIR__ . '/../../config/config.php';
            $db = $config['db'];

            $dsn = "pgsql:host={$db['host']};port={$db['port']};dbname={$db['dbname']}";

            try {
                //creamos la instancia con el dsn para pgsql
                self::$pdo = new PDO($dsn, $db['user'], $db['password']);
                // aqui confirugo pdo para los errores
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // aqui configuro PDO para que los resultados se devuelvan como arrays asociativos
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Error de conexión a la base de datos: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
