<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Asistencia{


	//implementamos nuestro constructor
public function __construct(){

}


//listar registros
public function listar(){
	$sql="SELECT 
    a.idasistencia,
    a.fecha,
    a.codigo_persona,
    u.nombre,
    u.apellidos,
    d.nombre AS departamento,
    u.tipo_turno,
    CASE WHEN a.Ingreso_a_Turno IS NULL OR a.Ingreso_a_Turno = '' THEN 'No Marco' ELSE a.Ingreso_a_Turno END AS Ingreso_a_Turno,
    CASE WHEN a.Ingreso_a_Break IS NULL OR a.Ingreso_a_Break = '' THEN 'No Marco' ELSE a.Ingreso_a_Break END AS Ingreso_a_Break,
    CASE WHEN a.Salida_a_Break IS NULL OR a.Salida_a_Break = '' THEN 'No Marco' ELSE a.Salida_a_Break END AS Salida_a_Break,
    CASE WHEN a.Salida_de_Turno IS NULL OR a.Salida_de_Turno = '' THEN 'No Marco' ELSE a.Salida_de_Turno END AS Salida_de_Turno
FROM 
    asistencia a
INNER JOIN 
    usuarios u ON
     a.codigo_persona = u.codigo_persona
INNER JOIN 
    departamento d ON u.iddepartamento = d.iddepartamento
";
	return ejecutarConsulta($sql);
}

public function listaru($idusuario) {
    $sql = "SELECT 
                a.fecha,
                CASE WHEN a.Ingreso_a_Turno IS NULL OR a.Ingreso_a_Turno = '' THEN 'No Marco' ELSE a.Ingreso_a_Turno END AS Ingreso_a_Turno,
                CASE WHEN a.Ingreso_a_Break IS NULL OR a.Ingreso_a_Break = '' THEN 'No Marco' ELSE a.Ingreso_a_Break END AS Ingreso_a_Break,
                CASE WHEN a.Salida_a_Break IS NULL OR a.Salida_a_Break = '' THEN 'No Marco' ELSE a.Salida_a_Break END AS Salida_a_Break,
                CASE WHEN a.Salida_de_Turno IS NULL OR a.Salida_de_Turno = '' THEN 'No Marco' ELSE a.Salida_de_Turno END AS Salida_de_Turno
            FROM 
                asistencia a
            INNER JOIN 
                usuarios u ON a.codigo_persona = u.codigo_persona
            INNER JOIN 
                departamento d ON u.iddepartamento = d.iddepartamento
            WHERE 
                u.idusuario = '$idusuario'";
    return ejecutarConsulta($sql);
}

public function listar_asistencia($fecha_inicio,$fecha_fin,$codigo_persona){
	$sql="SELECT 
                a.fecha,
                a.codigo_persona,
                u.nombre,
                u.apellidos,
                d.nombre AS departamento,
                u.tipo_turno,
                CASE WHEN a.Ingreso_a_Turno IS NULL OR a.Ingreso_a_Turno = '' THEN 'No Marco' ELSE a.Ingreso_a_Turno END AS Ingreso_a_Turno,
                CASE WHEN a.Ingreso_a_Break IS NULL OR a.Ingreso_a_Break = '' THEN 'No Marco' ELSE a.Ingreso_a_Break END AS Ingreso_a_Break,
                CASE WHEN a.Salida_a_Break IS NULL OR a.Salida_a_Break = '' THEN 'No Marco' ELSE a.Salida_a_Break END AS Salida_a_Break,
                CASE WHEN a.Salida_de_Turno IS NULL OR a.Salida_de_Turno = '' THEN 'No Marco' ELSE a.Salida_de_Turno END AS Salida_de_Turno
                FROM asistencia a 
                INNER JOIN usuarios u ON  a.codigo_persona=u.codigo_persona 
                INNER JOIN departamento d ON u.iddepartamento = d.iddepartamento
                WHERE DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin' AND a.codigo_persona='$codigo_persona'";
	return ejecutarConsulta($sql);
}

public function listar_asistenciau($fecha_inicio,$fecha_fin,$codigo_persona){
	$sql="SELECT 
                a.fecha,
                a.codigo_persona,
                u.nombre,
                u.apellidos,
                d.nombre AS departamento,
                u.tipo_turno,
                CASE WHEN a.Ingreso_a_Turno IS NULL OR a.Ingreso_a_Turno = '' THEN 'No Marco' ELSE a.Ingreso_a_Turno END AS Ingreso_a_Turno,
                CASE WHEN a.Ingreso_a_Break IS NULL OR a.Ingreso_a_Break = '' THEN 'No Marco' ELSE a.Ingreso_a_Break END AS Ingreso_a_Break,
                CASE WHEN a.Salida_a_Break IS NULL OR a.Salida_a_Break = '' THEN 'No Marco' ELSE a.Salida_a_Break END AS Salida_a_Break,
                CASE WHEN a.Salida_de_Turno IS NULL OR a.Salida_de_Turno = '' THEN 'No Marco' ELSE a.Salida_de_Turno END AS Salida_de_Turno
                FROM asistencia a 
                INNER JOIN usuarios u ON  a.codigo_persona=u.codigo_persona 
                INNER JOIN departamento d ON u.iddepartamento = d.iddepartamento
                WHERE DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin' AND a.codigo_persona='$codigo_persona'";
	return ejecutarConsulta($sql);
}




public function listar_por_departamento(){
	$sql="SELECT 
    a.fecha,
    a.codigo_persona,
    u.nombre,
    u.apellidos,
    d.nombre AS departamento,
    u.tipo_turno,
    CASE WHEN a.Ingreso_a_Turno IS NULL OR a.Ingreso_a_Turno = '' THEN 'No Marco' ELSE a.Ingreso_a_Turno END AS Ingreso_a_Turno,
    CASE WHEN a.Ingreso_a_Break IS NULL OR a.Ingreso_a_Break = '' THEN 'No Marco' ELSE a.Ingreso_a_Break END AS Ingreso_a_Break,
    CASE WHEN a.Salida_a_Break IS NULL OR a.Salida_a_Break = '' THEN 'No Marco' ELSE a.Salida_a_Break END AS Salida_a_Break,
    CASE WHEN a.Salida_de_Turno IS NULL OR a.Salida_de_Turno = '' THEN 'No Marco' ELSE a.Salida_de_Turno END AS Salida_de_Turno
FROM 
    asistencia a
INNER JOIN 
    usuarios u ON
     a.codigo_persona = u.codigo_persona
INNER JOIN 
    departamento d ON u.iddepartamento = d.iddepartamento";
	return ejecutarConsulta($sql);
}

public function desactivar($idasistencia){
    // Iniciar una transacción
    ejecutarConsulta("START TRANSACTION");

    try {
        // Eliminar el registro de asistencia
        $sqlAsistencia = "DELETE FROM asistencia WHERE idasistencia = '$idasistencia'";
        ejecutarConsulta($sqlAsistencia);

        // Confirmar la transacción
        ejecutarConsulta("COMMIT");

        return true; // O cualquier valor de éxito que uses en tu aplicación
    } catch (Exception $e) {
        // En caso de error, revertir la transacción
        ejecutarConsulta("ROLLBACK");
        throw $e;
    }
}



}

 ?>
