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
    // En AppController.php, dentro del switch
    case 'cargarDatosHeader':
        try {
            if (!isset($_SESSION['id_usuario'])) {
                throw new Exception("Sesión no iniciada.");
            }

            $id_usuario = $_SESSION['id_usuario'];
            $rol = $_SESSION['rol'];

            $coordinacionDAO = new CoordinacionDAO();
            $periodoDAO = new PeriodoDAO();

            $response['success'] = true;
            $response['data'] = [
                // Pasamos los datos del usuario para el filtrado
                'coordinaciones' => $coordinacionDAO->listar($id_usuario, $rol), 
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