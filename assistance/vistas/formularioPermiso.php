<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">

<!-- DataTables JavaScript -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>








<?php
//activamos almacenamiento en el buffer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
} else {
    require 'header.php';
    require_once('../modelos/Usuario.php');
    $usuario = new Usuario();
    $rsptan = $usuario->cantidad_usuario();
    $reg = $rsptan->fetch_object();
    $reg->nombre;

    ?>

    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <?php

$conn = mysqli_connect("localhost", "root", "", "asistencia");
$conn->set_charset("utf8mb4");





if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $empleado_id = $_SESSION['idusuario'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $motivo = $_POST['motivo'];
    $descripcion = $_POST['descripcion'];
    $estado = 'Pendiente'; // Estado inicial

    // Datos de conexión a la base de datos
    $db_host = 'localhost'; // O el host que estés usando
    $db_user = 'root'; // Tu usuario de MySQL
    $db_password = ''; // Tu contraseña de MySQL (por defecto en XAMPP suele estar vacía)
    $db_name = 'asistencia'; // El nombre de tu base de datos

    // Conectar a la base de datos
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

 
    // Guardar la solicitud
    $sql = "INSERT INTO solicitudes_permisos (idusuario, fecha_inicio, fecha_fin, motivo, estado, descripcion) VALUES ('$empleado_id', '$fecha_inicio', '$fecha_fin', '$motivo', '$estado', '$descripcion')";
    if ($conn->query($sql) === TRUE) {
        // Obtiene el ID de la solicitud recién insertada
        $solicitud_id = $conn->insert_id;

        // Manejo del archivo adjunto
        if (isset($_FILES['adjunto'])) {
            $file_error = $_FILES['adjunto']['error'];
            if ($file_error != 0) {
                
                switch ($file_error) {
                    case UPLOAD_ERR_INI_SIZE:
                        echo "El archivo subido excede la directiva upload_max_filesize en php.ini.<br>";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        echo "El archivo subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML.<br>";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        echo "El archivo subido solo se subió parcialmente.<br>";
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        echo "Falta una carpeta temporal.<br>";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        echo "No se pudo escribir el archivo en el disco.<br>";
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        echo "Una extensión de PHP detuvo la subida del archivo.<br>";
                        break;
                    default:
                        echo "Error desconocido al subir el archivo.<br>";
                        break;
                }
            } else {

                $nombre_adjunto = basename($_FILES['adjunto']['name']);
                                // Ubicación del archivo adjunto
                                $ubicacion_adjunto = $_FILES['adjunto']['tmp_name'];

                                // Crear el directorio de destino si no existe
                                $directorio_destino = "../files/archivos/";
                                if (!is_dir($directorio_destino)) {
                                    mkdir($directorio_destino, 0777, true);
                                }
                
                                // Crear una ruta segura para el archivo
                                $ruta_archivo_adjunto = $directorio_destino . $solicitud_id . "_" . $nombre_adjunto;
                
                                if (move_uploaded_file($ubicacion_adjunto, $ruta_archivo_adjunto)) {
                                    // Actualizar la entrada en la base de datos para incluir la ubicación del archivo adjunto
                                    $sql = "UPDATE solicitudes_permisos SET archivo_adjunto='$ruta_archivo_adjunto' WHERE id=$solicitud_id";
                                    if ($conn->query($sql) === TRUE) {
                                        
                                    } else {
                                        echo "Error al actualizar la solicitud con el archivo adjunto: " . $conn->error;
                                    }
                                } else {
                                    echo "Error al mover el archivo adjunto.";
                                }
                            }
                        } else {
                            echo "No se subió ningún archivo.";
                        }
                
// Obtener la empresa y el área del usuario actual
$id_usuario_actual = $_SESSION['idusuario'];
$sql_usuario = "SELECT empresa, iddepartamento FROM usuarios WHERE idusuario = $id_usuario_actual";
$result_usuario = mysqli_query($conn, $sql_usuario);

if ($result_usuario && mysqli_num_rows($result_usuario) > 0) {
    $row_usuario = mysqli_fetch_assoc($result_usuario);
    $empresa_usuario = $row_usuario['empresa'];
    $id_departamento_usuario = $row_usuario['iddepartamento'];
} else {
    die("No se pudo determinar la empresa y el área del usuario.");
}

// Inicializar arrays para almacenar correos
$emails_teamleader = [];
$emails_gerencia = [];

// Consultar correos de Team Leaders del mismo área y empresa
$sql_teamleader = "SELECT email FROM usuarios WHERE empresa = '$empresa_usuario' AND iddepartamento = $id_departamento_usuario AND idtipousuario = '4'";
$result_teamleader = mysqli_query($conn, $sql_teamleader);

if ($result_teamleader && mysqli_num_rows($result_teamleader) > 0) {
    while ($row_teamleader = mysqli_fetch_assoc($result_teamleader)) {
        $emails_teamleader[] = $row_teamleader['email'];
    }
}

// Consultar correos de Gerentes de la misma empresa
$sql_gerencia = "SELECT email FROM usuarios WHERE empresa = '$empresa_usuario' AND idtipousuario = '7'";
$result_gerencia = mysqli_query($conn, $sql_gerencia);

if ($result_gerencia && mysqli_num_rows($result_gerencia) > 0) {
    while ($row_gerencia = mysqli_fetch_assoc($result_gerencia)) {
        $emails_gerencia[] = $row_gerencia['email'];
    }
}










        // Envío del correo de notificación
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'it@flinslaw.com';
            $mail->Password = 'ZWVV$eF8^VbbvVm%L6K@hTdwpgebMWnPzPssva^1fRKqb1HXjRt%@eu!9B7S';  // Usa la contraseña de aplicación generada
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuración segura para verificación de certificados
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => true,
                    'allow_self_signed' => true
                )
            );

            // Configuración del correo
            $mail->setFrom('it@itl.legal', '**Notificación de Sistema**');
            $mail->addAddress('malena@itl.legal');
           // $mail->addAddress('jose@itl.legal');
           // $mail->addAddress('luismiguel@itl.legal');


    // Añadir correos de Team Leaders al correo
    foreach ($emails_teamleader as $email) {
        $mail->addAddress($email);
    }

    // Añadir correos de Gerentes al correo
    foreach ($emails_gerencia as $email) {
        $mail->addAddress($email);
    }

           $mail->CharSet = 'UTF-8';
            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'TEST';

            // Obtener nombre y apellido de la sesión "Nueva solicitud de permiso";

            
$nombre = '';
$apellido = '';
if (isset($_SESSION['nombre'])) {
    $nombreCompleto = $_SESSION['nombre'];
    $partesNombre = explode(' ', $nombreCompleto);
    $nombre = $partesNombre[0]; // Obtener el primer nombre
}
if (isset($_SESSION['apellidos'])) {
    $apellidosCompleto = $_SESSION['apellidos'];
    $partesApellidos = explode(' ', $apellidosCompleto);
    $apellido = $partesApellidos[0]; // Obtener el primer apellido
}


// Convertir y formatear las fechas
$date_inicio = new DateTime($fecha_inicio);
$fecha_inicio_formateada = $date_inicio->format('d/m/Y');

$date_fin = new DateTime($fecha_fin);
$fecha_fin_formateada = $date_fin->format('d/m/Y');





            $mail->Body    = "Se ha generado una nueva solicitud de permiso.<br><br>
            <b>Nombre Completo:</b> {$nombre} {$apellido}<br>
            <b>Fecha de Inicio:</b> {$fecha_inicio_formateada}<br>
            <b>Fecha de Fin:</b> {$fecha_fin_formateada}<br>
            <b>Motivo:</b> {$motivo}<br>
            <b>Descripción:</b> {$descripcion}<br>
            <b>**POR FAVOR INGRESAR A SISTEMA PARA APROBAR O RECHAZAR SOLICITUD**<b>";
            $mail->AltBody = "Se ha generado una nueva solicitud de permiso.\n\n
            Nombre: {$nombre} {$apellido}\n
            Fecha de Inicio: {$fecha_inicio_formateada}\n
            Fecha de Fin: {$fecha_fin_formateada}\n
            Motivo: {$motivo}\n
            Descripción: {$descripcion}\n
            **POR FAVOR INGRESAR A SISTEMA PARA APROBAR O RECHAZAR SOLICITUD**";
            $mail->send();
            
        } catch (Exception $e) {
            echo "La solicitud no pudo ser enviada. Error de correo: {$mail->ErrorInfo}";
        }
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
// Después de procesar el formulario y antes de redireccionar
echo '<script>alert("La solicitud ha sido enviada y se ha notificado a las personas encargadas."); setTimeout(function(){ window.location.href = "formularioPermiso.php"; }, 1000);</script>';
                    
                    
                }
                ?>
                            <!-- Consulta SQL para obtener las solicitudes del usuario actual -->
                            <?php
                            $id_usuario_actual = $_SESSION['idusuario'];
                            $sql_solicitudes = "SELECT id,
                            revisor1_estado,
                            DATE_FORMAT(fecha_solicitud, '%d/%m/%Y %H:%i:%s') as fecha_solicitud,
                            DATE_FORMAT(fecha_inicio, '%d/%m/%Y') as fecha_inicio,
                            DATE_FORMAT(fecha_fin, '%d/%m/%Y') as fecha_fin,
                            motivo,
                            estado,
                            descripcion,
                            archivo_adjunto,
                            motivo_rechazo
                            FROM solicitudes_permisos WHERE idusuario = $id_usuario_actual";
                            $resultado_solicitudes = mysqli_query($conn, $sql_solicitudes);
                            ?>
                
                            <style>
                                .aprobado {
                                    background-color: #28a745; /* Verde */
                                    color: #ffffff; /* Blanco */
                                    padding: 3px 6px; /* Espacio adicional alrededor del texto */
                                    border: 1px solid #000000; /* Borde negro */
                                    border-radius: 5px; /* Bordes redondeados */
                                    font-weight: bold; /* Negrita */
                                }
                
                                .pendiente {
                                    background-color: #ffc107; /* Amarillo */
                                    color: #ffffff; /* Blanco */
                                    padding: 3px 6px; /* Espacio adicional alrededor del texto */
                                    border: 1px solid #000000; /* Borde negro */
                                    border-radius: 5px; /* Bordes redondeados */
                                    font-weight: bold; /* Negrita */
                                }
                
                                .rechazado {
                                    background-color: #FC0000; /* Rojo */
                                    color: #ffffff; /* Blanco */
                                    padding: 3px 6px; /* Espacio adicional alrededor del texto */
                                    border: 1px solid #000000; /* Borde negro */
                                    border-radius: 5px; /* Bordes redondeados */
                                    font-weight: bold; /* Negrita */
                                }

                                input[type="date"] {
                                text-align: center;
                                }
                
                            </style>
                

                <div style="background-color: white; border: 2px solid #3c8dbc; border-radius: 10px; padding: 20px;">
    <div style="text-align: center;">
        <h3 style="color: #3c8dbc; font-weight: bold; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 28px; text-transform: uppercase; border-bottom: 2px solid #3c8dbc; padding-bottom: 5px; margin-bottom: 20px; display: inline-block;">Enviar nueva solicitud</h3>
    </div>

    <form method="post" action="formularioPermiso.php" enctype="multipart/form-data">
        <div style="margin-bottom: 10px;font-weight: bold;">
            <label for="fecha_inicio" style="display: inline-block; width: 120px;">Fecha de Inicio:</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" required>
        </div>
        <div style="margin-bottom: 10px;font-weight: bold;">
            <label for="fecha_fin" style="display: inline-block; width: 120px;">Fecha de Fin:</label>
            <input type="date" name="fecha_fin" id="fecha_fin" required>
        </div>
        <div style="margin-bottom: 10px;font-weight: bold;">
            <label style="display: inline-block; width: 120px;">Motivo:</label>
            <select id="motivo" name="motivo" onchange="mostrarFormato()" required>
                <option value="" selected hidden>Seleccionar una opción</option>
                <option value="Descanso Médico">Cita Médica</option>
                <option value="Vacaciones">Vacaciones</option>
                <option value="Permiso personal">Permiso personal</option>
                <option value="Otros">Otros</option>
            </select>
            <a id="formato_vacaciones" href="#" style="display: none; margin-left: 10px;">Descargar formato de solicitud</a>
        </div>
        <label style="display: inline-block; width: 200px;">Descripción (Breve detalle):</label>
        <div style="margin-bottom: 10px;font-weight: bold;">
            <textarea name="descripcion" id="descripcion" rows="4" cols="50" required></textarea>
        </div>
        <div style="margin-bottom: 10px;font-weight: bold;">
            <label style="display: inline-block; width: 200px;">Deseas cargar archivos:</label>
            <input type="radio" name="cargar_archivos" id="si" value="si" onclick="mostrarAdjuntos()" required> Sí
            <input type="radio" name="cargar_archivos" id="no" value="no" onclick="ocultarAdjuntos()" required> No
        </div>
        <div id="adjuntos" style="margin-bottom: 10px;font-weight: bold; display: none;">
            <label style="display: inline-block; width: 150px;">Adjuntar Documentos:</label>
            <input type="file" name="adjunto" id="adjunto">
        </div>
        <button type="submit" style="background-color: #3c8dbc; color: white; font-weight: bold; padding: 10px 20px; border: none; border-radius: 5px;">Enviar Solicitud</button>
    </form>
