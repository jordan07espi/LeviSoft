<?php
// Archivo: model/CoordinacionDAO.php
require_once __DIR__ . '/../config/Conexion.php';

class CoordinacionDAO {
    private $conexion;
    public function __construct() { $db = new Conexion(); $this->conexion = $db->getConnection(); }

    public function listar() {
        $sql = "SELECT 
                    c.id_coordinacion, c.nombre_coordinacion, c.alias_coordinacion,
                    s.nombre_sede,
                    u.nombre_completo as nombre_responsable
                FROM coordinaciones c
                JOIN sedes s ON c.id_sede = s.id_sede
                LEFT JOIN usuarios u ON c.id_responsable = u.id_usuario
                WHERE c.activo = 1 ORDER BY c.nombre_coordinacion ASC";
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM coordinaciones WHERE id_coordinacion = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function agregar($nombre, $alias, $id_sede, $id_responsable) {
        $sql = "INSERT INTO coordinaciones (nombre_coordinacion, alias_coordinacion, id_sede, id_responsable) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$nombre, $alias, $id_sede, $id_responsable ?: null]);
    }

    public function actualizar($id, $nombre, $alias, $id_sede, $id_responsable) {
        $sql = "UPDATE coordinaciones SET nombre_coordinacion = ?, alias_coordinacion = ?, id_sede = ?, id_responsable = ? WHERE id_coordinacion = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$nombre, $alias, $id_sede, $id_responsable ?: null, $id]);
    }

    public function eliminar($id) { // Eliminación lógica
        $sql = "UPDATE coordinaciones SET activo = 0 WHERE id_coordinacion = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>