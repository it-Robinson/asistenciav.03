

    <meta charset="UTF-8">
    <title>Lista de Solicitudes</title>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">

    <!-- DataTables JavaScript -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

    <!-- CSS para el diseño -->
    <style>
        .expandable-content {
            display: none;
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            margin-top: 5px;
        }

        .expandable-content td, .descripcion-texto {
            text-align: left;
            padding-left: 10px;
            font-size: 16px;
        }

        .toggle-button {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
            background: none;
            border: none;
            font-size: 14px;
            font-weight: bold;
        }

        .content-wrapper {
            overflow-x: auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 12px;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 13px;
            
        }

        .aprobado, .pendiente, .rechazado {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
            color: #ffffff;
            text-align: center;
        }

        .aprobado {
            background-color: #28a745;
        }

        .pendiente {
            background-color: #ffc107;
        }

        .rechazado {
            background-color: #FC0000;
        }

        .btn {
            padding: 4px 8px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            border: none;
            width: 100%;
            margin: 2px 0;
            font-weight: bold;
        }

        .btn-approve {
            background-color: #28a745;
            border: 1px solid darkgreen;
            
        }

        .btn-approve:hover {
            background-color: #218838;
        }

        .btn-reject {
            background-color: #DC3545;
            border: 1px solid darkred;
        }

        .btn-reject:hover {
            background-color: #C82333;
        }

        .btn-submit {
            background-color: #007bff;
            border: 1px solid #0056b3;
            margin-top: 5px;
        }

        .btn-submit:hover {
            background-color: #0069d9;
        }

        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 12px;
            resize: vertical;
            margin-top: 5px;
        }

        .comment-section {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>


<?php
// Mostrar todos los errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Activamos almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit;
} else {
    require 'header.php';
    require_once('../modelos/Usuario.php');
    $usuario = new Usuario();
    $rsptan = $usuario->cantidad_usuario();
    $reg = $rsptan->fetch_object();
    $reg->nombre;

    // Conexión a la base de datos
    $conn = mysqli_connect("localhost", "root", "", "asistencia");
    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }
    $conn->set_charset("utf8mb4");

// Obtener el iddepartamento y la empresa del usuario actual
$usuario_id = $_SESSION['idusuario']; // Asumiendo que se guarda el id del usuario en la sesión
$sql_usuario = "SELECT iddepartamento, empresa,idtipousuario FROM usuarios WHERE idusuario = '$usuario_id'";
$result_usuario = mysqli_query($conn, $sql_usuario);
$usuario_data = mysqli_fetch_assoc($result_usuario);
$usuario_departamento = $usuario_data['iddepartamento'];
$usuario_empresa = $usuario_data['empresa'];
$usuario_tipousuario = $usuario_data['idtipousuario'];

