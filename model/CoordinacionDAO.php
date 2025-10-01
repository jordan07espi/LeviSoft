<?php
// Archivo: model/CoordinacionDAO.php
require_once __DIR__ . '/../config/Conexion.php';

class CoordinacionDAO {
    private $conexion;
    public function __construct() { $db = new Conexion(); $this->conexion = $db->getConnection(); }

    public function listar() {
        $sql = "SELECT 
                    c.id_coordinacion, 
                    c.nombre_coordinacion, 
                    c.alias_coordinacion,
                    s.nombre_sede,  -- <-- La columna que faltaba
                    c.id_responsable,
                    u.nombre_completo as nombre_responsable
                FROM coordinaciones c
                JOIN sedes s ON c.id_sede = s.id_sede -- <-- El JOIN que faltaba
                LEFT JOIN usuarios u ON c.id_responsable = u.id_usuario
                WHERE c.activo = 1 
                ORDER BY c.nombre_coordinacion ASC";
                
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM coordinaciones WHERE id_coordinacion = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function agregar($nombre, $alias, $id_sede) {
        $sql = "INSERT INTO coordinaciones (nombre_coordinacion, alias_coordinacion, id_sede) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$nombre, $alias, $id_sede]);
    }


    public function actualizar($id, $nombre, $alias, $id_sede) {
        $sql = "UPDATE coordinaciones SET nombre_coordinacion = ?, alias_coordinacion = ?, id_sede = ? WHERE id_coordinacion = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$nombre, $alias, $id_sede, $id]);
    }

    public function eliminar($id) { // Eliminación lógica
        $sql = "UPDATE coordinaciones SET activo = 0 WHERE id_coordinacion = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }


    public function quitarResponsable($id_coordinacion) {
        $sql = "UPDATE coordinaciones SET id_responsable = NULL WHERE id_coordinacion = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id_coordinacion]);
    }
}
?>