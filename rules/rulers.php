<?php
session_start();
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Pragma: no-cache');

// Tiempo máximo de inactividad en segundos (5 minutos = 300 segundos)
$tiempo_max_inactividad = 120;

if (isset($_SESSION['ultima_actividad'])) {
    // Calcular el tiempo de inactividad
    $inactividad = time() - $_SESSION['ultima_actividad'];

    if ($inactividad > $tiempo_max_inactividad) {
        // Si el tiempo de inactividad supera el límite, destruir la sesión y redirigir al login
        session_unset(); // Eliminar todas las variables de sesión
        session_destroy(); // Destruir la sesión
        header("Location: ../assistance/vistas/login.html"); // Redirigir al login
        exit();
    }
}

// Actualizar el tiempo de última actividad
$_SESSION['ultima_actividad'] = time();

// Verificar si la sesión está iniciada
if (!isset($_SESSION['nombre'])) {
    // Redirigir al login si no está autenticado
    header("Location: ../assistance/vistas/login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <!-- basic -->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- mobile metas -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="viewport" content="initial-scale=1, maximum-scale=1">
   <!-- site metas -->
   <title>Sistema RRHH</title>
   <meta name="keywords" content="">
   <meta name="description" content="">
   <meta name="author" content="">
   <!-- bootstrap css -->
   <link rel="stylesheet" href="../css/bootstrap.min.css">
   <!-- style css -->
   <link rel="stylesheet" href="../css/style.css">
   <link rel="stylesheet" href="styles.css">
   <!-- Responsive-->
   <link rel="stylesheet" href="../css/responsive.css">
   <link rel="stylesheet" href="../css/owl.carousel.min.css">
   
   <!-- fevicon -->
   <link rel="icon" href="../images/icono.ico" type="image/gif" />
   <!-- Scrollbar Custom CSS -->
   <link rel="stylesheet" href="../css/jquery.mCustomScrollbar.min.css">
   <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
   <!-- Tweaks for older IEs-->
   <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
   <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
   <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
      <style>
        .header_midil {
            padding: 20px 0;
        }
        .phone-link {
  display: inline-flex;
  align-items: center;
  color: #ffffff; /* Cambia esto al color que desees */
  text-decoration: none; /* Quitar el subrayado */
}

.phone-link i {
  margin-right: 8px; /* Espacio entre el icono y el texto */
}

        .d_flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .conta_icon {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
        }


        .email-link {
  display: inline-flex;
  align-items: center;
  color: #ffffff; /* Cambia esto al color que desees */
  text-decoration: none; /* Quitar el subrayado */
}

.email-link i {
  margin-right: 8px; /* Espacio entre el icono y el texto */
}

        .conta_icon li {
            display: flex;
            align-items: center;
        }
        .address-link {
  display: inline-flex;
  align-items: center;
  color: #ffffff; /* Cambia esto al color que desees */
  text-decoration: none; /* Quitar el subrayado */
}

.address-link i {
  margin-right: 8px; /* Espacio entre el icono y el texto */
}

        .conta_icon li a {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
        }

        .conta_icon li i {
            margin-right: 5px;
        }

        /* Navbar Styles */
        .navbar {
            background-color: #002060;
        }

        .navbar-brand,
        .nav-link {
            color: #ffffff !important;
            font-weight: bold;
            text-transform: uppercase;
            padding: 15px 20px;
        }

        .nav-link:hover,
        .nav-link:focus {
            background-color: #ffd700 !important;
            color: #ffffff !important;
        }

        .dropdown-menu {
            background-color: #002060;
            border-radius: 0;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .15);
        }

        .dropdown-item {
            color: #ffffff;
            padding: 10px 20px;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: #ffd700 !important;
            color: #ffffff !important;
        }

        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
            border-radius: 0;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .15);
        }

        .dropdown-menu .dropdown-menu {
            max-height: 200px;
            overflow-y: auto;
        }

        .header_midil {
    background-color: #fff;
    border-bottom: 1px solid #ccc;
    padding: 10px 0;
}

