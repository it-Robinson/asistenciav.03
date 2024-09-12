<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Departamento{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre,$descripcion,$idusuario){
		date_default_timezone_set('America/Mexico_City');
	$fechacreada=date('Y-m-d H:i:s');
	$sql="INSERT INTO departamento (nombre,descripcion,fechacreada,idusuario) VALUES ('$nombre','$descripcion','$fechacreada','$idusuario')";
	return ejecutarConsulta($sql);
}

public function editar($iddepartamento,$nombre,$descripcion,$idusuario){
	$sql="UPDATE departamento SET nombre='$nombre',descripcion='$descripcion',idusuario='$idusuario' 
	WHERE iddepartamento='$iddepartamento'";
	return ejecutarConsulta($sql);
}
public function desactivar($iddepartamento){
    // Iniciar una transacción
    ejecutarConsulta("START TRANSACTION");

    try {
        // Actualizar los usuarios para asignar un iddepartamento predeterminado en lugar de NULL
        $sqlUsuarios = "UPDATE usuarios SET iddepartamento = 10 WHERE iddepartamento = '$iddepartamento'";
        ejecutarConsulta($sqlUsuarios);

        // Eliminar el departamento
        $sqlDepartamento = "DELETE FROM departamento WHERE iddepartamento = '$iddepartamento'";
        ejecutarConsulta($sqlDepartamento);

        // Confirmar la transacción
        ejecutarConsulta("COMMIT");

        return true; // O cualquier valor de éxito que uses en tu aplicación
    } catch (Exception $e) {
        // En caso de error, revertir la transacción
        ejecutarConsulta("ROLLBACK");
        throw $e;
    }
}




public function activar($iddepartamento){
	$sql="UPDATE departamento SET fechacreada='1' WHERE iddepartamento='$iddepartamento'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($iddepartamento){
	$sql="SELECT * FROM departamento WHERE iddepartamento='$iddepartamento'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM departamento";
	return ejecutarConsulta($sql);
}
//listar y mostrar en selct
public function select(){
	$sql="SELECT * FROM departamento";
	return ejecutarConsulta($sql);
}

public function regresaRolDepartamento($departamento){
	$sql="SELECT nombre FROM departamento where iddepartamento='$departamento'";		
	return ejecutarConsulta($sql);
}



}

 ?>