</div>
<script>
function mostrarFormato() {
    var motivo = document.getElementById("motivo").value;
    if (motivo === "Vacaciones") {
        document.getElementById("formato_vacaciones").style.display = "inline-block";
        document.getElementById("formato_vacaciones").href = "../files/formatos/formato_vacaciones.docx";
    } else {
        document.getElementById("formato_vacaciones").style.display = "none";
    }
}
</script>
<script>
    function mostrarAdjuntos() {
        document.getElementById("adjuntos").style.display = "block";
    }

    function ocultarAdjuntos() {
        document.getElementById("adjuntos").style.display = "none";
    }
</script>

<!-- Regla de dos días anticipados -->
<script>
        function obtenerFechaMinima() {
            let fecha = new Date(); // Fecha actual
            let diasParaSumar = 0;

            // Saltar el día actual y comenzar a contar desde el siguiente día
            fecha.setDate(fecha.getDate() + 1);

            // Calcular la fecha mínima permitida (dos días laborales adelante)
            while (diasParaSumar < 2) { // Contar exactamente 2 días laborales
                if (fecha.getDay() !== 0 && fecha.getDay() !== 6) { // 0 = Domingo, 6 = Sábado
                    diasParaSumar++;
                }
                // Avanzar al siguiente día
                fecha.setDate(fecha.getDate() + 1);
            }

            return fecha;
        }

        function formatFecha(fecha) {
            let dia = fecha.getDate().toString().padStart(2, '0');
            let mes = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Los meses empiezan en 0
            let año = fecha.getFullYear();
            return `${año}-${mes}-${dia}`;
        }

        // Establecer fechas mínimas en los campos de fecha
        let fechaMinima = obtenerFechaMinima();
        document.getElementById('fecha_inicio').setAttribute('min', formatFecha(fechaMinima));
        document.getElementById('fecha_fin').setAttribute('min', formatFecha(fechaMinima));

        // Escuchar cambios en el campo de fecha de inicio para ajustar la fecha mínima de fin
        document.getElementById('fecha_inicio').addEventListener('change', function() {
            let fechaInicioSeleccionada = new Date(this.value);
            document.getElementById('fecha_fin').setAttribute('min', formatFecha(fechaInicioSeleccionada));
        });
    </script>



 <br>