.d_flex {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.logo img {
    height: 100px;
}

.conta_icon {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.conta_icon li {
    margin-left: 20px;
}

.conta_icon li a {
    text-decoration: none;
    color: #333;
    font-size: 14px;
    display: flex;
    align-items: center;
}

.conta_icon li a i {
    margin-right: 5px;
    color: #FFD700; /* Color dorado para los íconos */
}

.text-left {
    display: flex;
    justify-content: flex-start;
}

.text-center {
    display: flex;
    justify-content: center;
}

.text-right {
    display: flex;
    justify-content: flex-end;
}
.logout-btn {
    background-color: red;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    font-weight: bold;
}

.logout-btn:hover {
    background-color: darkred;
}
    </style>

      
</head>
<!-- body -->

<body class="main-layout">
  <!-- loader  -->
  <div class="loader_bg">
      <div class="loader"><img src="../images/loading.gif" alt="#" /></div>
   </div>
   <!-- end loader -->
   <!-- header -->
   <header>
      <!-- header inner -->
      <div class="header">
      <div class="header_midil">
        <div class="container">
            <div class="row d_flex">
                <div class="col-md-3 text-left">
                    <a class="logo" href="#"><img src="../images/logo1.jpg" alt="Logo Left" /></a>
                </div>
                <div class="col-md-6 text-center">
                    <ul class="conta_icon">
                        <li><a href="https://wa.me/+51917176158"><i class="fa fa-phone" aria-hidden="true"></i> Call Us: +51917176158</a></li>
                        <li><a href="mailto:malena@flinslaw.com"><i class="fa fa-envelope" aria-hidden="true"></i> malena@flinslaw.com</a></li>
                    </ul>
                </div>
                <div class="col-md-3 text-right">
                    <a class="logo" href="#"><img src="../images/logo2.png" alt="Logo Right" /></a>
                </div>
            </div>
        </div>
    </div>
    <div class="header_bo">
            <div class="container">
               <div class="row">
                  <nav class="navigation navbar navbar-expand-md navbar-dark">
                     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                     </button>
                     <div class="collapse navbar-collapse" id="navbarsExample04">
                        <ul class="navbar-nav mr-auto">
                           <li class="nav-item ">
                              <a class="nav-link" href="../index2">Home</a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" href="../news/news">News</a>
                           </li>
                           <li class="nav-item ">
                              <a class="nav-link" href="../calendar/calendar">Calendar</a>
                           </li>
                           <li class="nav-item dropdown active">
                              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 Rules
                              </a>
                              <div class="dropdown-menu " aria-labelledby="navbarDropdown">
                                 <div class="dropdown-submenu">
                                    <a class="dropdown-item" href="../rules/rulers2">English</a>
                                 </div>
                                 <div class="dropdown-submenu ">
                                    <a class="dropdown-item active" href="../rules/rulers">Spanish</a>
                                 </div>
                              </div>
                           </li>
                           <li class="nav-item dropdown ">
                              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 Team
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                              <div class="dropdown-submenu">
                              <a class="dropdown-item" href="../team/itl/administrativearea">Administrative area</a>
                                                <a class="dropdown-item dropdown-toggle" href="#">ITL</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="../team/itl/presuit">Settlement</a>
                                                    <a class="dropdown-item" href="../team/itl/HOS">HOS</a>
                                                    <a class="dropdown-item" href="../team/itl/filing">Filing</a>
                                                    <a class="dropdown-item" href="../team/itl/legalassistant">Legal Assistant</a>
                                                    <a class="dropdown-item" href="../team/itl/scheduling">Scheduling</a>
                                                    <a class="dropdown-item" href="../team/itl/release">Release</a>
                                                    <a class="dropdown-item" href="../team/itl/uploading">Uploading</a>
                                                    <a class="dropdown-item" href="../team/itl/account">Account</a>
                                                </div>
                                            </div>
                                 <div class="dropdown-submenu">
                                    <a class="dropdown-item dropdown-toggle" href="#">Claimpay</a>
                                    <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="../team/claimpay/operations">Operations</a>
                                                    <a class="dropdown-item" href="../team/claimpay/finance">Finance</a>
                                                    <a class="dropdown-item" href="../team/claimpay/revenue">Revenue</a>
                                                    <a class="dropdown-item" href="../team/claimpay/underwriting">Underwriting</a> 
                                    </div>
                                 </div>
                              </div>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" href="../assistance/vistas/escritorio">Remote assistance</a>
                           </li>
                           <!--
                           <li class="nav-item">
                                 <a class="nav-link" href="../contact">contact</a>
                           </li>
-->
                           <li class="nav-item">
    <a href="../assistance/ajax/usuario.php?op=salir" class="nav-link logout-btn">Log out</a>
</li>
                        </ul>
                     </div>
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </header>
   <!-- end header inner -->
   <!-- end header -->
   <div class="container">
   <h2 class="text-center"></h2>
      <!-- end header inner -->
   <!-- end header -->
 <br>
 <main>
        <section>
            <h2 onclick="toggleSection('introduction')"><i class="fas fa-info-circle"></i> Introducción</h2>
            <div id="introduction" class="content">
                <p>Estimados colaboradores,</p>
                <p>Con el objetivo de promover un entorno laboral productivo y armonioso en la empresa, presentamos nuestro Reglamento Interno. Les solicitamos su lectura y estricto cumplimiento para garantizar un ambiente de trabajo positivo y eficiente.</p>
            </div>
        </section>
        <section>
            <h2 onclick="toggleSection('work-schedule')"><i class="fas fa-clock"></i> Horario Laboral</h2>
            <div id="work-schedule" class="content">
                <p>El horario laboral es de 8:00 a.m. a 6:00 p.m. o de 7:00 a.m. a 5:00 p.m., incluyendo una pausa de una hora para el refrigerio, los turnos para el refrigerio van de 12:00 p.m. a 3:00 p.m. Durante este período, se espera que los empleados mantengan una conducta profesional y respetuosa.</p>
                <ul class="styled-list">
                    <li>La puntualidad es esencial. En caso de retraso o ausencia debido a circunstancias excepcionales, se debe notificar con antelación al supervisor y al área de recursos humanos.</li>
                    <li>Cualquier ingreso después de las 7:20 a.m. o 8:20 a.m. (según el turno) no será permitido, lo que resultará en descuentos y un registro de amonestación.</li>
                    <li>Si un empleado acumula tres o más tardanzas en un mes calendario, se le emitirá un memorándum de amonestación.</li>
                    <li>Para ausencias por enfermedad, se debe presentar un certificado médico emitido por el Ministerio de Salud o una clínica, junto con la receta y el comprobante de compra de medicamentos.</li>
                    <li>En casos de descanso médico, el trabajador debe notificar al supervisor directo, recursos humanos y la gerencia el mismo día en que obtenga el certificado médico.</li>
                    <li>Al reincorporarse al trabajo, se debe presentar el certificado médico físicamente a recursos humanos en un plazo máximo de 48 horas. Caso contrario los días serán descontados.</li>
                    <li>Las citas médicas deben ser solicitadas por correo electrónico al supervisor directo, con copia a recursos humanos y gerencia, con al menos dos días de anticipación. El tiempo empleado en una cita médica será recuperado con horas de trabajo.</li>
                    <li>Los permisos personales deben solicitarse con un mínimo de dos días de anticipación y serán evaluados por la gerencia. La aprobación se comunicará por correo electrónico. Dependiendo del permiso solicitado se evaluará si se recuperan las horas o se procederá con el descuento correspondiente.</li>
                    <li>Las vacaciones deben solicitarse por correo electrónico, copiando al supervisor directo, gerencia y recursos humanos, con al menos 15 días de anticipación. La aprobación se espera de la gerencia.</li>
                    <li>La modalidad de trabajo en home office solo será aprobada por la gerencia. Los empleados interesados deben enviar una solicitud por correo electrónico, con copia al supervisor y recursos humanos. Esto será excepcional.</li>
                    <li>Al renunciar o finalizar el contrato, se espera que los empleados entreguen sus tareas pendientes y devuelvan el mobiliario en buen estado.</li>
                </ul>
            </div>
        </section>
        <section>
            <h2 onclick="toggleSection('conduct-rules')"><i class="fas fa-users"></i> Normas de Conducta</h2>
            <div id="conduct-rules" class="content">
                <ul class="styled-list">
                    <li>Toda falta de respeto o indisciplina hacia los compañeros, administrativos o ejecutivos, se considera grave y puede resultar en el cese del contrato. Se espera un trato cordial y respetuoso entre colegas.</li>
                    <li>Se debe completar todas las tareas asignadas durante el horario laboral.</li>
                    <li>Se prohíbe cualquier forma de discriminación por motivos de raza, orientación sexual, religión, orientación ideológica, edad o discapacidad, según la regulación peruana.</li>
                    <li>El acoso, ya sea sexual, verbal, psicológico, físico o cibernético, se considera una falta grave y será sancionado según la regulación peruana.</li>
                    <li>El uso de teléfonos celulares y dispositivos similares está prohibido durante el horario de trabajo, a menos que sea necesario para tareas laborales específicas.</li>
                    <li>Está estrictamente prohibido presentarse al trabajo bajo los efectos del alcohol o sustancias ilegales, lo que puede resultar en el cese del contrato.</li>
                    <li>El registro de horas de entrada y salida es obligatorio. La omisión debe respaldarse con pruebas. La falta de registro resultará en un descuento salarial.</li>
                    <li>Se espera que los empleados mantengan su área de trabajo limpia y ordenada. Los desechos orgánicos deben colocarse en bolsas antes de arrojarlos al basurero.</li>
                    <li>La vestimenta debe ser adecuada para el entorno laboral, sin prendas inapropiadas. Además, se espera que los empleados mantengan una higiene personal adecuada.</li>
                </ul>
            </div>
        </section>
        <section>
            <h2 onclick="toggleSection('equipment-use')"><i class="fas fa-desktop"></i> Uso de Equipos de Oficina</h2>
            <div id="equipment-use" class="content">
                <ul class="styled-list">
                    <li>Se prohíbe el uso de equipos de la empresa para fines personales, como el almacenamiento de música o imágenes.</li>
                    <li>No está permitido el acceso a correos personales, redes sociales o videos en línea durante el horario laboral.</li>
                    <li>Los empleados son responsables de cuidar los equipos y su área de trabajo asignada. Los equipos deben apagarse al finalizar el horario laboral.</li>
                    <li>Los equipos de trabajo deben utilizarse exclusivamente para fines laborales. Cualquier otro uso resultará en un memorándum.</li>
                    <li>Al finalizar el horario laboral, es responsabilidad del empleado apagar los equipos, incluyendo pantallas y CPUs.</li>
                </ul>
            </div>
        </section>
        <section>
            <h2 onclick="toggleSection('breaks')"><i class="fas fa-coffee"></i> Refrigerios</h2>
            <div id="breaks" class="content">
                <p>El refrigerio es de una hora y el horario será coordinado con el supervisor, dentro del rango de las 12:00 p.m. hasta un máximo de 2:00 p.m.</p>
                <p>Solo se permite consumir snacks en la oficina, como papitas, fruta o galletas.</p>
            </div>
        </section>
        <section>
            <h2 onclick="toggleSection('final-note')"><i class="fas fa-flag"></i> Nota Final</h2>
            <div id="final-note" class="content">
                <p>Cualquier incumplimiento de las normas mencionadas resultará en un memorándum como llamado de atención. Si un empleado acumula tres memorándums en tres meses, será suspendido por tres días sin sueldo. Además, cualquier falta adicional después de una supervisión podría resultar en el cese de contrato, de acuerdo con los mecanismos establecidos en el decreto supremo 003-97-TR, texto único ordenado del decreto legislativo N°728, ley de productividad y competitividad laboral.</p>
                <p>Agradecemos su cooperación y compromiso para mantener un ambiente de trabajo eficiente y respetuoso.</p>
            </div>
        </section>
    </main>
    <script>
        function toggleSection(id) {
            var section = document.getElementById(id);
            if (section.style.display === "none" || section.style.display === "") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }
    </script>

   </div>
  
   <footer>
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-6 col-sm-6">
                        <h3>Contact Us</h3>
                        <ul class="location_icon">
                            <li><a href="#" class="address-link"><i class="fa fa-map-marker" aria-hidden="true">

                            </i>Av. República de Panamá #5768
                            </a>
                                    <br>
                                    <br>
                                    <a href="#" class="address-link">
                                   <i class="fa fa-map-marker" aria-hidden="true"></i> Av. Alfredo Benavides #1311
                                   </a>                             
                            </li>
                            <li>
                            <a href="mailto:malena@flinslaw.com" class="email-link">
                           <i class="fa fa-envelope" aria-hidden="true"></i> malena@flinslaw.com
                           </a>
                           </li>
                            
                            <li>
                            <a href="https://wa.me/+51917176158" class="phone-link">
                            <i class="fa fa-volume-control-phone" aria-hidden="true"></i> +51917176158
                            </a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <h3>Menus</h3>
                        <ul class="link_icon">
                            <li><a href="../index2" class="dropdown-item ">Home</a></li>
                            <li><a href="../news/news" class="dropdown-item ">News</a></li>
                            <li><a href="../calendar/calendar" class="dropdown-item ">Calendar</a></li>
                            <li class="dropdown-submenu">
                                <a href="#" class="dropdown-toggle dropdown-item active" data-toggle="dropdown">Rules</a>
                                <ul class="dropdown-menu">

                                <li><a class="dropdown-item" href="rulers2">English</a></li>
                                <li><a class="dropdown-item active" href="rulers">Spanish</a></li>

                                </ul>
                            </li>






                            <li class="dropdown-submenu">
                                <a href="#" class="dropdown-toggle dropdown-item" data-toggle="dropdown">Team</a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-submenu">
                                    <a class="dropdown-item" href="../team/itl/administrativearea">Administrative area</a>
                                        <a href="#" class="dropdown-toggle dropdown-item" data-toggle="dropdown">ITL</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="../team/itl/presuit">Settlement</a></li>
                                            <li><a class="dropdown-item" href="../team/itl/HOS">HOS</a></li>
                                            <li><a class="dropdown-item" href="../team/itl/filing">Filing</a></li>
                                            <li><a class="dropdown-item" href="../team/itl/legalassistant">Legal Assistance</a></li>
                                            <li><a class="dropdown-item" href="../team/itl/scheduling">Scheduling</a></li>
                                            <li><a class="dropdown-item" href="../team/itl/release">Release</a></li>
                                            <li><a class="dropdown-item" href="../team/itl/uploading">Uploading</a></li>
                                            <li><a class="dropdown-item" href="../team/itl/account">Account</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown-submenu">
                                        <a href="#" class="dropdown-toggle dropdown-item" data-toggle="dropdown">Claimpay</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="../team/claimpay/operations">Operations</a></li>
                                            <li><a class="dropdown-item" href="../team/claimpay/finance">Finance</a></li>
                                            <li><a class="dropdown-item" href="../team/claimpay/revenue">Revenue</a></li>
                                            <li><a class="dropdown-item" href="../team/claimpay/underwriting">Underwriting</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="../assistance/vistas/escritorio" class="dropdown-item">Remote assistance</a></li>
                            <!--
                            <li><a href="../contact" class="dropdown-item">Contact</a></li>
    -->
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <p>© 2024 All Rights Reserved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
   <!-- end footer -->
   <!-- Javascript files-->
   <script src="../js/jquery.min.js"></script>
   <script src="../js/popper.min.js"></script>
   <script src="../js/bootstrap.bundle.min.js"></script>
   <script src="../js/jquery-3.0.0.min.js"></script>
   <script src="../js/owl.carousel.min.js"></script>
   <!-- sidebar -->
   <script src="../js/jquery.mCustomScrollbar.concat.min.js"></script>
   <script src="../js/custom.js"></script>
   <script src="../assistance/vistas/js/calendar.js"></script>
   <script>
      // Handles the hover and click events for the submenus
      $(document).ready(function() {
         $('.dropdown-submenu a.dropdown-toggle').on('click', function(e) {
            if (!$(this).next().hasClass('show')) {
               $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
            }
            var $subMenu = $(this).next('.dropdown-menu');
            $subMenu.toggleClass('show');
            $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
               $('.dropdown-submenu .show').removeClass('show');
            });
            return false;
         });
      });
   </script>
</body>

</html>