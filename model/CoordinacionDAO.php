<?php
// Archivo: model/CoordinacionDAO.php
require_once __DIR__ . '/../config/Conexion.php';

class CoordinacionDAO {
    private $conexion;
    public function __construct() { $db = new Conexion(); $this->conexion = $db->getConnection(); }

    // En CoordinacionDAO.php
    public function listar($id_usuario, $rol) {
        if ($rol === 'Administrador') {
            // El administrador ve todas las coordinaciones activas
            $sql = "SELECT c.id_coordinacion, c.nombre_coordinacion, c.alias_coordinacion
                    FROM coordinaciones c
                    WHERE c.activo = 1 ORDER BY c.nombre_coordinacion ASC";
            $stmt = $this->conexion->prepare($sql);
        } else {
            // Otros roles solo ven las coordinaciones a las que tienen acceso
            $sql = "SELECT c.id_coordinacion, c.nombre_coordinacion, c.alias_coordinacion
                    FROM coordinaciones c
                    JOIN usuario_coordinacion_acceso uca ON c.id_coordinacion = uca.id_coordinacion
                    WHERE c.activo = 1 AND uca.id_usuario = :id_usuario
                    ORDER BY c.nombre_coordinacion ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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