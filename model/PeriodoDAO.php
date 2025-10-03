<?php
// Archivo: model/PeriodoDAO.php
require_once __DIR__ . '/../config/Conexion.php';

class PeriodoDAO {
    private $conexion;
    public function __construct() { $db = new Conexion(); $this->conexion = $db->getConnection(); }

    public function listarPeriodosActivos() {
        $sql = "SELECT id_periodo, nombre_periodo FROM periodos_lectivos WHERE activo = 1 ORDER BY fecha_inicio DESC";
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- NUEVOS MÉTODOS CRUD ---
    public function listarTodos() {
        $sql = "SELECT * FROM periodos_lectivos WHERE activo = 1 ORDER BY fecha_inicio DESC";
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conexion->prepare("SELECT * FROM periodos_lectivos WHERE id_periodo = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function agregar($nombre, $fecha_inicio, $fecha_fin) {
        $sql = "INSERT INTO periodos_lectivos (nombre_periodo, fecha_inicio, fecha_fin) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$nombre, $fecha_inicio, $fecha_fin]);
    }

    public function actualizar($id, $nombre, $fecha_inicio, $fecha_fin) {
        $sql = "UPDATE periodos_lectivos SET nombre_periodo = ?, fecha_inicio = ?, fecha_fin = ? WHERE id_periodo = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$nombre, $fecha_inicio, $fecha_fin, $id]);
    }

    public function eliminar($id) {
        $sql = "UPDATE periodos_lectivos SET activo = 0 WHERE id_periodo = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>