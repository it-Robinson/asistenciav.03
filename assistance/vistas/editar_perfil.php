<?php
// activamos almacenamiento en el buffer
ob_start();
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit;
} else {
    require 'header.php'; // Incluir la cabecera
    require_once('../modelos/Usuario.php'); // Modelo de usuario, donde está obtener_departamentos()

    $usuario = new Usuario();
    $idusuario = $_SESSION['idusuario'];
    $departamentos = $usuario->obtener_departamentos(); 
    $tipousuarios = $usuario->obtener_tipousuarios();


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $email = $_POST['email'];
        $imagen = $_FILES['imagen']['name'];
        $iddepartamento = $_POST['iddepartamento'];
        $idtipousuario = $_POST['idtipousuario'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $tipo_turno = $_POST['tipo_turno'];
        $empresa = $_POST['empresa'];
        $extension = $_POST['extension'];
        $primary_phone_number = $_POST['primary_phone_number'];
        

        if ($imagen) {
            $target_dir = "../files/usuarios/";
            $target_file = $target_dir . basename($imagen);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file);
        } else {
            $imagen = $_SESSION['imagen'];
        }

        $usuario->editar_usuario($idusuario, $nombre, $apellidos, $email, $imagen, $iddepartamento,$idtipousuario, $fecha_nacimiento, $tipo_turno, $empresa, $extension, $primary_phone_number);

        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellidos'] = $apellidos;
        $_SESSION['email'] = $email;
        $_SESSION['imagen'] = $imagen;
        $_SESSION['fecha_nacimiento'] = $fecha_nacimiento;
        $_SESSION['tipo_turno'] = $tipo_turno;
        $_SESSION['empresa'] = $empresa;
        $_SESSION['extension'] = $extension;
        $_SESSION['primary_phone_number'] = $primary_phone_number;

        header('Location: editar_perfil.php?success=1');
        exit;
    }

    $rsptan = $usuario->get_usuario($idusuario);
    $reg = $rsptan->fetch_object();
?>

