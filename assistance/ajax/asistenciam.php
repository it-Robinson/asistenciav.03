<?php 
require_once "../modelos/Asistenciam.php";

$asistencia = new Asistencia();

$codigo_persona = isset($_POST["codigo_persona"]) ? limpiarCadena($_POST["codigo_persona"]) : "";
$iddepartamento = isset($_POST["iddepartamento"]) ? limpiarCadena($_POST["iddepartamento"]) : "";

switch ($_GET["op"]) {
    case 'registrar_asistencia':
        $result = $asistencia->verificarcodigo_persona($codigo_persona);

        if ($result) {
            date_default_timezone_set('America/Lima');
            $hora = date("H:i:s");

            // Obtener la última fecha registrada para este empleado
            $ultima_fecha_registro = $asistencia->obtenerUltimaFechaRegistrada($codigo_persona);

            // Si no hay registros anteriores o la fecha del último registro es diferente a la fecha actual, reiniciar en "Entrada de Turno"
            if (!$ultima_fecha_registro || $ultima_fecha_registro != date('Y-m-d')) {
                $tipo = "Entrada de Turno";
            } else {
                // Determinar el tipo de entrada según el último registro
                $ultimo_tipo_entrada = $asistencia->obtenerUltimoTipoEntrada($codigo_persona);
                switch ($ultimo_tipo_entrada) {
                    case "Entrada de Turno":
                        $tipo = "Entrada de Break";
                        break;
                    case "Entrada de Break":
                        $tipo = "Salida de Break";
                        break;
                    case "Salida de Break":
                        $tipo = "Salida de Turno";
                        break;
                    default:
                        $tipo = "Entrada de Turno";
                }
            }

            // Registrar la entrada con el tipo determinado
            if ($tipo =="Entrada de Turno") {
                $rspta = $asistencia->registrar_entrada_turno($codigo_persona, $tipo);
                echo $rspta ? '<h3><strong>Nombres: </strong> ' . $result['nombre'] . ' ' . $result['apellidos'] . '</h3><div class="alert alert-success">Ingreso de Turno Registrado- ' . $hora . '</div>' : 'No se pudo registrar la entrada';
               }else if ($tipo == "Entrada de Break"){
                $rspta = $asistencia->registrar_entrada_break($codigo_persona, $tipo);
                echo $rspta ? '<h3><strong>Nombres: </strong> ' . $result['nombre'] . ' ' . $result['apellidos'] . '</h3><div class="alert alert-info">Ingreso de Break Registrado- ' . $hora . '</div>' : 'No se pudo registrar la entrada';
               }else if ($tipo == "Salida de Break"){
               $rspta = $asistencia->registrar_salida_break($codigo_persona, $tipo);
               echo $rspta ? '<h3><strong>Nombres: </strong> ' . $result['nombre'] . ' ' . $result['apellidos'] . '</h3><div class="alert alert-warning">Salida de Break Registrado- ' . $hora . '</div>' : 'No se pudo registrar la salida';
               }else if ($tipo == "Salida de Turno"){
                $rspta = $asistencia->registrar_salida_turno($codigo_persona, $tipo);
               echo $rspta ? '<h3><strong>Nombres: </strong> ' . $result['nombre'] . ' ' . $result['apellidos'] . '</h3><div class="alert alert-danger">Salida de Turno Registrado- ' . $hora . '</div>' : 'No se pudo registrar la salida';
               }
           

        } else {
            echo '<div class="alert alert-danger">
                       <i class="icon fa fa-warning"></i>Ya se registro la asistencia!!
                         </div>';
        }
        break;


        case 'registrar_asistencia2':
            $result = $asistencia->verificarcodigo_persona($codigo_persona);
    
            if ($result) {
                date_default_timezone_set('America/Lima');
                $hora = date("H:i:s");
    
                // Obtener la última fecha registrada para este empleado
                $ultima_fecha_registro = $asistencia->obtenerUltimaFechaRegistrada($codigo_persona);
    
                // Si no hay registros anteriores o la fecha del último registro es diferente a la fecha actual, reiniciar en "Entrada de Turno"
                if (!$ultima_fecha_registro || $ultima_fecha_registro != date('Y-m-d')) {
                    $tipo = "Entrada de Turno";
                } else {
                    // Determinar el tipo de entrada según el último registro
                    $ultimo_tipo_entrada = $asistencia->obtenerUltimoTipoEntrada($codigo_persona);
                    switch ($ultimo_tipo_entrada) {
                        case "Entrada de Turno":
                            $tipo = "Salida de Turno";
                            break;
                        default:
                            $tipo = "Entrada de Turno";
                    }
                }
    
                // Registrar la entrada con el tipo determinado
                if (substr($tipo, 0, 7) == "Entrada") {
                    $rspta = $asistencia->registrar_entrada_turno($codigo_persona, $tipo);
                    
                    echo $rspta ? '<h3><strong>Nombres: </strong> ' . $result['nombre'] . ' ' . $result['apellidos'] . '</h3><div class="alert alert-success">Ingreso de Turno Registrado- ' . $hora . '</div>' : 'No se pudo registrar la entrada';
                  
                } else {
                    $rspta = $asistencia->registrar_salida_turno($codigo_persona, $tipo);
                    echo $rspta ? '<h3><strong>Nombres: </strong> ' . $result['nombre'] . ' ' . $result['apellidos'] . '</h3><div class="alert alert-danger">Salida de Turno Registrado- ' . $hora . '</div>' : 'No se pudo registrar la salida';
                   
                }
    
            } else {
                echo '<div class="alert alert-danger">
                           <i class="icon fa fa-warning"></i>Ya se registro la asistencia!!
                             </div>';
            }
            break;




}
?>
