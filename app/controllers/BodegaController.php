<?php
require_once __DIR__ . '/../models/Bodega.php';
require_once __DIR__ . '/../models/Encargado.php';


// este controlador administrara las vistas y las funciones necesarias llamando al modelo Bodega digamos agregar editar cambiar estado
class BodegaController
{
// crear vista llama a los modelos encargado y bodega  para entregar los valores necesarios para levantar el formulario
// lista de encargados para el selector de encargados  lista de bodega para la la tabla principal
    public function crearVistaFormulario()
    {
        $encargados = Encargado::obtenerTodos();
        $bodegas = Bodega::obtenerTodas();

        require __DIR__ . '/../views/bodega/formulario.php';
        require __DIR__ . '/../views/bodega/listaBodegas.php';
    }

// guardar bodega recive los parametros desde index que funciona como el enrutador llama a bodega crea el objeto y lo guarda mediante el modelo
// y el metodo guardar
    public function guardarBodega($datos)
    {
        $nombre = $datos['nombre'] ?? '';
        $direccion = $datos['direccion'] ?? '';
        $dotacion = intval($datos['dotacion'] ?? 0);
        $encargado_id = isset($datos['encargado_id']) ? intval($datos['encargado_id']) : null;
        header('Content-Type: application/json');

        // controla campos obligatorios
        if (empty($nombre)) {
            echo json_encode([
                'success' => false,
                'message' => 'Faltan datos obligatorios: nombre.'
            ]);
            return;
        }

        //creamos el objeto bodega le entregamos sus valores y llamamos al metodo guardar
        try {
            $bodega = new Bodega($nombre, $direccion, $dotacion, $encargado_id);
            $bodega->guardar();

            echo json_encode([
                'success' => true,
                'message' => 'Bodega guardada exitosamente.'
            ]);

        } catch (Exception $e) {

            echo json_encode([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ]);
        }
    }

    // desactivar Bodega llama al metodo estatico cabiar estado entregando el codigo de la bodega y el texto desactivado retornando un bool
    public function desactivarBodega(string $codigo): bool
    {
        return Bodega::cambiarEstado($codigo, 'Desactivada');
    }

    //esta funcion retorna las bodegas y las entrega a la vista requerida
    public function obtenerBodegas(): array
    {
        return Bodega::obtenerTodas();
    }

    // actualizar bodega toma los datos enviados desde la tabla y llama a la funcion estatica actualizar del modelo Bodega
    public function actualizarBodega($datos)
    {
        $codigo = $datos['codigo'];
        $nombre = $datos['nombre'] ?? '';
        $direccion = $datos['direccion'] ?? '';
        $dotacion = intval($datos['dotacion'] ?? 0);
        $encargado_id = isset($datos['encargado_id']) ? intval($datos['encargado_id']) : null;


        $resultado = Bodega::actualizar($codigo, $nombre, $direccion, $dotacion, $encargado_id);
        header('Content-Type: application/json');

        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Bodega actualizada correctamente.' : 'Error al actualizar la bodega.'
        ]);
    }



}
