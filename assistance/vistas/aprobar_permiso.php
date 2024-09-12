<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Conectar a la base de datos
$conn = mysqli_connect("localhost", "root", "", "asistencia");
$conn->set_charset("utf8mb4");
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Obtener parámetros de la solicitud
$id = $_GET['id'];
$nombreAprobador = $_SESSION['nombre']; 
$apellidoAprobador = $_SESSION['apellidos']; 
$completoAprobador = $nombreAprobador . ' ' . $apellidoAprobador;
$comentario_adicional = isset($_POST['comentario']) ? mysqli_real_escape_string($conn, $_POST['comentario']) : '';

// Determinar cuál revisor está actuando
$revisor = 0;

// Consulta para obtener el estado actual de los revisores
$sql_check = "SELECT revisor1_estado, revisor1_comentario, revisor2_estado, revisor2_comentario, revisor3_estado, revisor3_comentario, revisor4_estado, revisor4_comentario  FROM solicitudes_permisos WHERE id = '$id'";
$result_check = mysqli_query($conn, $sql_check);
$solicitud = mysqli_fetch_assoc($result_check);

// Verificar qué revisor es el siguiente en aprobar
if ($solicitud['revisor1_estado'] == 'Pendiente') {
    $revisor = 1;
} elseif ($solicitud['revisor2_estado'] == 'Pendiente') {
    $revisor = 2;
} elseif ($solicitud['revisor3_estado'] == 'Pendiente') {
    $revisor = 3;
} elseif ($solicitud['revisor4_estado'] == 'Pendiente') {
    $revisor = 4;    
} else {
    die("Todos los revisores ya han aprobado o rechazado esta solicitud.");
}

// Componer el comentario final
$comentario_final = "Aprobado por $completoAprobador";
if (!empty($comentario_adicional)) {
    $comentario_final .= ". Comentario: $comentario_adicional";
}

// Actualizar la base de datos con la aprobación y comentario del revisor correspondiente
$estado_campo = "revisor{$revisor}_estado";
$comentario_campo = "revisor{$revisor}_comentario";
$sql = "UPDATE solicitudes_permisos SET $estado_campo = 'Aprobado', $comentario_campo = '$comentario_final' WHERE id='$id'";