// Modificar la consulta SQL para permitir que los usuarios del departamento 9 vean todas las solicitudes
if ($usuario_departamento == 26 || $usuario_tipousuario==1) {
    $sql = "SELECT DATE_FORMAT(s.fecha_solicitud, '%d/%m/%Y %H:%i:%s') as fecha_solicitud,
        s.id,
        u.nombre,
        u.apellidos,
        DATE_FORMAT(s.fecha_inicio, '%d/%m/%Y') as fecha_inicio,
        DATE_FORMAT(s.fecha_fin, '%d/%m/%Y') as fecha_fin,
        s.motivo,
        s.estado,
        s.descripcion,
        s.archivo_adjunto,
        s.revisor1_estado,
        s.revisor2_estado,
        s.revisor3_estado,
        s.revisor4_estado,
        s.revisor1_comentario,
        s.revisor2_comentario,
        s.revisor3_comentario,
        s.revisor4_comentario
    FROM solicitudes_permisos s
    INNER JOIN usuarios u ON s.idusuario = u.idusuario";

} else {
    $sql = "SELECT DATE_FORMAT(s.fecha_solicitud, '%d/%m/%Y %H:%i:%s') as fecha_solicitud,
        s.id,
        u.nombre,
        u.apellidos,
        DATE_FORMAT(s.fecha_inicio, '%d/%m/%Y') as fecha_inicio,
        DATE_FORMAT(s.fecha_fin, '%d/%m/%Y') as fecha_fin,
        s.motivo,
        s.estado,
        s.descripcion,
        s.archivo_adjunto,
        s.revisor1_estado,
        s.revisor2_estado,
        s.revisor3_estado,
        s.revisor4_estado,
        s.revisor1_comentario,
        s.revisor2_comentario,
        s.revisor3_comentario,
        s.revisor4_comentario
    FROM solicitudes_permisos s
    INNER JOIN usuarios u ON s.idusuario = u.idusuario
    WHERE u.iddepartamento = '$usuario_departamento' AND u.empresa = '$usuario_empresa'"; // Filtrar por iddepartamento y empresa del usuario
}

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<div class="content-wrapper">
    <section class="content">
        <div style="background-color: white; border: 2px solid #3c8dbc; border-radius: 10px; padding: 20px;">
            <div style="text-align: center;">
                <h3 style="color: #3c8dbc; font-weight: bold; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 28px; text-transform: uppercase; border-bottom: 2px solid #3c8dbc; padding-bottom: 5px; margin-bottom: 20px; display: inline-block;">Lista de solicitudes</h3>
            </div>
            <table id="solicitudes_table" class="display">
                <thead>
                    <tr>
                    <th style="text-align: center;">N° de Solicitud</th>    
                    <th style="text-align: center;">Fecha de Registro</th>                    
                    <th style="text-align: center;">Nombre</th>
                    <th style="text-align: center;">Fecha de inicio</th>
                    <th style="text-align: center;">Fecha de fin</th>
                    <th style="text-align: center;">Motivo</th>
                    <th style="text-align: center;">Archivo Adjunto</th>
                    <th style="text-align: center;">Descripción</th>
                    <th style="text-align: center;">Estado General</th>
                    <th style="text-align: center;">Acción</th>
                    <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($solicitud = mysqli_fetch_assoc($result)): ?>
                        <?php
                        // Determinar el Estado General basado en los estados de los revisores
                        $estado_general = 'Pendiente'; // Estado por defecto
                        if ($solicitud['revisor1_estado'] == 'Rechazado' || 
                        $solicitud['revisor2_estado'] == 'Rechazado' || 
                        $solicitud['revisor3_estado'] == 'Rechazado' || 
                        $solicitud['revisor4_estado'] == 'Rechazado') {
                        $estado_general = 'Rechazado';
                    } elseif ($solicitud['revisor1_estado'] == 'Aprobado' && 
                              $solicitud['revisor2_estado'] == 'Aprobado' && 
                              $solicitud['revisor3_estado'] == 'Aprobado' && 
                              $solicitud['revisor4_estado'] == 'Aprobado') {
                        $estado_general = 'Aprobado';
                    }

                        // Verificar si todos los revisores han tomado una decisión
                        $todos_revisores_decididos = (
                            $solicitud['revisor1_estado'] != 'Pendiente' &&
                            $solicitud['revisor2_estado'] != 'Pendiente' &&
                            $solicitud['revisor3_estado'] != 'Pendiente' &&
                            $solicitud['revisor4_estado'] != 'Pendiente'  // Agregar revisor 4
                        );

                        // Verificar si el usuario actual ya realizó una acción
                        $nombreUsuario = $_SESSION['nombre'] . ' ' . $_SESSION['apellidos'];
                        $accion_realizada_usuario = (
                            (($solicitud['revisor1_estado'] != 'Pendiente') && strpos($solicitud['revisor1_comentario'], $nombreUsuario) !== false) ||
                            (($solicitud['revisor2_estado'] != 'Pendiente') && strpos($solicitud['revisor2_comentario'], $nombreUsuario) !== false) ||
                            (($solicitud['revisor3_estado'] != 'Pendiente') && strpos($solicitud['revisor3_comentario'], $nombreUsuario) !== false) ||
                            (($solicitud['revisor4_estado'] != 'Pendiente') && strpos($solicitud['revisor4_comentario'], $nombreUsuario) !== false)
                        );
                        ?>
                        <tr>                            
                            <td style="font-size: 14px;"><strong><?php echo $solicitud["id"]; ?></strong></td>
                            <td style="font-size: 14px;"><?php echo $solicitud["fecha_solicitud"]; ?></td>
                            <td style="font-size: 14px;"><?php echo $solicitud["nombre"] . ' ' . $solicitud["apellidos"]; ?></td>
                            <td style="font-size: 14px;"><?php echo $solicitud["fecha_inicio"]; ?></td>
                            <td style="font-size: 14px;"><?php echo $solicitud["fecha_fin"]; ?></td>
                            <td style="font-size: 14px;"><?php echo $solicitud["motivo"]; ?></td>
                            <td style="font-size: 14px;">
                                <?php 
                                $ruta_archivo = $solicitud["archivo_adjunto"];
                                if ($ruta_archivo !== null) {
                                    $nombre_archivo = basename($ruta_archivo);
                                    $url_archivo = "../files/archivos/" . $nombre_archivo;
                                    echo '<a href="' . $url_archivo . '" download>' . $nombre_archivo . '</a>';
                                } else {
                                    echo "No hay documento";
                                }
                                ?>
                            </td>
                            <td>
                                <button class="toggle-button" onclick="toggleDescription(this)">Ver Descripción</button>
                            </td>
                            <td><span class="<?php echo strtolower($estado_general); ?>"><?php echo $estado_general; ?></span></td>
                            <td style="font-size: 14px;">
                                <?php 
                                // Verificación de acción realizada para todos los revisores o si el usuario actual ya realizó una acción
                                if ($todos_revisores_decididos || $accion_realizada_usuario) {
                                    echo '<strong>Acción Realizada</strong>';
                                } else { 
                                ?>
<div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
    <form action="aprobar_permiso.php" method="GET" style="margin: 5px 0; width: 100%; display: flex; justify-content: center;">
        <input type="hidden" name="id" value="<?php echo $solicitud['id']; ?>">
        <input type="hidden" name="revisor" value="4">
        <button style="font-weight: bold; display: flex; align-items: center; justify-content: center;" type="submit" class="btn btn-approve">Aprobar</button>
    </form>
    <!-- Botón de Rechazar -->
    <form id="rejectForm" action="rechazar_permiso.php" method="POST" style="margin: 5px 0; width: 100%; display: flex; justify-content: center;">
        <input type="hidden" name="id" value="<?php echo $solicitud['id']; ?>">
        <input type="hidden" name="motivo_rechazo" id="motivoRechazoInput">
        <button type="button" class="btn btn-reject" style="font-weight: bold; display: flex; align-items: center; justify-content: center;" onclick="showRejectionModal()">Rechazar</button>
    </form>
    <!-- Modal para motivo de rechazo -->
    <div id="rejectionModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.4);">
        <div style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 300px; text-align: center;">
            <h4>Motivo del Rechazo</h4>
            <textarea id="motivoRechazoText" placeholder="Escribe el motivo del rechazo..." style="width: 100%; height: 100px;"></textarea>
            <br><br>
            <button class="btn btn-submit" onclick="submitRejection()">Enviar</button>
            <button class="btn btn-reject" onclick="closeRejectionModal()">Cancelar</button>
        </div>
    </div>
</div>
                                <?php } ?>
                            </td>
                            <td>
                                <button class="toggle-button" onclick="toggleDetails(this)">Ver Detalles</button>
                            </td>
                        </tr>
                        <!-- Fila desplegable para mostrar la descripción -->
                        <tr class="description-content" style="display: none;">
                            <td colspan="11" class="descripcion-texto">
                                <strong>Descripción:</strong> <?php echo $solicitud["descripcion"]; ?>
                            </td>
                        </tr>
                        <tr class="expandable-content">
                            <td colspan="11" class="descripcion-texto">
                                <strong>Estado Revisor 1:</strong> <span class="<?php echo strtolower($solicitud['revisor1_estado']); ?>"><?php echo $solicitud["revisor1_estado"]; ?></span><br>
                                <strong>Comentario Revisor 1:</strong> <?php echo $solicitud["revisor1_comentario"]; ?><br><br>
                                <strong>Estado Revisor 2:</strong> <span class="<?php echo strtolower($solicitud['revisor2_estado']); ?>"><?php echo $solicitud["revisor2_estado"]; ?></span><br>
                                <strong>Comentario Revisor 2:</strong> <?php echo $solicitud["revisor2_comentario"]; ?><br><br>
                                <strong>Estado Revisor 3:</strong> <span class="<?php echo strtolower($solicitud['revisor3_estado']); ?>"><?php echo $solicitud["revisor3_estado"]; ?></span><br>
                                <strong>Comentario Revisor 3:</strong> <?php echo $solicitud["revisor3_comentario"]; ?><br><br>
                                <strong>Estado Revisor 4:</strong> <span class="<?php echo strtolower($solicitud['revisor4_estado']); ?>"><?php echo $solicitud["revisor4_estado"]; ?></span><br>
                                <strong>Comentario Revisor 4:</strong> <?php echo $solicitud["revisor4_comentario"]; ?>

                            </td>
                        </tr>

                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
