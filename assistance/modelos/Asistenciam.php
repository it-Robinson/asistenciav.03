<?php 
// Incluir la conexión de base de datos
require "../config/Conexion.php";

class Asistencia {
    // Implementamos nuestro constructor
    public function __construct(){}

    public function verificarcodigo_persona($codigo_persona){
        $sql = "SELECT * FROM usuarios WHERE codigo_persona='$codigo_persona'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function seleccionarcodigo_persona($codigo_persona){
        $sql = "SELECT * FROM asistencia WHERE codigo_persona = '$codigo_persona'";
        return ejecutarConsulta($sql);
    }
    public function registrar_entrada_turno($codigo_persona, $tipo){
        date_default_timezone_set('America/Lima');
        $fecha = date("Y-m-d");
        $fecha_hora_actual = date("Y-m-d H:i:s");
        $fecha_hora = date("Y-m-d H:i:s"
        //, strtotime("$fecha_hora_actual -1 hour")
    );
        
        // Verificar si la fila actual está completa
        $sql_check = "SELECT COUNT(*) AS num_columns_filled FROM asistencia WHERE codigo_persona = '$codigo_persona' AND fecha = '$fecha' AND (Ingreso_a_Turno IS NULL)";
        $result_check = ejecutarConsulta($sql_check);
        $row_check = mysqli_fetch_assoc($result_check);
        $num_columns_filled = $row_check['num_columns_filled'];
    
        if ($num_columns_filled == 0) {
            // La fila actual está completa, insertar en una nueva fila
            $sql = "INSERT INTO asistencia (codigo_persona, Ingreso_a_Turno, tipo, fecha) VALUES ('$codigo_persona', '$fecha_hora','$tipo', '$fecha')";
        } else {
            // La fila actual no está completa, actualizar en la misma fila
            $sql = "UPDATE asistencia SET Ingreso_a_Turno = '$fecha_hora', tipo = '$tipo' WHERE codigo_persona = '$codigo_persona' AND fecha = '$fecha' AND Ingreso_a_Turno IS NULL";
        }
    
        return ejecutarConsulta($sql);
    }
    
    public function registrar_entrada_break($codigo_persona, $tipo){
        date_default_timezone_set('America/Lima');
        $fecha = date("Y-m-d");
        $fecha_hora_actual = date("Y-m-d H:i:s");
        $fecha_hora = date("Y-m-d H:i:s"
        //, strtotime("$fecha_hora_actual -1 hour")
    );
        
        // Verificar si la fila actual está completa
        $sql_check = "SELECT COUNT(*) AS num_columns_filled FROM asistencia WHERE codigo_persona = '$codigo_persona' AND fecha = '$fecha' AND (Ingreso_a_Break IS NULL)";
        $result_check = ejecutarConsulta($sql_check);
        $row_check = mysqli_fetch_assoc($result_check);
        $num_columns_filled = $row_check['num_columns_filled'];
    
        if ($num_columns_filled == 0) {
            // La fila actual está completa, insertar en una nueva fila
            $sql = "INSERT INTO asistencia (codigo_persona, Ingreso_a_Break, tipo, fecha) VALUES ('$codigo_persona', '$fecha_hora','$tipo', '$fecha')";
        } else {
            // La fila actual no está completa, actualizar en la misma fila
            $sql = "UPDATE asistencia SET Ingreso_a_Break = '$fecha_hora', tipo = '$tipo' WHERE codigo_persona = '$codigo_persona' AND fecha = '$fecha' AND Ingreso_a_Break IS NULL";
        }
    
        return ejecutarConsulta($sql);
    }
    