<div style="background-color: white; border: 2px solid #3c8dbc; border-radius: 10px; padding: 20px;">
<div style="text-align: center;">
    <h2 style="color: #3c8dbc; font-weight: bold; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 28px; text-transform: uppercase; border-bottom: 2px solid #3c8dbc; padding-bottom: 5px; margin-bottom: 20px; display: inline-block;">Mis solicitudes realizadas</h2>
</div>
                            <table id="solicitudes_table">
                                <thead>
                                    <tr>
                                        <th>Fecha de Solicitud</th>
                                        <th>N° de Solicitud</th>                                    
                                        <th>Fecha de inicio</th>
                                        <th>Fecha de fin</th>
                                        <th>Motivo de Solicitud</th>
                                        <th>Descripción</th>
                                        <th>Archivo Adjuntos</th>
                                        <th>Estado General</th>                                        
                                        <th>Motivo de Rechazo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($solicitud = mysqli_fetch_assoc($resultado_solicitudes)): ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $solicitud["fecha_solicitud"]; ?></td>
                                        <td style="text-align: center;"><strong><?php echo $solicitud["id"]; ?></strong></td>                                                                    
                                        <td style="text-align: center;"><?php echo $solicitud["fecha_inicio"]; ?></td>
                                        <td style="text-align: center;"><?php echo $solicitud["fecha_fin"]; ?></td>
                                        <td style="text-align: center;"><?php echo $solicitud["motivo"]; ?></td>
                                        <td ><?php echo $solicitud["descripcion"]; ?></td>
                                        <td style="text-align: center;">
    <?php 
    // Obtener el nombre del archivo de la ruta
    $ruta_archivo = $solicitud["archivo_adjunto"];
    
    if ($ruta_archivo !== null) {
        $nombre_archivo = basename($ruta_archivo);
        // Generar la URL del archivo adjunto
        $url_archivo = "../files/archivos/" . $nombre_archivo;
        echo '<a href="' . $url_archivo . '" download>' . $nombre_archivo . '</a>';
    } else {
        // Si $ruta_archivo es nulo, mostrar mensaje de que no hay documento
        echo "No hay documento";
    }
    ?>
</td>





                                        <td style="text-align: center;">
                                            <?php 
                                            $estado = $solicitud["estado"]; 
                                            if ($estado == "Aprobado") {
                                                echo '<span class="aprobado">' . $estado . '</span>';
                                            } elseif ($estado == "Pendiente") {
                                                echo '<span class="pendiente">' . $estado . '</span>';
                                            } elseif ($estado == "Rechazado")  {
                                                echo '<span class="rechazado">' . $estado . '</span>';
                                            }
                                            ?>
                                        </td>
                                        <td style="text-align: center;">
<?php
$motivo_rechazo = $solicitud["motivo_rechazo"];
if ($motivo_rechazo !== null) {
    $nombre_archivo = basename($ruta_archivo);
    echo  $solicitud["motivo_rechazo"];;
} else {

    echo "No se rechazo";
}
?>


</td>

                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            </div>                
                            <script>
                                $(document).ready(function() {
                                    $('#solicitudes_table').DataTable();
                                });
                            </script>
                        </section>
                    </div>
                
                <?php
                require 'footer.php';
                }
                ob_end_flush();
                ?>
                
