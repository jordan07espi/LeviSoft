<?php
// Archivo: controller/AppController.php
require_once '../model/PeriodoDAO.php';
require_once '../model/UsuarioDAO.php';
require_once '../model/CoordinacionDAO.php';

session_start(); // <-- LÍNEA AÑADIDA

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Acción no reconocida.'];
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'cargarDatosHeader':
        try {
            $coordinacionDAO = new CoordinacionDAO();
            $periodoDAO = new PeriodoDAO();

            $response['success'] = true;
            $response['data'] = [
                'coordinaciones' => $coordinacionDAO->listar(), 
                'periodos'       => $periodoDAO->listarPeriodosActivos()
            ];
        } catch (Exception $e) {
            $response['message'] = 'Error al cargar los datos: ' . $e->getMessage();
        }
        break;
        
    case 'cargarModulosDashboard':
        if (isset($_SESSION['id_usuario'])) {
            try {
                $usuarioDAO = new UsuarioDAO();
                $id_usuario = $_SESSION['id_usuario'];
                $modulos = $usuarioDAO->obtenerModulosPorUsuario($id_usuario);
                
                $response['success'] = true;
                $response['data'] = $modulos;

            } catch (Exception $e) {
                $response['message'] = 'Error al cargar los módulos: ' . $e->getMessage();
            }
        } else {
            $response['message'] = 'No se ha iniciado sesión.';
        }
        break;
}

echo json_encode($response);
?>