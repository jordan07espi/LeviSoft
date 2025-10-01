<?php
// Archivo: controller/CoordinacionController.php
require_once '../model/CoordinacionDAO.php';
require_once '../model/SedeDAO.php'; //
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acción no reconocida.'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

$coordinacionDAO = new CoordinacionDAO();

switch ($action) {
    case 'listar':
        $id_usuario = $_SESSION['id_usuario'] ?? 0;
        $rol = $_SESSION['rol'] ?? '';
        $response['data'] = $coordinacionDAO->listar($id_usuario, $rol);
        $response['success'] = true;
        break;
    case 'agregar':
        $ok = $coordinacionDAO->agregar($_POST['nombre'], $_POST['alias'], $_POST['id_sede'], $_POST['id_responsable']);
        $response['success'] = $ok;
        $response['message'] = $ok ? 'Coordinación agregada.' : 'Error al agregar.';
        break;
    case 'obtener':
        $response['data'] = $coordinacionDAO->obtenerPorId($_POST['id']);
        $response['success'] = !!$response['data'];
        break;
    case 'actualizar':
        $ok = $coordinacionDAO->actualizar($_POST['id'], $_POST['nombre'], $_POST['alias'], $_POST['id_sede'], $_POST['id_responsable']);
        $response['success'] = $ok;
        $response['message'] = $ok ? 'Coordinación actualizada.' : 'Error al actualizar.';
        break;
    case 'eliminar':
        $ok = $coordinacionDAO->eliminar($_POST['id']);
        $response['success'] = $ok;
        $response['message'] = $ok ? 'Coordinación eliminada.' : 'Error al eliminar.';
        break;
    case 'listarSedes': // <-- AÑADIR ESTE CASE COMPLETO
        $sedeDAO = new SedeDAO();
        $response['success'] = true;
        $response['data'] = $sedeDAO->listar();         
        break;
}

echo json_encode($response);
?>