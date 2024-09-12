<?php 
if (strlen(session_id()) < 1) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Asistencia</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3 -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    <!-- Conflicto -->
  
    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <!-- AdminLTE Skins -->
    <link rel="stylesheet" href="../public/css/_all-skins.min.css">
    <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
    <link rel="shortcut icon" href="../public/img/icono.ico">
    <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">    
    <link href="../public/datatables/buttons.dataTables.min.css" rel="stylesheet"/>
    <link href="../public/datatables/responsive.dataTables.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="../public/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

    <style>


.boton-container {
  display: flex;
  justify-content: space-between;
  width: flex;
  text-align: center;
}

.boton {
  padding: 15px 25px;
  font-size: 18px;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  outline: none;
  border: none;
  border-radius: 30px;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
  transition: background-color 0.3s, transform 0.3s;
  font-weight: bold;
}

.boton-izquierda {
  color: #fff;
  background-color: #007bff;
}

.boton-derecha {
  color: #fff;
  background-color: #28a745;
}

.boton:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.boton:active {
  transform: translateY(0);
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.emoji {
  margin-right: 10px;
}


.lockscreen-name {
  text-align: center;
  animation: pulse 2s infinite alternate;
}

.lockscreen-name h5 {
  margin: 10px 0;
  font-size: 18px;
  color: #555;
  font-style: italic;
}

.lockscreen-name h4 {
  margin: 5px 0;
  font-size: 22px;
  font-weight: bold;
  color: #333;
}

.lockscreen-name h4:last-child {
  color: #337ab7;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  100% {
    transform: scale(1.05);
  }
}

.text-primary {
    font-size: 34px; /* Tamaño de fuente */
    font-family: 'Merriweather', serif; /* Tipo de letra */
    font-weight: bold; /* Texto en negrita */
    background-color: white; /* Fondo blanco */
}
</style>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="escritorio.php" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>PA</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Panel de Asistencia</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"></a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="user-image" alt="User Image">
                            <span class="hidden-xs">
                                <?php 
                                if (isset($_SESSION['nombre'])) {
                                    $nombreCompleto = $_SESSION['nombre'];
                                    $partesNombre = explode(' ', $nombreCompleto);
                                    echo $partesNombre[0]; // Imprime el primer nombre
                                }
                                ?>
                                <?php 
                                if (isset($_SESSION['apellidos'])) {
                                    $nombreCompleto = $_SESSION['apellidos'];
                                    $partesNombre = explode(' ', $nombreCompleto);
                                    echo $partesNombre[0]; // Imprime el primer nombre
                                }
                                ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" alt="User Image">
                                <p>
                                    <?php 
                                    if (isset($_SESSION['nombre'])) {
                                        $nombreCompleto = $_SESSION['nombre'];
                                        $partesNombre = explode(' ', $nombreCompleto);
                                        echo $partesNombre[0]; // Imprime el primer nombre
                                    }
                                    ?>
                                    <?php 
                                    if (isset($_SESSION['apellidos'])) {
                                        $nombreCompleto = $_SESSION['apellidos'];
                                        $partesNombre = explode(' ', $nombreCompleto);
                                        echo $partesNombre[0]; // Imprime el primer nombre
                                    }
                                    ?>
                                </p>
                                <p>
                                    <b>
                                        <?php 
                                        if (isset($_SESSION['nombre_departamento'])) {
                                            $nombreCompleto = $_SESSION['nombre_departamento'];
                                            $partesNombre = explode(' ', $nombreCompleto);
                                            echo $partesNombre[0];
                                            if (isset($partesNombre[1])) echo " " . $partesNombre[1];
                                            if (isset($partesNombre[2])) echo " " . $partesNombre[2];
                                        }
                                        ?>
                                    </b>
                                </p>
                            </li>
<!-- Menu Footer-->
<li class="user-footer">
    <div class="pull-left">
        <!-- Agregamos el botón para editar el perfil -->
        <a href="editar_perfil.php" class="btn btn-default btn-flat">Editar Perfil</a>
    </div>
    <div class="pull-right">
        <a href="../ajax/usuario.php?op=salir" class="btn btn-primary" role="button">Salir</a>
    </div>