<style>
/* Contenedor del formulario */
.container {
    max-width: 1000px; /* Aumentamos el ancho máximo */
    margin: 0 auto;
    padding: 40px; /* Aumentamos el padding */
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

/* Ajuste de fuentes */
h2 {
    font-size: 2.5rem; /* Aumentamos el tamaño del título */
    font-weight: bold;
    margin-bottom: 25px;
}

.form-label {
    font-size: 2rem; /* Hacemos más grandes las etiquetas */
    font-weight: bold;
}

/* Espaciado entre filas y campos */
.form-group {
    margin-bottom: 2rem; /* Mayor espacio entre los grupos de formulario */
}

/* Tamaño de inputs */
.form-control {
    font-size: 1.8rem; /* Aumentamos el tamaño del texto de los inputs */
    padding: 15px; /* Aumentamos el padding */
    border-radius: 5px;
}

/* Imagen de perfil más grande */
.img-thumbnail {
    border-radius: 50%;
    width: 150px; /* Aumentamos el tamaño de la imagen */
    height: 150px;
    object-fit: cover;
}

.btn-guardar {
    background-color:  #007bff;   /* Color de fondo personalizado */
    border: none;               /* Sin borde */
    padding: 15px 30px;         /* Ajustar tamaño del botón */
    font-size: 2rem;          /* Tamaño de fuente */
    font-weight: bold;
    color: white;    
    border-radius: 5px;         /* Esquinas redondeadas */
    width: 920px;                /* Ajuste automático al contenido */
    display: inline-block;      /* Alineación inline */
    transition: background-color 0.3s ease, transform 0.2s ease; /* Transiciones */
}

.btn-guardar i {
    margin-right: 8px;          /* Espacio entre el icono y el texto */
}

.btn-guardar:hover {
    background-color: #0056b3;  /* Color de fondo al pasar el mouse */
    transform: scale(1.05);     /* Efecto de agrandamiento al hacer hover */
}

.btn-guardar:active {
    background-color: #1e7e34;  /* Color al hacer clic */
}


/* Hacer los iconos un poco más grandes */
.form-label i {
    font-size: 1.5rem; /* Aumentamos el tamaño de los íconos */
    margin-right: 5px;
}

/* Ajustar el espaciado de las filas */
.row {
    margin-bottom: 25px; /* Mayor espacio entre las filas */
}

/* Responsividad para pantallas pequeñas */
@media (max-width: 768px) {
    .container {
        padding: 25px; /* Ajustamos el padding para pantallas pequeñas */
    }

    h2 {
        font-size: 2rem; /* Reducimos el tamaño del título en pantallas pequeñas */
    }

    .form-control {
        font-size: 2rem; /* Ajustamos el tamaño de texto de inputs */
    }

    .btn-primary {
        font-size: 1.2rem; /* Reducimos el tamaño del botón en pantallas pequeñas */
        padding: 12px 20px;
    }
}
/* Ajuste para los elementos <select> con la clase form-select */
.form-select {
    font-size: 1.5rem;  /* Aumenta el tamaño de la fuente */
    padding: 12px 15px; /* Aumenta el espacio dentro del <select> */
    border-radius: 5px;  /* Redondea las esquinas */
    border: 1px solid #ced4da; /* Mantén un borde visible y limpio */
    width: 100%; /* Para asegurarse de que ocupe todo el ancho disponible */
    height: auto; /* Ajusta la altura automáticamente al nuevo contenido */
}
.custom-alert {
    background-color: #007bff; /* Fondo azul */
    color: white;              /* Texto blanco */
    font-size: 1.5rem;         /* Tamaño del texto */
    font-weight: bold;         /* Texto en negrita */
    padding: 15px;             /* Espaciado interno */
    border-radius: 5px;        /* Bordes redondeados */
}
</style>

<!-- Contenido HTML dentro de la estructura principal -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container mt-5">
            <h2 class="mb-4">Editar Perfil <i class="fas fa-user-edit"></i></h2>


<!-- Mostrar mensaje de éxito -->
<?php if (isset($_GET['success'])): ?>
    <div class="custom-alert alert " id="successMessage">
        Perfil actualizado correctamente.
    </div>
<?php endif; ?>

            <!-- Formulario para editar el perfil -->
            <form action="editar_perfil.php" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <!-- Campo Nombre -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre <i class="fas fa-user"></i></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $reg->nombre; ?>" required>
                    </div>

                    <!-- Campo Apellidos -->
                    <div class="col-md-6">
                        <label for="apellidos" class="form-label">Apellidos <i class="fas fa-user"></i></label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $reg->apellidos; ?>" required>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <!-- Campo Extensión -->
                    <div class="col-md-6">
                        <label for="extension" class="form-label">Extensión <i class="fas fa-phone-alt"></i></label>
                        <input type="text" class="form-control" id="extension" name="extension" value="<?php echo $reg->extension; ?>" required>
                    </div>

                    <!-- Campo Teléfono -->
                    <div class="col-md-6">
                        <label for="primary_phone_number" class="form-label">Teléfono <i class="fas fa-phone"></i></label>
                        <input type="text" class="form-control" id="primary_phone_number" name="primary_phone_number" value="<?php echo $reg->primary_phone_number; ?>" required>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <!-- Campo Correo Electrónico -->
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo Electrónico <i class="fas fa-envelope"></i></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $reg->email; ?>" required>
                    </div>

                    <!-- Campo Fecha de Nacimiento -->
                    <div class="col-md-6">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Cumpleaños <i class="fas fa-calendar-alt"></i></label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $reg->fecha_nacimiento; ?>" required>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <!-- Campo Tipo de Turno -->
                    <div class="col-md-6">
                        <label for="tipo_turno" class="form-label">Tipo de Turno <i class="fas fa-clock"></i></label>
                        <select name="tipo_turno" id="tipo_turno" class="form-select" required>
                            <option value="" selected hidden>Seleccionar una opción</option>
                            <option value="Full-time" <?php echo ($reg->tipo_turno == 'Full-time') ? 'selected' : ''; ?>>Full-time</option>
                            <option value="Part-time" <?php echo ($reg->tipo_turno == 'Part-time') ? 'selected' : ''; ?>>Part-time</option>
                        </select>
                    </div>

                    <!-- Campo Empresa -->
                    <div class="col-md-6">
                        <label for="empresa" class="form-label">Empresa <i class="fas fa-building"></i></label>
                        <select name="empresa" id="empresa" class="form-select" required>
                            <option value="" selected hidden>Seleccionar una opción</option>
                            <option value="ITL" <?php echo ($reg->empresa == 'ITL') ? 'selected' : ''; ?>>ITL</option>
                            <option value="Claimpay" <?php echo ($reg->empresa == 'Claimpay') ? 'selected' : ''; ?>>Claimpay</option>
                        </select>
                    </div>
                    
                </div>

                <div class="row g-3 mt-3">
                    <!-- Campo Departamento -->
                    <div class="col-md-6">
                        <label for="iddepartamento" class="form-label">Departamento <i class="fas fa-sitemap"></i></label>
                        <select name="iddepartamento" id="iddepartamento" class="form-select" required>
                            <?php while ($row = $departamentos->fetch_object()): ?>
                                <option value="<?php echo $row->iddepartamento; ?>" <?php echo ($reg->iddepartamento == $row->iddepartamento) ? 'selected' : ''; ?>>
                                    <?php echo $row->nombre; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <br>
                        <br>

                        <label for="idtipousuario" class="form-label">Puesto <i class="fas fa-sitemap"></i></label>
<select name="idtipousuario" id="idtipousuario" class="form-select" required>
    <?php while ($row = $tipousuarios->fetch_object()): ?>
        <?php 
            // Lista de puestos a ocultar, pero que se mostrarán si el usuario tiene ese puesto
            $puestosOcultos = ['Team Leader', 'Administrador', 'Gerencia y RRHH', 'empleado', 'Receptionist'];

            // Si el puesto está en la lista de ocultos Y el usuario no tiene ese puesto, se omite
            if (in_array($row->nombre, $puestosOcultos) && $_SESSION['tipousuario'] != $row->nombre) {
                continue;
            }
        ?>
        <!-- Mostrar la opción si no está en la lista de ocultos, o si el usuario tiene ese puesto -->
        <option value="<?php echo $row->idtipousuario; ?>" 
            <?php echo ($reg->idtipousuario == $row->idtipousuario) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($row->nombre); ?>
        </option>
    <?php endwhile; ?>
</select>


                    </div>

                    <!-- Campo Imagen de Perfil -->
                    <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                        <label for="imagen" class="form-label">Imagen de Perfil<i class="fas fa-image"></i></label>
                                        <input type="file" class="form-control-file" id="imagen" name="imagen">
                                        <div class="mt-3">
                                            <p class="form-label">Imagen Actual:</p>
                                            <img src="../files/usuarios/<?php echo $reg->imagen; ?>" alt="Imagen de Perfil" style="width: 100px; height: 100px;">
                                        </div>
                                    </div>
                </div>

                <button type="submit" class="btn btn-guardar mt-4">
    <i class="fas fa-save"></i> Guardar Cambios
</button>

            </form>
        </div>
    </section>
</div>


<!-- Agregamos JavaScript para ocultar el mensaje de éxito después de 3 segundos -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var successMessage = document.getElementById('successMessage');
        if (successMessage) {
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 3000);
        }
    });
</script>

<?php
    require 'footer.php';
}
ob_end_flush();
?>