if (mysqli_query($conn, $sql)) {
    echo "Permiso aprobado correctamente.";

    // Volver a consultar los estados de los revisores después de la actualización
    $sql_check = "SELECT revisor1_estado, revisor1_comentario, revisor2_estado, revisor2_comentario, revisor3_estado, revisor3_comentario, revisor4_estado, revisor4_comentario FROM solicitudes_permisos WHERE id = '$id'";
    $result_check = mysqli_query($conn, $sql_check);
    $solicitud = mysqli_fetch_assoc($result_check);

    // Verificar si todos los revisores han aprobado
    $revisoresAprobados = 0;
    if ($solicitud['revisor1_estado'] == 'Aprobado') $revisoresAprobados++;
    if ($solicitud['revisor2_estado'] == 'Aprobado') $revisoresAprobados++;
    if ($solicitud['revisor3_estado'] == 'Aprobado') $revisoresAprobados++;
    if ($solicitud['revisor4_estado'] == 'Aprobado') $revisoresAprobados++;

    // Estado del proceso
    $estado_final = "Pendiente";
    if ($revisoresAprobados === 4) {
        $estado_final = "Aprobado";
    }

    // Actualizar el estado general de la solicitud
    $sql_update_estado = "UPDATE solicitudes_permisos SET estado = '$estado_final' WHERE id = '$id'";
    mysqli_query($conn, $sql_update_estado);

    // Obtener información de la solicitud y del empleado para el correo
    $sql_info = "SELECT s.revisor1_comentario, s.revisor2_comentario, s.revisor3_comentario,s.revisor4_comentario,  u.email, u.nombre, u.apellidos, s.fecha_inicio, s.fecha_fin, s.motivo, s.descripcion 
                 FROM solicitudes_permisos s 
                 JOIN usuarios u ON s.idusuario = u.idusuario 
                 WHERE s.id = '$id'";
    $result = mysqli_query($conn, $sql_info);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];
        $nombre = isset($row['nombre']) ? $row['nombre'] : '';
        $apellidos = isset($row['apellidos']) ? $row['apellidos'] : '';
        $fecha_inicio = $row['fecha_inicio'];
        $fecha_fin = $row['fecha_fin'];
        $motivo = $row['motivo'];
        $descripcion = $row['descripcion'];
        $revisor1_comentario = $row['revisor1_comentario'];
        $revisor2_comentario = $row['revisor2_comentario'];
        $revisor3_comentario = $row['revisor3_comentario'];
        $revisor4_comentario = $row['revisor4_comentario'];
        // Preparar el correo de notificación
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
            $mail->setFrom('it@itl.legal', 'Notificaciones');
            $mail->addAddress($email, "{$nombre} {$apellidos}");
            $mail->CharSet = 'UTF-8';

            // Convertir y formatear las fechas
            $date_inicio = new DateTime($fecha_inicio);
            $fecha_inicio_formateada = $date_inicio->format('d/m/Y');
            $date_fin = new DateTime($fecha_fin);
            $fecha_fin_formateada = $date_fin->format('d/m/Y');

            // Asignar estilos de botón a los estados
            $estado_final_coloreado = $estado_final === "Aprobado" ? "<span style='color: white; background-color: green; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Aprobado</span>" : "<span style='color: white; background-color: #f0ad4e; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Pendiente</span>";
            $revisor1_estado_coloreado = $solicitud['revisor1_estado'] === "Aprobado" ? "<span style='color: white; background-color: green; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Aprobado</span>" : "<span style='color: white; background-color: #f0ad4e; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Pendiente</span>";
            $revisor2_estado_coloreado = $solicitud['revisor2_estado'] === "Aprobado" ? "<span style='color: white; background-color: green; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Aprobado</span>" : "<span style='color: white; background-color: #f0ad4e; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Pendiente</span>";
            $revisor3_estado_coloreado = $solicitud['revisor3_estado'] === "Aprobado" ? "<span style='color: white; background-color: green; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Aprobado</span>" : "<span style='color: white; background-color: #f0ad4e; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Pendiente</span>";
            $revisor4_estado_coloreado = $solicitud['revisor4_estado'] === "Aprobado" ? "<span style='color: white; background-color: green; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Aprobado</span>" : "<span style='color: white; background-color: #f0ad4e; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Pendiente</span>";
            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Actualización de solicitud de permiso';
            $mail->Body    = "Hola {$nombre} {$apellidos},<br><br>
                              Tu solicitud de permiso actualmente está en estado: <b>{$estado_final_coloreado}</b>.<br><br>
                              <b>Estado de los revisores:</b><br>
                              Revisor 1: {$revisor1_estado_coloreado} - Comentario: {$revisor1_comentario}<br><br>
                              Revisor 2: {$revisor2_estado_coloreado} - Comentario: {$revisor2_comentario}<br><br>
                              Revisor 3: {$revisor3_estado_coloreado} - Comentario: {$revisor3_comentario}<br><br>
                              Revisor 4: {$revisor4_estado_coloreado} - Comentario: {$revisor4_comentario}<br><br>";

            if ($estado_final === "Aprobado") {
                $mail->Body .= "Tu solicitud ha sido completamente aprobada por los cuatro revisores.<br>";
            } else {
                $mail->Body .= "Tu solicitud aún está en proceso de revisión. Te notificaremos una vez que todos los revisores hayan completado su revisión.<br>";
            }

            $mail->Body .= "<br>Saludos,<br>Equipo de Recursos Humanos";

            // Texto alternativo sin HTML
            $mail->AltBody = "Hola {$nombre} {$apellidos},\n\n
                              Tu solicitud de permiso actualmente está en estado: {$estado_final}.\n\n
                              Estado de los revisores:\n
                              Revisor 1: {$solicitud['revisor1_estado']} - Comentario: {$revisor1_comentario}\n\n
                              Revisor 2: {$solicitud['revisor2_estado']} - Comentario: {$revisor2_comentario}\n\n
                              Revisor 3: {$solicitud['revisor3_estado']} - Comentario: {$revisor3_comentario}\n\n
                              Revisor 4: {$solicitud['revisor4_estado']} - Comentario: {$revisor3_comentario}\n\n";

            if ($estado_final === "Aprobado") {
                $mail->AltBody .= "Tu solicitud ha sido completamente aprobada por los tres revisores.\n";
            } else {
                $mail->AltBody .= "Tu solicitud aún está en proceso de revisión. Te notificaremos una vez que todos los revisores hayan completado su revisión.\n";
            }

            $mail->AltBody .= "\nSaludos,\nEquipo de Recursos Humanos";

            $mail->send();
            echo 'Se ha enviado una notificación por correo al empleado.';
        } catch (Exception $e) {
            echo "La notificación por correo no pudo ser enviada. Error de correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "No se encontró la información de la solicitud.";
    }
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

header("Location: ListadoPermisos.php");
exit;
?>