function toggleDescription(button) {
    var row = $(button).closest('tr');
    var descriptionRow = row.next('.description-content');
    var detailsRow = descriptionRow.next('.expandable-content');

    detailsRow.hide(); // Siempre ocultar detalles al mostrar descripción

    if (descriptionRow.is(':visible')) {
        descriptionRow.hide();
        $(button).text('Ver Descripción');
    } else {
        descriptionRow.show();
        $(button).text('Ocultar Descripción');
    }
}

function toggleDetails(button) {
    var row = $(button).closest('tr');
    var descriptionRow = row.next('.description-content');
    var detailsRow = descriptionRow.next('.expandable-content');

    descriptionRow.hide(); // Siempre ocultar descripción al mostrar detalles

    if (detailsRow.is(':visible')) {
        detailsRow.hide();
        $(button).text('Ver Detalles');
    } else {
        detailsRow.show();
        $(button).text('Ocultar Detalles');
    }
}

$(document).ready(function() {
    $('#solicitudes_table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "pageLength": 10,
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar:",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "rowCallback": function(row, data, index) {
            // Ocultar las filas desplegables
            if ($(row).hasClass('description-content') || $(row).hasClass('expandable-content')) {
                $(row).hide();
            }
        }
    });
});

function showRejectionModal() {
    // Mostrar el modal
    document.getElementById('rejectionModal').style.display = 'block';
}

function closeRejectionModal() {
    // Ocultar el modal
    document.getElementById('rejectionModal').style.display = 'none';
}

function submitRejection() {
    var motivo = document.getElementById('motivoRechazoText').value.trim();

    if (motivo === '') {
        alert('Por favor, escribe un motivo para el rechazo.');
        return;
    }

    // Asignar el motivo al input hidden del formulario
    document.getElementById('motivoRechazoInput').value = motivo;

    // Enviar el formulario
    document.getElementById('rejectForm').submit();
}
</script>
<?php
    require 'footer.php';
}
ob_end_flush();
?>


