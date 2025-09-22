<?php
// Archivo: model/PeriodoDAO.php
require_once __DIR__ . '/../config/Conexion.php';

class PeriodoDAO {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->getConnection();
    }

    public function listarPeriodosActivos() {
        $sql = "SELECT id_periodo, nombre_periodo FROM periodos_lectivos WHERE activo = 1 ORDER BY fecha_inicio DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>