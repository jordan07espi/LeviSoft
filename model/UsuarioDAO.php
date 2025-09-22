<?php
// Archivo: model/UsuarioDAO.php
require_once __DIR__ . '/../config/Conexion.php';
require_once __DIR__ . '/dto/Usuario.php';

class UsuarioDAO {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->getConnection();
    }

    /**
     * Obtiene un usuario activo por su cédula para el proceso de login.
     */
    public function obtenerUsuarioPorCedula($cedula) {
        $sql = "SELECT u.*, r.nombre_rol 
                FROM usuarios u 
                JOIN roles r ON u.id_rol = r.id_rol 
                WHERE u.cedula = :cedula AND u.activo = 1";
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error en UsuarioDAO::obtenerUsuarioPorCedula: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lista todos los usuarios activos con su información extendida para el módulo de Talento Humano.
     */
    public function listar() {
        $sql = "SELECT 
                    u.id_usuario, 
                    u.nombre_completo, 
                    u.cedula,
                    u.email,
                    u.telefono,
                    s.nombre_sede,
                    r.nombre_rol,
                    GROUP_CONCAT(DISTINCT g.nombre_grupo SEPARATOR ', ') as grupos
                FROM 
                    usuarios u
                LEFT JOIN 
                    sedes s ON u.id_sede = s.id_sede
                LEFT JOIN 
                    roles r ON u.id_rol = r.id_rol
                LEFT JOIN 
                    usuario_grupos ug ON u.id_usuario = ug.id_usuario
                LEFT JOIN 
                    grupos g ON ug.id_grupo = g.id_grupo
                WHERE 
                    u.activo = 1
                GROUP BY
                    u.id_usuario, u.nombre_completo, u.cedula, u.email, u.telefono, s.nombre_sede, r.nombre_rol
                ORDER BY
                    u.nombre_completo ASC";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un usuario específico por su ID con sus grupos.
     */
    public function obtenerPorId($id) {
        $sql = "SELECT 
                    u.id_usuario, 
                    u.nombre_completo, 
                    u.cedula, 
                    u.email, 
                    u.telefono, 
                    u.id_rol, 
                    u.id_sede,
                    GROUP_CONCAT(ug.id_grupo) as grupos_ids
                FROM 
                    usuarios u
                LEFT JOIN
                    usuario_grupos ug ON u.id_usuario = ug.id_usuario
                WHERE 
                    u.id_usuario = :id
                GROUP BY
                    u.id_usuario";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        // Convertir la cadena de IDs de grupos en un array
        if ($usuario && $usuario['grupos_ids']) {
            $usuario['grupos'] = explode(',', $usuario['grupos_ids']);
        } else if ($usuario) {
            $usuario['grupos'] = [];
        }
        return $usuario;
    }

    /**
     * Agrega un nuevo usuario y sus grupos asociados.
     * Se espera que $usuario->grupos sea un array de IDs de grupo.
     */
    public function agregar(Usuario $usuario, array $grupos = []) {
        $this->conexion->beginTransaction();
        try {
            $sql = "INSERT INTO usuarios (nombre_completo, cedula, email, telefono, password, id_rol, id_sede) 
                    VALUES (:nombre, :cedula, :email, :telefono, :password, :id_rol, :id_sede)";
            $stmt = $this->conexion->prepare($sql);
            
            $passwordHash = password_hash($usuario->password, PASSWORD_DEFAULT);

            $stmt->bindValue(':nombre', $usuario->nombre_completo);
            $stmt->bindValue(':cedula', $usuario->cedula);
            $stmt->bindValue(':email', $usuario->email);
            $stmt->bindValue(':telefono', $usuario->telefono);
            $stmt->bindValue(':password', $passwordHash);
            $stmt->bindValue(':id_rol', $usuario->id_rol);
            $stmt->bindValue(':id_sede', $usuario->id_sede);
            $stmt->execute();
            
            $idUsuario = $this->conexion->lastInsertId();

            // Insertar la relación en la tabla usuario_grupos
            if (!empty($grupos)) {
                $sqlGrupos = "INSERT INTO usuario_grupos (id_usuario, id_grupo) VALUES (:id_usuario, :id_grupo)";
                $stmtGrupos = $this->conexion->prepare($sqlGrupos);
                foreach ($grupos as $id_grupo) {
                    $stmtGrupos->execute([':id_usuario' => $idUsuario, ':id_grupo' => $id_grupo]);
                }
            }
            
            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            error_log("Error en UsuarioDAO::agregar: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza un usuario existente y sus grupos.
     * Se espera que $usuario->grupos sea un array de IDs de grupo.
     */
    public function actualizar(Usuario $usuario, array $grupos = []) {
        $this->conexion->beginTransaction();
        try {
            // Actualizar la tabla principal de usuarios
            if (!empty($usuario->password)) {
                $sql = "UPDATE usuarios SET nombre_completo = :nombre, cedula = :cedula, email = :email, telefono = :telefono, id_rol = :id_rol, id_sede = :id_sede, password = :password WHERE id_usuario = :id";
                $passwordHash = password_hash($usuario->password, PASSWORD_DEFAULT);
            } else {
                $sql = "UPDATE usuarios SET nombre_completo = :nombre, cedula = :cedula, email = :email, telefono = :telefono, id_rol = :id_rol, id_sede = :id_sede WHERE id_usuario = :id";
            }
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':nombre', $usuario->nombre_completo);
            $stmt->bindValue(':cedula', $usuario->cedula);
            $stmt->bindValue(':email', $usuario->email);
            $stmt->bindValue(':telefono', $usuario->telefono);
            $stmt->bindValue(':id_rol', $usuario->id_rol);
            $stmt->bindValue(':id_sede', $usuario->id_sede);
            $stmt->bindValue(':id', $usuario->id_usuario);
            if (!empty($usuario->password)) {
                $stmt->bindValue(':password', $passwordHash);
            }
            $stmt->execute();

            // Actualizar los grupos: la forma más simple es borrar y reinsertar
            $this->conexion->prepare("DELETE FROM usuario_grupos WHERE id_usuario = ?")->execute([$usuario->id_usuario]);
            
            if (!empty($grupos)) {
                $sqlGrupos = "INSERT INTO usuario_grupos (id_usuario, id_grupo) VALUES (:id_usuario, :id_grupo)";
                $stmtGrupos = $this->conexion->prepare($sqlGrupos);
                foreach ($grupos as $id_grupo) {
                    $stmtGrupos->execute([':id_usuario' => $usuario->id_usuario, ':id_grupo' => $id_grupo]);
                }
            }
            
            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            error_log("Error en UsuarioDAO::actualizar: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Realiza una eliminación lógica del usuario.
     */
    public function eliminar($id) {
        $sql = "UPDATE usuarios SET activo = 0 WHERE id_usuario = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    /**
     * Lista todos los roles disponibles.
     */
    public function listarRoles() {
        $sql = "SELECT id_rol, nombre_rol FROM roles ORDER BY nombre_rol ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lista todos los grupos disponibles.
     */
    public function listarGrupos() {
        $sql = "SELECT id_grupo, nombre_grupo FROM grupos ORDER BY nombre_grupo ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>