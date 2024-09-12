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

// Debug: Verificar si los datos están llegando
if (!isset($_POST['id']) || !isset($_POST['motivo_rechazo'])) {
    echo "ID de solicitud o motivo de rechazo no especificado.";
    var_dump($_POST);
    exit;
}

// Obtener parámetros de la solicitud
$id = mysqli_real_escape_string($conn, $_POST['id']);
$nombreAprobador = $_SESSION['nombre']; 
$apellidoAprobador = $_SESSION['apellidos']; 
$completoAprobador = $nombreAprobador . ' ' . $apellidoAprobador;
$comentario_adicional = isset($_POST['comentario']) ? mysqli_real_escape_string($conn, $_POST['comentario']) : '';
$motivo_rechazo = mysqli_real_escape_string($conn, $_POST['motivo_rechazo']);

// Consulta para obtener el estado actual de los revisores
$sql_check = "SELECT revisor1_estado, revisor2_estado, revisor3_estado, revisor4_estado  FROM solicitudes_permisos WHERE id = '$id'";
$result_check = mysqli_query($conn, $sql_check);
$solicitud = mysqli_fetch_assoc($result_check);

// Componer el comentario final de rechazo
$comentario_final = "Rechazado por $completoAprobador";
if (!empty($comentario_adicional)) {
    $comentario_final .= ". Comentario: $comentario_adicional";
}

// Crear consulta para actualizar solo los revisores con estado "Pendiente"
$updates = [];
if ($solicitud['revisor1_estado'] == 'Pendiente') {
    $updates[] = "revisor1_estado = 'Rechazado', revisor1_comentario = '$comentario_final'";
}
if ($solicitud['revisor2_estado'] == 'Pendiente') {
    $updates[] = "revisor2_estado = 'Rechazado', revisor2_comentario = '$comentario_final'";
}
if ($solicitud['revisor3_estado'] == 'Pendiente') {
    $updates[] = "revisor3_estado = 'Rechazado', revisor3_comentario = '$comentario_final'";
}
if ($solicitud['revisor4_estado'] == 'Pendiente') {
    $updates[] = "revisor4_estado = 'Rechazado', revisor4_comentario = '$comentario_final'";
}
// Agregar el motivo de rechazo y actualizar el estado general de la solicitud
$updates[] = "motivo_rechazo = '$motivo_rechazo'";
$updates[] = "estado = 'Rechazado'";

// Concatenar todas las actualizaciones en una sola consulta SQL
$sql = "UPDATE solicitudes_permisos SET " . implode(", ", $updates) . " WHERE id = '$id'";

if (mysqli_query($conn, $sql)) {
    echo "Permiso rechazado correctamente.";

    // Obtener los estados actualizados de los revisores después de la actualización
    $sql_check_updated = "SELECT revisor1_estado, revisor1_comentario, revisor2_estado, revisor2_comentario, revisor3_estado, revisor3_comentario, revisor4_estado, revisor4_comentario FROM solicitudes_permisos WHERE id = '$id'";
    $result_check_updated = mysqli_query($conn, $sql_check_updated);
    $solicitud_updated = mysqli_fetch_assoc($result_check_updated);

    // Asignar estilos de botón a los estados
    $revisor1_estado_coloreado = $solicitud_updated['revisor1_estado'] === "Rechazado" ? "<span style='color: white; background-color: red; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Rechazado</span>" : "<span style='color: white; background-color: green; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Aprobado</span>";
    $revisor2_estado_coloreado = $solicitud_updated['revisor2_estado'] === "Rechazado" ? "<span style='color: white; background-color: red; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Rechazado</span>" : "<span style='color: white; background-color: green; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Aprobado</span>";
    $revisor3_estado_coloreado = $solicitud_updated['revisor3_estado'] === "Rechazado" ? "<span style='color: white; background-color: red; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Rechazado</span>" : "<span style='color: white; background-color: green; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Aprobado</span>";
    $revisor4_estado_coloreado = $solicitud_updated['revisor4_estado'] === "Rechazado" ? "<span style='color: white; background-color: red; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Rechazado</span>" : "<span style='color: white; background-color: green; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Aprobado</span>";
    // Obtener información de la solicitud y del empleado para el correo
    $sql_info = "SELECT s.revisor1_comentario, s.revisor2_comentario, s.revisor3_comentario, s.revisor4_comentario,u.email, u.nombre, u.apellidos, s.fecha_inicio, s.fecha_fin, s.motivo, s.descripcion 
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
        $revisor4_comentario = $row['revisor3_comentario'];

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

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Actualización de solicitud de permiso';
            $mail->Body    = "Hola {$nombre} {$apellidos},<br><br>
                              Lamentamos informarte que tu solicitud de permiso ha sido <b><span style='color: white; background-color: red; border-radius: 5px; padding: 3px 8px; display: inline-block;'>Rechazada</span></b>.<br><br>
                              <b>Motivo del Rechazo:</b> {$motivo_rechazo}<br><br>
                              <b>Comentarios de los revisores:</b><br>
                              Revisor 1: {$revisor1_estado_coloreado} - Comentario: {$revisor1_comentario}<br><br>
                              Revisor 2: {$revisor2_estado_coloreado} - Comentario: {$revisor2_comentario}<br><br>
                              Revisor 3: {$revisor3_estado_coloreado} - Comentario: {$revisor3_comentario}<br><br>
                              Revisor 4: {$revisor4_estado_coloreado} - Comentario: {$revisor4_comentario}<br><br>                              
                              <br>Saludos,<br>Equipo de Recursos Humanos";

            // Texto alternativo sin HTML
            $mail->AltBody = "Hola {$nombre} {$apellidos},\n\n
                              Lamentamos informarte que tu solicitud de permiso ha sido Rechazada.\n\n
                              Motivo del Rechazo: {$motivo_rechazo}\n\n
                              Comentarios de los revisores:\n
                              Revisor 1: Comentario: {$revisor1_comentario}\n\n
                              Revisor 2: Comentario: {$revisor2_comentario}\n\n
                              Revisor 3: Comentario: {$revisor3_comentario}\n\n
                              Revisor 4: Comentario: {$revisor4_comentario}\n\n
                              \nSaludos,\nEquipo de Recursos Humanos";

            $mail->send();
            echo 'Se ha enviado una notificación por correo al empleado.';
        } catch (Exception $e) {
            echo "La notificación por correo no pudo ser enviada. Error de correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "No se encontró la información de la solicitud.";
    }
} else {
    echo "Error en la actualización de la solicitud: " . mysqli_error($conn);
}

header("Location: ListadoPermisos.php");
exit;
?>
