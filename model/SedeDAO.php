<?php
// Archivo: model/SedeDAO.php
require_once __DIR__ . '/../config/Conexion.php';

class SedeDAO {
    private $conexion;
    public function __construct() { $db = new Conexion(); $this->conexion = $db->getConnection(); }

    public function listar() {
        $sql = "SELECT s.*, p.provincia, c.canton, pa.parroquia 
                FROM sedes s
                LEFT JOIN tbl_provincia p ON s.id_provincia = p.id
                LEFT JOIN tbl_canton c ON s.id_canton = c.id
                LEFT JOIN tbl_parroquia pa ON s.id_parroquia = pa.id
                WHERE s.activo = 1 ORDER BY s.nombre_sede ASC";
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conexion->prepare("SELECT * FROM sedes WHERE id_sede = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function agregar($nombre, $alias, $provincia, $canton, $parroquia, $telefonos, $direccion) {
        $sql = "INSERT INTO sedes (nombre_sede, codigo_sede, id_provincia, id_canton, id_parroquia, telefonos, direccion) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$nombre, $alias, $provincia ?: null, $canton ?: null, $parroquia ?: null, $telefonos, $direccion]);
    }

    public function actualizar($id, $nombre, $alias, $provincia, $canton, $parroquia, $telefonos, $direccion) {
        $sql = "UPDATE sedes SET nombre_sede = ?, codigo_sede = ?, id_provincia = ?, id_canton = ?, id_parroquia = ?, telefonos = ?, direccion = ? WHERE id_sede = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$nombre, $alias, $provincia ?: null, $canton ?: null, $parroquia ?: null, $telefonos, $direccion, $id]);
    }

    public function eliminar($id) {
        $stmt = $this->conexion->prepare("UPDATE sedes SET activo = 0 WHERE id_sede = ?");
        return $stmt->execute([$id]);
    }

    public function listarProvincias() {
        return $this->conexion->query("SELECT id, provincia FROM tbl_provincia ORDER BY provincia ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarCantones($id_provincia) {
        $stmt = $this->conexion->prepare("SELECT id, canton FROM tbl_canton WHERE id_provincia = ? ORDER BY canton ASC");
        $stmt->execute([$id_provincia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarParroquias($id_canton) {
        $stmt = $this->conexion->prepare("SELECT id, parroquia FROM tbl_parroquia WHERE id_canton = ? ORDER BY parroquia ASC");
        $stmt->execute([$id_canton]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>