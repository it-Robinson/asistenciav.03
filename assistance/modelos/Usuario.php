<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Usuario{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$clavehash,$imagen,$usuariocreado,$tipo_turno,$codigo_persona,$fecha_nacimiento,$empresa,$extension,$primary_phone_number){
	date_default_timezone_set('America/Mexico_City');
	$fechacreado=date('Y-m-d H:i:s');
	$sql="INSERT INTO usuarios (nombre,apellidos,login,iddepartamento,idtipousuario,email,password,imagen,estado,fechacreado,usuariocreado,tipo_turno,codigo_persona,fecha_nacimiento,empresa,extension,primary_phone_number) VALUES ('$nombre','$apellidos','$login','$iddepartamento','$idtipousuario','$email','$clavehash','$imagen','1','$fechacreado','$usuariocreado','$tipo_turno','$codigo_persona','$fecha_nacimiento','$empresa','$extension','$primary_phone_number')";
	return ejecutarConsulta($sql);

}

public function editar($idusuario,$nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$imagen,$usuariocreado,$tipo_turno,$codigo_persona,$fecha_nacimiento,$empresa,$extension,$primary_phone_number){
	$sql="UPDATE usuarios SET nombre='$nombre',apellidos='$apellidos',login='$login',iddepartamento='$iddepartamento',idtipousuario='$idtipousuario',email='$email',imagen='$imagen' ,usuariocreado='$usuariocreado',tipo_turno='$tipo_turno',codigo_persona='$codigo_persona',fecha_nacimiento='$fecha_nacimiento',empresa='$empresa',extension='$extension',primary_phone_number='$primary_phone_number'
	WHERE idusuario='$idusuario'";
	 return ejecutarConsulta($sql);

}
public function editar_clave($idusuario,$clavehash){
	$sql="UPDATE usuarios SET password='$clavehash' WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}
public function mostrar_clave($idusuario){
	$sql="SELECT idusuario, password FROM usuarios WHERE idusuario='$idusuario'";
	return ejecutarConsultaSimpleFila($sql);
}
public function desactivar($idusuario) {
    // Iniciar una transacción
    ejecutarConsulta("START TRANSACTION");

    try {
        // Eliminar el usuario
        $sqlUsuario = "DELETE FROM usuarios WHERE idusuario = '$idusuario'";
        ejecutarConsulta($sqlUsuario);

        // Confirmar la transacción
        ejecutarConsulta("COMMIT");

        return true; // O cualquier valor de éxito que uses en tu aplicación
    } catch (Exception $e) {
        // En caso de error, revertir la transacción
        ejecutarConsulta("ROLLBACK");
        throw $e;
    }
}
public function activar($idusuario){
	$sql="UPDATE usuarios SET estado='1' WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idusuario){
	$sql="SELECT * FROM usuarios WHERE idusuario='$idusuario'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT usuarios.idusuario,
    usuarios.nombre,
    usuarios.apellidos,
    usuarios.fecha_nacimiento,
    usuarios.login,
    usuarios.email,
    usuarios.imagen,
    usuarios.tipo_turno,
    usuarios.fechacreado,
    usuarios.estado, 
    departamento.nombre AS nombre_departamento,
    usuarios.empresa,
    usuarios.extension,
    usuarios.primary_phone_number
	FROM usuarios INNER JOIN departamento ON usuarios.iddepartamento = departamento.iddepartamento";
	return ejecutarConsulta($sql);
}




public function listare(){
	$sql="SELECT * from usuarios";
	return ejecutarConsulta($sql);
}

public function cantidad_usuario(){
	$sql="SELECT count(*) nombre FROM usuarios";
	return ejecutarConsulta($sql);
}

//Función para verificar el acceso al sistema
public function verificar($login, $clave)
{
    $sql = "SELECT u.codigo_persona, u.idusuario, u.nombre, u.apellidos, u.login, u.idtipousuario, u.iddepartamento, u.email, u.imagen, u.login, tu.nombre as tipousuario, d.nombre as nombre_departamento 
            FROM usuarios u 
            INNER JOIN tipousuario tu ON u.idtipousuario = tu.idtipousuario 
            INNER JOIN departamento d ON u.iddepartamento = d.iddepartamento 
            WHERE u.login = '$login' AND u.password = '$clave' AND u.estado = '1'";
    return ejecutarConsulta($sql);
}

    // Método para obtener los datos del usuario por su ID
    public function get_usuario($idusuario) {
        global $conexion; // Usamos la conexión global
        $sql = "SELECT * FROM usuarios WHERE idusuario = ?";
        
        // Preparar la consulta usando la conexión global
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idusuario);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Método para actualizar los datos del usuario
    public function editar_usuario($idusuario, $nombre, $apellidos, $email, $imagen, $fecha_nacimiento, $tipo_turno, $extension, $primary_phone_number) {
        global $conexion; // Usamos la conexión a la base de datos correctamente
    
        // Inicializar consulta SQL
        $sql = "UPDATE usuarios 
                SET nombre = ?, apellidos = ?, email = ?, imagen = ?,fecha_nacimiento = ?, tipo_turno = ?, extension = ?, primary_phone_number = ? 
                WHERE idusuario = ?";
    
        // Preparar la consulta
        $stmt = $conexion->prepare($sql);
    
        // Enlazar parámetros (actualizando la cadena de tipos a 11 parámetros)
        $stmt->bind_param("ssssssssi", $nombre, $apellidos, $email, $imagen,$fecha_nacimiento, $tipo_turno, $extension, $primary_phone_number, $idusuario);
    
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        } else {
            die("Error en la consulta: " . $conexion->error);
        }
    }

    // Método para obtener los departamentos
    public function obtener_departamentos() {
        global $conexion;
        $sql = "SELECT iddepartamento, nombre,descripcion FROM departamento";
        $query = $conexion->query($sql);

        // Verificar si hay errores en la consulta
        if (!$query) {
            die("Error en la consulta: " . $conexion->error);
        }

        return $query;
    }
	

    
    // Método para obtener los departamentos
    public function obtener_tipousuarios() {
        global $conexion;
        $sql = "SELECT idtipousuario, nombre FROM tipousuario";
        $query = $conexion->query($sql);

        // Verificar si hay errores en la consulta
        if (!$query) {
            die("Error en la consulta: " . $conexion->error);
        }

        return $query;
    }


}

 ?>