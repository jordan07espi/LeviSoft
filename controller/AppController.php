<?php
// Archivo: controller/AppController.php
require_once '../model/SedeDAO.php';
require_once '../model/PeriodoDAO.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Acción no reconocida.'];
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'cargarDatosHeader':
        try {
            $sedeDAO = new SedeDAO();
            $periodoDAO = new PeriodoDAO();

            $response['success'] = true;
            $response['data'] = [
                'sedes'    => $sedeDAO->listarSedes(),
                'periodos' => $periodoDAO->listarPeriodosActivos()
            ];
        } catch (Exception $e) {
            $response['message'] = 'Error al cargar los datos: ' . $e->getMessage();
        }
        break;
}

echo json_encode($response);
?>