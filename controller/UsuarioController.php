<?php
// Archivo: controller/UsuarioController.php
require_once '../model/UsuarioDAO.php';
require_once '../model/dto/Usuario.php';

// Iniciar sesión para verificar permisos si es necesario en el futuro
session_start();

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Acción no reconocida o no permitida.'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Verificar si el usuario tiene rol de Administrador para acciones críticas
$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador';

$usuarioDAO = new UsuarioDAO();

switch ($action) {
    case 'listar':
        $response['success'] = true;
        $response['data'] = $usuarioDAO->listar();
        break;

    case 'listarRoles':
        $response['success'] = true;
        $response['data'] = $usuarioDAO->listarRoles();
        break;

    case 'listarGrupos': // Nueva acción para el formulario de Talento Humano
        $response['success'] = true;
        $response['data'] = $usuarioDAO->listarGrupos();
        break;

    case 'agregar':
        if ($esAdmin) {
            $usuario = new Usuario();
            $usuario->nombre_completo = $_POST['nombre_completo'] ?? null;
            $usuario->cedula = $_POST['cedula'] ?? null;
            $usuario->email = $_POST['email'] ?? null;
            $usuario->telefono = $_POST['telefono'] ?? null;
            $usuario->password = $_POST['password'] ?? null;
            $usuario->id_rol = $_POST['id_rol'] ?? null;
            $usuario->id_sede = !empty($_POST['id_sede']) ? $_POST['id_sede'] : null;
            
            // Recibimos los grupos como un array
            $grupos = $_POST['grupos'] ?? [];

            if ($usuarioDAO->agregar($usuario, $grupos)) {
                $response['success'] = true;
                $response['message'] = 'Usuario agregado exitosamente.';
            } else {
                $response['message'] = 'Error al agregar el usuario. La cédula podría ya existir.';
            }
        }
        break;
        
    case 'obtener':
        $id = $_POST['id_usuario'] ?? null;
        if ($id) {
            $data = $usuarioDAO->obtenerPorId($id);
            if ($data) {
                $response['success'] = true;
                $response['data'] = $data;
            } else {
                $response['message'] = 'Usuario no encontrado.';
            }
        } else {
            $response['message'] = 'No se proporcionó un ID de usuario.';
        }
        break;

    case 'actualizar':
        if ($esAdmin) {
            $usuario = new Usuario();
            $usuario->id_usuario = $_POST['id_usuario'] ?? null;
            $usuario->nombre_completo = $_POST['nombre_completo'] ?? null;
            $usuario->cedula = $_POST['cedula'] ?? null;
            $usuario->email = $_POST['email'] ?? null;
            $usuario->telefono = $_POST['telefono'] ?? null;
            $usuario->id_rol = $_POST['id_rol'] ?? null;
            $usuario->id_sede = !empty($_POST['id_sede']) ? $_POST['id_sede'] : null;
            $usuario->password = $_POST['password'] ?? ''; // La contraseña es opcional al actualizar

            $grupos = $_POST['grupos'] ?? [];

            if ($usuarioDAO->actualizar($usuario, $grupos)) {
                $response['success'] = true;
                $response['message'] = 'Usuario actualizado exitosamente.';
            } else {
                $response['message'] = 'Error al actualizar el usuario.';
            }
        }
        break;

    case 'eliminar':
        if ($esAdmin) {
            $id = $_POST['id_usuario'] ?? null;
            if ($id && $usuarioDAO->eliminar($id)) {
                $response['success'] = true;
                $response['message'] = 'Usuario eliminado exitosamente.';
            } else {
                $response['message'] = 'Error al eliminar el usuario.';
            }
        }
        break;
    case 'buscar':
        $termino = $_GET['termino'] ?? '';
        $response['success'] = true;
        $response['data'] = $usuarioDAO->buscarUsuarios($termino);
        break;
}

echo json_encode($response);
?>