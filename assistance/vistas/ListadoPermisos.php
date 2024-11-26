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

    .expandable-content td,
    .descripcion-texto {
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
    overflow-x: auto; /* Asegura que permita desplazamiento horizontal */
    padding: 20px 20px 0 20px; /* Ajusta los paddings según lo necesario */
    }


    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    th,
    td {
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

    .aprobado,
    .pendiente,
    .rechazado {
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
<style>
    .empresa-claimpay {
        background-color: #061a63;
        color: white;
        padding: 3px 4px;
        border-radius: 4px;
        font-weight: bold;
    }

    .empresa-itl {
        background-color: #d9a44e;
        /* Mostaza */
        color: white;
        padding: 3px 6px;
        border-radius: 5px;
        font-weight: bold;
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

    // Modificar la consulta SQL para permitir que los usuarios del departamento 26 vean todas las solicitudes
    if ($usuario_departamento == 26 || $usuario_tipousuario == 1) {
        $sql = "SELECT DATE_FORMAT(s.fecha_solicitud, '%d/%m/%Y %H:%i:%s') as fecha_solicitud,
    s.id,
    d.nombre as departamentos,
    u.nombre,
    u.empresa,
    u.apellidos,
    DATE_FORMAT(s.fecha_inicio, '%d/%m/%Y') as fecha_inicio,
    DATE_FORMAT(s.fecha_fin, '%d/%m/%Y') as fecha_fin,
    s.motivo,
    s.motivo_rechazo,
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
    s.revisor4_comentario,
    d.nombre as departamento
FROM solicitudes_permisos s
INNER JOIN usuarios u ON s.idusuario = u.idusuario
INNER JOIN departamento d ON u.iddepartamento = d.iddepartamento";

    } else {
        $sql = "SELECT DATE_FORMAT(s.fecha_solicitud, '%d/%m/%Y %H:%i:%s') as fecha_solicitud,
        s.id,
        d.nombre as departamentos,
        u.nombre,
        u.apellidos,
        DATE_FORMAT(s.fecha_inicio, '%d/%m/%Y') as fecha_inicio,
        DATE_FORMAT(s.fecha_fin, '%d/%m/%Y') as fecha_fin,
        s.motivo,
        s.motivo_rechazo,
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
    INNER JOIN departamento d ON u.iddepartamento = d.iddepartamento
    WHERE u.iddepartamento = '$usuario_departamento' AND u.empresa = '$usuario_empresa'"; // Filtrar por iddepartamento y empresa del usuario
    }

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Error en la consulta: " . mysqli_error($conn));
    }
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <div class="content-wrapper">
        <!-- Sección de Solicitudes Pendientes -->
        <div style="background-color: white; border: 2px solid #3c8dbc; border-radius: 10px; padding: 20px;">
        <div style="text-align: center;">
            <h3
                style="color: #ffc107; font-weight: bold; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: center; text-transform: uppercase; margin-bottom: 20px;">
                Solicitudes Pendientes
            </h3>
            </div>
            <table id="solicitudes_table2" border="1" style="width: 100%; border-collapse: collapse; text-align: center;">
                <thead>
                    <tr>
                        <th style="text-align: center;">N° de Solicitud</th>
                        <th style="text-align: center;">Fecha de Registro</th>
                        <th style="text-align: center;">Área</th>
                        <th style="text-align: center;">Empresa</th>
                        <th style="text-align: center;">Nombre</th>
                        <th style="text-align: center;">Fecha de inicio</th>
                        <th style="text-align: center;">Fecha de fin</th>
                        <th style="text-align: center;">Motivo</th>
                        <th style="text-align: center;">Archivo Adjunto</th>
                        <th style="text-align: center;">Descripción</th>
                        <th style="text-align: center;">Estado General</th>
                        <th style="text-align: center;">Acción</th>
                        <th style="text-align: center;">Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    mysqli_data_seek($result, 0); // Reinicia el puntero de los resultados
                    while ($solicitud = mysqli_fetch_assoc($result)) {
                        $todos_revisores_decididos = (
                            $solicitud['revisor1_estado'] != 'Pendiente' &&
                            $solicitud['revisor2_estado'] != 'Pendiente' &&
                            $solicitud['revisor3_estado'] != 'Pendiente' &&
                            $solicitud['revisor4_estado'] != 'Pendiente'
                        );

                        $accion_realizada_usuario = (
                            (($solicitud['revisor1_estado'] != 'Pendiente') && strpos($solicitud['revisor1_comentario'] ?? '', $_SESSION['nombre']) !== false) ||
                            (($solicitud['revisor2_estado'] != 'Pendiente') && strpos($solicitud['revisor2_comentario'] ?? '', $_SESSION['nombre']) !== false) ||
                            (($solicitud['revisor3_estado'] != 'Pendiente') && strpos($solicitud['revisor3_comentario'] ?? '', $_SESSION['nombre']) !== false) ||
                            (($solicitud['revisor4_estado'] != 'Pendiente') && strpos($solicitud['revisor4_comentario'] ?? '', $_SESSION['nombre']) !== false)
                        );

                        // Filtrar las solicitudes pendientes (sin acciones realizadas)
                        if (!$todos_revisores_decididos && !$accion_realizada_usuario) {
                            ?>
                            <tr>
                                <td style="font-size: 14px;"><strong><?php echo $solicitud["id"]; ?></strong></td>
                                <td style="font-size: 14px;"><?php echo $solicitud["fecha_solicitud"]; ?></td>
                                <td style="font-size: 14px;"><?php echo $solicitud["departamentos"]; ?></td>
                                <td style="font-size: 14px;">
                                    <?php
                                    $empresa = $solicitud["empresa"];
                                    if ($empresa === "Claimpay") {
                                        echo '<span class="empresa-claimpay">' . htmlspecialchars($empresa) . '</span>';
                                    } elseif ($empresa === "ITL") {
                                        echo '<span class="empresa-itl">' . htmlspecialchars($empresa) . '</span>';
                                    } else {
                                        echo htmlspecialchars($empresa);
                                    }
                                    ?>
                                </td>
                                <td style="font-size: 14px;"><?php echo $solicitud["nombre"] . ' ' . $solicitud["apellidos"]; ?>
                                </td>
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
                                    <button class="toggle-button" onclick="showDescriptionModal(this)"
                                        data-info="<?php echo htmlspecialchars($solicitud['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">Ver
                                        Descripción</button>
                                </td>
                                <td><span class="pendiente">Pendiente</span></td>
                                <td>
                                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                                        <form action="aprobar_permiso.php" method="GET"
                                            style="margin: 5px 0; width: 100%; display: flex; justify-content: center;">
                                            <input type="hidden" name="id" value="<?php echo $solicitud['id']; ?>">
                                            <input type="hidden" name="revisor" value="4">
                                            <button
                                                style="font-weight: bold; display: flex; align-items: center; justify-content: center;"
                                                type="submit" class="btn btn-approve">Aprobar</button>
                                        </form>
                                        <form id="rejectForm" action="rechazar_permiso.php" method="POST"
                                            style="margin: 5px 0; width: 100%; display: flex; justify-content: center;">
                                            <input type="hidden" name="id" value="<?php echo $solicitud['id']; ?>">
                                            <input type="hidden" name="motivo_rechazo" id="motivoRechazoInput">
                                            <button type="button" class="btn btn-reject"
                                                style="font-weight: bold; display: flex; align-items: center; justify-content: center;"
                                                onclick="showRejectionModal()">Rechazar</button>
                                        </form>
                                        <div id="rejectionModal"
                                            style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.4);">
                                            <div
                                                style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 300px; text-align: center;">
                                                <h4>Motivo del Rechazo</h4>
                                                <textarea id="motivoRechazoText" placeholder="Escribe el motivo del rechazo..."
                                                    style="width: 100%; height: 100px;"></textarea>
                                                <br><br>
                                                <button class="btn btn-submit" onclick="submitRejection()">Enviar</button>
                                                <button class="btn btn-reject" onclick="closeRejectionModal()">Cancelar</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button class="toggle-button" onclick="showDetailsModal(this)"
                                        data-revisor1-estado="<?php echo addslashes($solicitud['revisor1_estado'] ?? ''); ?>"
                                        data-revisor1-comentario="<?php echo addslashes($solicitud['revisor1_comentario'] ?? ''); ?>"
                                        data-revisor2-estado="<?php echo addslashes($solicitud['revisor2_estado'] ?? ''); ?>"
                                        data-revisor2-comentario="<?php echo addslashes($solicitud['revisor2_comentario'] ?? ''); ?>"
                                        data-revisor3-estado="<?php echo addslashes($solicitud['revisor3_estado'] ?? ''); ?>"
                                        data-revisor3-comentario="<?php echo addslashes($solicitud['revisor3_comentario'] ?? ''); ?>"
                                        data-revisor4-estado="<?php echo addslashes($solicitud['revisor4_estado'] ?? ''); ?>"
                                        data-revisor4-comentario="<?php echo addslashes($solicitud['revisor4_comentario'] ?? ''); ?>"
                                        data-motivo-rechazo="<?php echo addslashes($solicitud['motivo_rechazo'] ?? ''); ?>">Ver
                                        Detalles</button>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>

            </table>
        </div>
        <br>
        <section class="content">
            <!-- Encabezado General -->
            <div style="background-color: white; border: 2px solid #3c8dbc; border-radius: 10px; padding: 20px;">
                <div style="text-align: center;">
                    <h3
                        style="color: #3c8dbc; font-weight: bold; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 28px; text-transform: uppercase; border-bottom: 2px solid #3c8dbc; padding-bottom: 5px; margin-bottom: 20px; display: inline-block;">
                        Lista de Solicitudes
                    </h3>

                    <!-- Sección de Solicitudes con Acciones Realizadas -->
                    <div
                        style="background-color: white; border: 1px solid #ccc; border-radius: 10px; padding: 20px; margin-bottom: 30px;">

                        <div style="margin-bottom: 20px; text-align: center;">
                            <button id="exportExcel" class="btn btn-success">Exportar a Excel</button>
                        </div>
                        <table id="solicitudes_table" border="1"
                            style="width: 100%; border-collapse: collapse; text-align: center;">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">N° de Solicitud</th>
                                    <th style="text-align: center;">Fecha de Registro</th>
                                    <th style="text-align: center;">Área</th>
                                    <th style="text-align: center;">Empresa</th>
                                    <th style="text-align: center;">Nombre</th>
                                    <th style="text-align: center;">Fecha de inicio</th>
                                    <th style="text-align: center;">Fecha de fin</th>
                                    <th style="text-align: center;">Motivo</th>
                                    <th style="text-align: center;">Archivo Adjunto</th>
                                    <th style="text-align: center;">Descripción</th>
                                    <th style="text-align: center;">Estado General</th>
                                    <th style="text-align: center;">Acción</th>
                                    <th style="text-align: center;">Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                mysqli_data_seek($result, 0); // Reinicia el puntero de los resultados
                                while ($solicitud = mysqli_fetch_assoc($result)) {
                                    $todos_revisores_decididos = (
                                        $solicitud['revisor1_estado'] != 'Pendiente' &&
                                        $solicitud['revisor2_estado'] != 'Pendiente' &&
                                        $solicitud['revisor3_estado'] != 'Pendiente' &&
                                        $solicitud['revisor4_estado'] != 'Pendiente'
                                    );

                                    $accion_realizada_usuario = (
                                        (($solicitud['revisor1_estado'] != 'Pendiente') && strpos($solicitud['revisor1_comentario'] ?? '', $_SESSION['nombre']) !== false) ||
                                        (($solicitud['revisor2_estado'] != 'Pendiente') && strpos($solicitud['revisor2_comentario'] ?? '', $_SESSION['nombre']) !== false) ||
                                        (($solicitud['revisor3_estado'] != 'Pendiente') && strpos($solicitud['revisor3_comentario'] ?? '', $_SESSION['nombre']) !== false) ||
                                        (($solicitud['revisor4_estado'] != 'Pendiente') && strpos($solicitud['revisor4_comentario'] ?? '', $_SESSION['nombre']) !== false)
                                    );

                                    if ($todos_revisores_decididos || $accion_realizada_usuario) {
                                        ?>
                                        <tr>
                                            <td style="font-size: 14px;"><strong><?php echo $solicitud["id"]; ?></strong></td>
                                            <td style="font-size: 14px;"><?php echo $solicitud["fecha_solicitud"]; ?></td>
                                            <td style="font-size: 14px;"><?php echo $solicitud["departamentos"]; ?></td>
                                            <td style="font-size: 14px;">
                                                <?php
                                                $empresa = $solicitud["empresa"];
                                                if ($empresa === "Claimpay") {
                                                    echo '<span class="empresa-claimpay">' . htmlspecialchars($empresa) . '</span>';
                                                } elseif ($empresa === "ITL") {
                                                    echo '<span class="empresa-itl">' . htmlspecialchars($empresa) . '</span>';
                                                } else {
                                                    echo htmlspecialchars($empresa);
                                                }
                                                ?>
                                            </td>
                                            <td style="font-size: 14px;">
                                                <?php echo $solicitud["nombre"] . ' ' . $solicitud["apellidos"]; ?></td>
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
                                                <button class="toggle-button" onclick="showDescriptionModal(this)"
                                                    data-info="<?php echo htmlspecialchars($solicitud['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">Ver
                                                    Descripción</button>
                                            </td>
                                            <td><span
                                                    class="<?php echo strtolower($solicitud['estado']); ?>"><?php echo $solicitud['estado']; ?></span>
                                            </td>
                                            <td style="font-size: 14px;"><strong>Acción Realizada</strong></td>
                                            <td>
                                                <button class="toggle-button" onclick="showDetailsModal(this)"
                                                    data-revisor1-estado="<?php echo addslashes($solicitud['revisor1_estado'] ?? ''); ?>"
                                                    data-revisor1-comentario="<?php echo addslashes($solicitud['revisor1_comentario'] ?? ''); ?>"
                                                    data-revisor2-estado="<?php echo addslashes($solicitud['revisor2_estado'] ?? ''); ?>"
                                                    data-revisor2-comentario="<?php echo addslashes($solicitud['revisor2_comentario'] ?? ''); ?>"
                                                    data-revisor3-estado="<?php echo addslashes($solicitud['revisor3_estado'] ?? ''); ?>"
                                                    data-revisor3-comentario="<?php echo addslashes($solicitud['revisor3_comentario'] ?? ''); ?>"
                                                    data-revisor4-estado="<?php echo addslashes($solicitud['revisor4_estado'] ?? ''); ?>"
                                                    data-revisor4-comentario="<?php echo addslashes($solicitud['revisor4_comentario'] ?? ''); ?>"
                                                    data-motivo-rechazo="<?php echo addslashes($solicitud['motivo_rechazo'] ?? ''); ?>">Ver
                                                    Detalles</button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </section>
    </div>


    <!-- Modal para Ver Descripción -->
    <div id="descriptionModal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4);">
        <div
            style="background-color: white; border: 2px solid #3c8dbc; border-radius: 10px; width: 60%; margin: 10% auto; padding: 20px;">
            <span onclick="closeModal('descriptionModal')"
                style="float: right; cursor: pointer; font-size: 20px; color: #3c8dbc;">&times;</span>
            <h4
                style="color: #3c8dbc; font-weight: bold; font-family: 'Segoe UI', Tahoma, Geneva, sans-serif; text-align: center; border-bottom: 2px solid #3c8dbc; padding-bottom: 10px;">
                Descripción</h4>
            <div id="descriptionContent" style="font-size: 16px; color: #333; margin-top: 20px; white-space: pre-wrap;">
            </div>
        </div>
    </div>


    <!-- Modal para Ver Detalles -->
    <div id="detailsModal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4);">
        <div
            style="background-color: white; border: 2px solid #3c8dbc; border-radius: 10px; width: 60%; margin: 10% auto; padding: 20px;">
            <span onclick="closeModal('detailsModal')"
                style="float: right; cursor: pointer; font-size: 20px; color: #3c8dbc;">&times;</span>
            <h4
                style="color: #3c8dbc; font-weight: bold; font-family: 'Segoe UI', Tahoma, Geneva, sans-serif; text-align: center; border-bottom: 2px solid #3c8dbc; padding-bottom: 10px;">
                Detalles</h4>
            <div id="detailsContent" style="font-size: 16px; color: #333; margin-top: 20px;"></div>
        </div>
    </div>

    <script>
        function showDescriptionModal(button) {
            // Obtiene el contenido de la descripción desde el atributo data-info
            var descriptionContent = button.getAttribute('data-info');

            // Selecciona el contenedor del modal y establece el contenido
            var descriptionContainer = document.getElementById('descriptionContent');
            descriptionContainer.style.whiteSpace = 'pre-wrap'; // Respeta los saltos de línea
            descriptionContainer.innerHTML = descriptionContent; // Muestra el contenido de la descripción

            // Muestra el modal
            document.getElementById('descriptionModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }


        function showDetailsModal(button) {
            var detailsContent = '';
            detailsContent += '<strong>Estado Revisor 1:</strong> <span class="' + getStatusClass(button.getAttribute('data-revisor1-estado')) + '">' + button.getAttribute('data-revisor1-estado') + '</span><br>';
            detailsContent += '<strong>Comentario Revisor 1:</strong> ' + button.getAttribute('data-revisor1-comentario') + '<br><br>';
            detailsContent += '<strong>Estado Revisor 2:</strong> <span class="' + getStatusClass(button.getAttribute('data-revisor2-estado')) + '">' + button.getAttribute('data-revisor2-estado') + '</span><br>';
            detailsContent += '<strong>Comentario Revisor 2:</strong> ' + button.getAttribute('data-revisor2-comentario') + '<br><br>';
            detailsContent += '<strong>Estado Revisor 3:</strong> <span class="' + getStatusClass(button.getAttribute('data-revisor3-estado')) + '">' + button.getAttribute('data-revisor3-estado') + '</span><br>';
            detailsContent += '<strong>Comentario Revisor 3:</strong> ' + button.getAttribute('data-revisor3-comentario') + '<br><br>';
            detailsContent += '<strong>Estado Revisor 4:</strong> <span class="' + getStatusClass(button.getAttribute('data-revisor4-estado')) + '">' + button.getAttribute('data-revisor4-estado') + '</span><br>';
            detailsContent += '<strong>Comentario Revisor 4:</strong> ' + button.getAttribute('data-revisor4-comentario') + '<br><br>';

            // Mostrar el motivo de rechazo si el estado general es "Rechazado"
            var motivoRechazo = button.getAttribute('data-motivo-rechazo');
            if (motivoRechazo && (button.getAttribute('data-revisor1-estado') === 'Rechazado' ||
                button.getAttribute('data-revisor2-estado') === 'Rechazado' ||
                button.getAttribute('data-revisor3-estado') === 'Rechazado' ||
                button.getAttribute('data-revisor4-estado') === 'Rechazado')) {
                detailsContent += '<strong style="color: orange;">Motivo del Rechazo:</strong> ' + motivoRechazo + '<br>';
            }

            document.getElementById('detailsContent').innerHTML = detailsContent;
            document.getElementById('detailsModal').style.display = 'block';
        }

        function getStatusClass(status) {
            if (status === 'Aprobado') {
                return 'aprobado';
            } else if (status === 'Rechazado') {
                return 'rechazado';
            } else if (status === 'Pendiente') {
                return 'pendiente';
            }
            return '';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }



        function showRejectionModal() {
            document.getElementById('rejectionModal').style.display = 'block';
        }

        function closeRejectionModal() {
            document.getElementById('rejectionModal').style.display = 'none';
        }

        function submitRejection() {
            const motivo = document.getElementById('motivoRechazoText').value;
            document.getElementById('motivoRechazoInput').value = motivo;
            document.getElementById('rejectForm').submit();
        }

        $(document).ready(function () {
            if ($.fn.DataTable.isDataTable('#solicitudes_table')) {
                $('#solicitudes_table').DataTable().destroy();
            }

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
                }
            });
        });

        $(document).ready(function () {
            if ($.fn.DataTable.isDataTable('#solicitudes_table2')) {
                $('#solicitudes_table2').DataTable().destroy();
            }

            $('#solicitudes_table2').DataTable({
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

        document.getElementById('exportExcel').addEventListener('click', function () {
            // Seleccionar la tabla y procesar los datos
            var table = document.getElementById('solicitudes_table');
            var rows = [];

            // Recorrer las filas de la tabla
            for (var i = 0, row; row = table.rows[i]; i++) {
                var rowData = [];
                for (var j = 0, col; col = row.cells[j]; j++) {
                    if (col.querySelector('button')) {
                        // Si la celda tiene un botón, obtenemos el atributo data-info o data-revisor
                        var button = col.querySelector('button');
                        if (button.hasAttribute('data-info')) {
                            rowData.push(button.getAttribute('data-info')); // Descripción
                        } else if (button.hasAttribute('data-revisor1-estado')) {
                            var details = '';
                            details += 'Estado Revisor 1: ' + button.getAttribute('data-revisor1-estado') + '\n';
                            details += 'Comentario Revisor 1: ' + button.getAttribute('data-revisor1-comentario') + '\n';
                            details += 'Estado Revisor 2: ' + button.getAttribute('data-revisor2-estado') + '\n';
                            details += 'Comentario Revisor 2: ' + button.getAttribute('data-revisor2-comentario') + '\n';
                            details += 'Estado Revisor 3: ' + button.getAttribute('data-revisor3-estado') + '\n';
                            details += 'Comentario Revisor 3: ' + button.getAttribute('data-revisor3-comentario') + '\n';
                            details += 'Estado Revisor 4: ' + button.getAttribute('data-revisor4-estado') + '\n';
                            details += 'Comentario Revisor 4: ' + button.getAttribute('data-revisor4-comentario') + '\n';
                            rowData.push(details.trim()); // Detalles
                        } else {
                            rowData.push(''); // Si no hay información
                        }
                    } else {
                        rowData.push(col.innerText.trim()); // Añadir texto de la celda
                    }
                }
                rows.push(rowData);
            }

            // Crear hoja de Excel
            var workbook = XLSX.utils.book_new();
            var worksheet = XLSX.utils.aoa_to_sheet(rows);
            XLSX.utils.book_append_sheet(workbook, worksheet, "Reporte");

            // Descargar archivo
            XLSX.writeFile(workbook, 'Reporte_Solicitudes.xlsx');
        });

    </script>
    <script>
        window.onload = function () {
            var sidebarToggle = document.getElementById("sidebarToggle");
            if (sidebarToggle) {
                sidebarToggle.click();  // Simula el clic para abrir la sidebar automáticamente
            }
        };
    </script>

    <?php
    require 'footer.php';
}
ob_end_flush();
?>