    public function registrar_salida_break($codigo_persona, $tipo){
        date_default_timezone_set('America/Lima');
        $fecha = date("Y-m-d");
        $fecha_hora_actual = date("Y-m-d H:i:s");
        $fecha_hora = date("Y-m-d H:i:s"
       // , strtotime("$fecha_hora_actual -1 hour")
    );
        
        // Verificar si la fila actual está completa
        $sql_check = "SELECT COUNT(*) AS num_columns_filled FROM asistencia WHERE codigo_persona = '$codigo_persona' AND fecha = '$fecha' AND (Salida_a_Break IS NULL)";
        $result_check = ejecutarConsulta($sql_check);
        $row_check = mysqli_fetch_assoc($result_check);
        $num_columns_filled = $row_check['num_columns_filled'];
    
        if ($num_columns_filled == 0) {
            // La fila actual está completa, insertar en una nueva fila
            $sql = "INSERT INTO asistencia (codigo_persona, Salida_a_Break, tipo, fecha) VALUES ('$codigo_persona', '$fecha_hora','$tipo', '$fecha')";
        } else {
            // La fila actual no está completa, actualizar en la misma fila
            $sql = "UPDATE asistencia SET Salida_a_Break = '$fecha_hora', tipo = '$tipo' WHERE codigo_persona = '$codigo_persona' AND fecha = '$fecha' AND Salida_a_Break IS NULL";
        }
    
        return ejecutarConsulta($sql);
    }
    
    public function registrar_salida_turno($codigo_persona, $tipo){
        date_default_timezone_set('America/Lima');
        $fecha = date("Y-m-d");
        $fecha_hora_actual = date("Y-m-d H:i:s");
        $fecha_hora = date("Y-m-d H:i:s"
        //, strtotime("$fecha_hora_actual -1 hour")
    );
        
        // Verificar si la fila actual está completa
        $sql_check = "SELECT COUNT(*) AS num_columns_filled FROM asistencia WHERE codigo_persona = '$codigo_persona' AND fecha = '$fecha' AND (Salida_de_Turno IS NULL)";
        $result_check = ejecutarConsulta($sql_check);
        $row_check = mysqli_fetch_assoc($result_check);
        $num_columns_filled = $row_check['num_columns_filled'];
    
        if ($num_columns_filled == 0) {
            // La fila actual está completa, insertar en una nueva fila
            $sql = "INSERT INTO asistencia (codigo_persona, Salida_de_Turno, tipo, fecha) VALUES ('$codigo_persona', '$fecha_hora','$tipo', '$fecha')";
        } else {
            // La fila actual no está completa, actualizar en la misma fila
            $sql = "UPDATE asistencia SET Salida_de_Turno = '$fecha_hora', tipo = '$tipo' WHERE codigo_persona = '$codigo_persona' AND fecha = '$fecha' AND Salida_de_Turno IS NULL";
        }
    
        return ejecutarConsulta($sql);
    }
    
    
    
    // Función para obtener el tipo de la última entrada para un empleado en la fecha actual
    public function obtenerUltimoTipoEntrada($codigo_persona) {
        // Lógica para obtener el tipo de la última entrada registrada para el empleado con el código proporcionado
        // Por ejemplo:
        $sql = "SELECT tipo FROM asistencia WHERE codigo_persona = '$codigo_persona' ORDER BY idasistencia DESC LIMIT 1";
        $result = ejecutarConsultaSimpleFila($sql);
        return isset($result['tipo']) ? $result['tipo'] : null;
    }


	public function obtenerUltimaFechaRegistrada($codigo_persona) {
        // Lógica para obtener la última fecha registrada para el empleado con el código proporcionado
        // Por ejemplo:
        $sql = "SELECT fecha FROM asistencia WHERE codigo_persona = '$codigo_persona' ORDER BY idasistencia DESC LIMIT 1";
        $result = ejecutarConsultaSimpleFila($sql);
        return isset($result['fecha']) ? $result['fecha'] : null;
    }
	    public function restablecerRegistroAsistencia($codigo_persona) {
        // Lógica para restablecer el registro de asistencia para el empleado con el código proporcionado
        // Por ejemplo, podrías ejecutar una consulta para eliminar todas las entradas de asistencia para este empleado:
        $sql = "DELETE FROM asistencia WHERE codigo_persona = '$codigo_persona'";
        return ejecutarConsulta($sql);
    }


    // Listar registros
    public function listar(){
        $sql="SELECT * FROM asistencia";
        return ejecutarConsulta($sql);
    }
}
?>
