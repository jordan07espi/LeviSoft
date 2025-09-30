<?php
// Archivo: controller/SedeController.php
require_once '../model/SedeDAO.php';
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acción no válida'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$sedeDAO = new SedeDAO();

switch ($action) {
    case 'listar':
        $response['data'] = $sedeDAO->listar();
        $response['success'] = true;
        break;
    case 'agregar':
        $ok = $sedeDAO->agregar($_POST['nombre'], $_POST['alias'], $_POST['id_provincia'], $_POST['id_canton'], $_POST['id_parroquia'], $_POST['telefonos'], $_POST['direccion']);
        $response['success'] = $ok;
        $response['message'] = $ok ? 'Sede agregada correctamente.' : 'Error al agregar la sede.';
        break;
    case 'obtener':
        $response['data'] = $sedeDAO->obtenerPorId($_POST['id']);
        $response['success'] = !!$response['data'];
        break;
    case 'actualizar':
        $ok = $sedeDAO->actualizar($_POST['id'], $_POST['nombre'], $_POST['alias'], $_POST['id_provincia'], $_POST['id_canton'], $_POST['id_parroquia'], $_POST['telefonos'], $_POST['direccion']);
        $response['success'] = $ok;
        $response['message'] = $ok ? 'Sede actualizada correctamente.' : 'Error al actualizar la sede.';
        break;
    case 'eliminar':
        $ok = $sedeDAO->eliminar($_POST['id']);
        $response['success'] = $ok;
        $response['message'] = $ok ? 'Sede eliminada correctamente.' : 'Error al eliminar la sede.';
        break;
    case 'listarProvincias':
        $response['data'] = $sedeDAO->listarProvincias();
        $response['success'] = true;
        break;
    case 'listarCantones':
        $id_provincia = $_GET['id_provincia'] ?? 0;
        $response['data'] = $sedeDAO->listarCantones($id_provincia);
        $response['success'] = true;
        break;
    case 'listarParroquias':
        $id_canton = $_GET['id_canton'] ?? 0;
        $response['data'] = $sedeDAO->listarParroquias($id_canton);
        $response['success'] = true;
        break;
}

echo json_encode($response);
?>