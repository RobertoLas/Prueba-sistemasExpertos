<?php

// en este documento index toma la forma de un enrutador obligando mediante el documento .htaccess
// en un principio pensaba trabajarlo por formulario directo y luego me decidi por hacerlo todo por fetch
//la logica es facil de entender tenemos una URL (bodega) a la cual dependiendo del metodo y una accion responde de acuerdo a lo necesario
require_once __DIR__ . '/../app/controllers/BodegaController.php';
if ($_SERVER['REQUEST_URI'] === '/bodega/listar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    $controller = new BodegaController();
    $bodegas = $controller->obtenerBodegas(); 
    echo json_encode($bodegas);
    exit;
}

if ($_SERVER['REQUEST_URI'] === '/bodega') {
    $controller = new BodegaController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->crearVistaFormulario();

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['accion'])) {
            if ($input['accion'] === 'desactivar') {

                $codigo = $input['codigo'] ?? null;
                $resultado = $controller->desactivarBodega($codigo);
                echo json_encode(['success' => $resultado]);
                exit;
            }
            if ($input['accion'] === 'editar') {
                $_POST = $input; 
                $controller->actualizarBodega($input);
                exit;
            }
            if ($input['accion'] === 'crear') {
                $_POST = $input;
                ob_start(); 
                $controller->guardarBodega($input);
                ob_end_clean();
                echo json_encode(['success' => true]);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => 'Acción no reconocida']);
        exit;
    } else {
        http_response_code(405);
        echo "Método no permitido";
    }
} else {
    http_response_code(404);
    echo "Página no encontrada";
}
