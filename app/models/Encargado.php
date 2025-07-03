<?php
require_once __DIR__ . '/../core/Database.php';

// el objeto encargado solamente retorna todos los encargados
class Encargado
{
    public static function obtenerTodos()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT id, nombre, apellido_paterno, apellido_materno FROM encargado ORDER BY nombre");
        return $stmt->fetchAll();
    }
}
