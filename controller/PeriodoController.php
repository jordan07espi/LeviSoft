<?php
// Archivo: controller/PeriodoController.php
require_once '../model/PeriodoDAO.php';
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acción no válida o no permitida.'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador';

if (!$esAdmin) {
    echo json_encode($response);
    exit();
}

$periodoDAO = new PeriodoDAO();

switch ($action) {
    case 'listar':
        $response['data'] = $periodoDAO->listarTodos();
        $response['success'] = true;
        break;
    case 'agregar':
        $ok = $periodoDAO->agregar($_POST['nombre'], $_POST['fecha_inicio'], $_POST['fecha_fin']);
        $response['success'] = $ok;
        $response['message'] = $ok ? 'Período agregado correctamente.' : 'Error al agregar el período.';
        break;
    case 'obtener':
        $response['data'] = $periodoDAO->obtenerPorId($_POST['id']);
        $response['success'] = !!$response['data'];
        break;
    case 'actualizar':
        $ok = $periodoDAO->actualizar($_POST['id'], $_POST['nombre'], $_POST['fecha_inicio'], $_POST['fecha_fin']);
        $response['success'] = $ok;
        $response['message'] = $ok ? 'Período actualizado correctamente.' : 'Error al actualizar el período.';
        break;
    case 'eliminar':
        $ok = $periodoDAO->eliminar($_POST['id']);
        $response['success'] = $ok;
        $response['message'] = $ok ? 'Período eliminado correctamente.' : 'Error al eliminar el período.';
        break;
}

echo json_encode($response);
?>