</li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" style="width: 50px; height: 50px;" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>
                        <?php 
                        if (isset($_SESSION['nombre'])) {
                            $nombreCompleto = $_SESSION['nombre'];
                            $partesNombre = explode(' ', $nombreCompleto);
                            echo $partesNombre[0]; // Imprime el primer nombre
                        }
                        ?>
                        <?php 
                        if (isset($_SESSION['apellidos'])) {
                            $nombreCompleto = $_SESSION['apellidos'];
                            $partesNombre = explode(' ', $nombreCompleto);
                            echo $partesNombre[0]; // Imprime el primer nombre
                        }
                        ?>
                    </p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu tree" data-widget="tree">
                <li class="header">MENÚ DE NAVEGACIÓN</li>
                <li><a href="escritorio.php"><i class="bi bi-house"></i> <span>Inicio</span></a></li>

                
                <?php if ($_SESSION['tipousuario'] == 'Administrador') { ?>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-folder"></i> <span>Acceso</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="usuario.php"><i class="bi bi-person-plus"></i> Usuarios</a></li>
                            <li><a href="tipousuario.php"><i class="bi bi-people"></i> Tipo Usuario</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-building"></i> <span>Departamento</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="departamento.php"><i class="bi bi-circle"></i> Tipo de Departamento</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-clock-o"></i> <span>Asistencias</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="asistencia.php"><i class="bi bi-calendar"></i> Reporte General</a></li>
                            <li><a href="rptasistencia.php"><i class="bi bi-calendar3"></i> Reportes por fechas</a></li>
                            <li><a href="ListadoPermisos.php"><i class="bi bi-card-checklist"></i> Listado de Permisos</a></li>
                        </ul>
                    </li>
                    <li><a href="calendar.php"><i class="bi bi-calendar2-plus"></i> <span>Calendario</span></a></li>
                <?php } ?>



                <!--EMPLEADOS-->

                <?php 
                $puestosExcluidos = ['Team Leader', 'Administrador', 'Gerencia', 'RRHH'];
                
                if (!in_array($_SESSION['tipousuario'], $puestosExcluidos)) { ?>
                    <li><a href="inicio.php"><i class="bi bi-alarm"></i><span> Marcador</span></a></li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-clock-o"></i> <span>Mis Asistencias</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="asistenciau.php"><i class="bi bi-calendar"></i> Reporte General</a></li>
                            <li><a href="rptasistenciau.php"><i class="bi bi-calendar3"></i> Reporte por fechas</a></li>
                            <li><a href="formularioPermiso.php"><i class="bi bi-envelope"></i> Solicitar Permiso</a></li>
                        </ul>
                    </li>
                <?php } ?>


                <!--TEAM LEADER-->


                <?php if ($_SESSION['tipousuario'] == 'Team Leader') { ?>
                    <li><a href="inicio.php"><i class="bi bi-alarm"></i><span> Marcador</span></a></li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-clock-o"></i> <span>Mis Asistencias</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="asistenciau.php"><i class="bi bi-person"></i> Mi lista de Asistencia</a></li>
                            <li><a href="asistenciaspordepa.php"><i class="bi bi-people"></i> Reporte por Area</a></li>
                            <li><a href="formularioPermiso.php"><i class="bi bi-envelope"></i> Solicitar Permiso</a></li>
                            <li><a href="ListadoPermisos.php"><i class="bi bi-inbox-fill"></i> Listado de Permisos</a></li>
                        </ul>
                    </li>
                <?php } ?>




                <!--GERENCIA-->



                <?php if ($_SESSION['tipousuario'] == 'Gerencia y RRHH') { ?>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-clock-o"></i> <span>Mis Asistencias</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="asistencia.php"><i class="bi bi-calendar"></i> Reporte General</a></li>
                            <li><a href="rptasistencia.php"><i class="bi bi-calendar3"></i> Reportes por fechas</a></li>
                            <li><a href="ListadoPermisos.php"><i class="bi bi-envelope"></i> Listado de Permisos</a></li>
                        </ul>
                    </li>
                <?php } ?>
                
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

