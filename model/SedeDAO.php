<?php
// Archivo: model/SedeDAO.php
require_once __DIR__ . '/../config/Conexion.php';

class SedeDAO {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->getConnection();
    }

    public function listarSedes() {
        $sql = "SELECT id_sede, codigo_sede, nombre_sede FROM sedes ORDER BY nombre_sede ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>