<?php
// Archivo: controller/GrupoController.php
require_once '../model/GrupoDAO.php';
require_once '../model/UsuarioDAO.php'; // Lo usamos para listar los grupos

session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acción no reconocida.'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';
$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador';

if (!$esAdmin) {
    $response['message'] = 'Acceso denegado.';
    echo json_encode($response);
    exit();
}

$grupoDAO = new GrupoDAO();

switch ($action) {
    // Este case es para obtener los grupos para la lista de la izquierda
    case 'listarGrupos':
        $usuarioDAO = new UsuarioDAO();
        $response['success'] = true;
        $response['data'] = $usuarioDAO->listarGrupos();
        break;

    // Este case obtiene todos los módulos disponibles en el sistema
    case 'listarModulos':
        $response['success'] = true;
        $response['data'] = $grupoDAO->listarModulos();
        break;

    // Este case obtiene los permisos específicos de UN grupo
    case 'obtenerPermisos':
        $id_grupo = $_GET['id_grupo'] ?? 0;
        $response['success'] = true;
        $response['data'] = $grupoDAO->obtenerPermisosPorGrupo($id_grupo);
        break;

    // Este case guarda los nuevos permisos para un grupo
    case 'guardarPermisos':
        $id_grupo = $_POST['id_grupo'] ?? 0;
        $modulos = $_POST['modulos'] ?? [];
        if ($grupoDAO->actualizarPermisos($id_grupo, $modulos)) {
            $response['success'] = true;
            $response['message'] = 'Permisos actualizados correctamente.';
        } else {
            $response['message'] = 'Error al guardar los permisos.';
        }
        break;


    // Este case obtiene los usuarios que pertenecen a UN grupo
    case 'listarUsuariosPorGrupo':
        $id_grupo = $_GET['id_grupo'] ?? 0;
        $usuarioDAO = new UsuarioDAO();
        $response['success'] = true;
        $response['data'] = $usuarioDAO->listarPorGrupo($id_grupo);
        break;
    }

echo json_encode($response);
?>