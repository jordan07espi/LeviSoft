<?php
// Archivo: model/GrupoDAO.php
require_once __DIR__ . '/../config/Conexion.php';

class GrupoDAO {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->getConnection();
    }

    // Obtiene todos los módulos del sistema para listarlos
    public function listarModulos() {
        $sql = "SELECT id_modulo, nombre_modulo FROM modulos ORDER BY nombre_modulo ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene los IDs de los módulos permitidos para un grupo específico
    public function obtenerPermisosPorGrupo($id_grupo) {
        $sql = "SELECT id_modulo FROM grupo_permisos WHERE id_grupo = :id_grupo";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_grupo', $id_grupo, PDO::PARAM_INT);
        $stmt->execute();
        // Devuelve un array plano de IDs
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    // Actualiza los permisos para un grupo (borra los antiguos y guarda los nuevos)
    public function actualizarPermisos($id_grupo, array $modulos_ids) {
        $this->conexion->beginTransaction();
        try {
            // 1. Borrar todos los permisos actuales del grupo
            $stmtDelete = $this->conexion->prepare("DELETE FROM grupo_permisos WHERE id_grupo = :id_grupo");
            $stmtDelete->execute([':id_grupo' => $id_grupo]);

            // 2. Insertar los nuevos permisos
            if (!empty($modulos_ids)) {
                $sqlInsert = "INSERT INTO grupo_permisos (id_grupo, id_modulo) VALUES (:id_grupo, :id_modulo)";
                $stmtInsert = $this->conexion->prepare($sqlInsert);
                foreach ($modulos_ids as $id_modulo) {
                    $stmtInsert->execute([':id_grupo' => $id_grupo, ':id_modulo' => $id_modulo]);
                }
            }
            
            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            error_log("Error en GrupoDAO::actualizarPermisos: " . $e->getMessage());
            return false;
        }
    }
}
?>