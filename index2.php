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
        header("Location: assistance/vistas/login.html"); // Redirigir al login
        exit();
    }
}

// Actualizar el tiempo de última actividad
$_SESSION['ultima_actividad'] = time();

// Verificar si la sesión está iniciada
if (!isset($_SESSION['nombre'])) {
    // Redirigir al login si no está autenticado
    header("Location: assistance/vistas/login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

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
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Popup Cumpleaños -->
    <link rel="stylesheet" href="css/popcumple.css">    
    <!-- Responsive-->
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <!-- fevicon -->
    <link rel="icon" href="images/icono.ico" type="image/gif" />
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
    <!-- Tweaks for older IEs-->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fireworks-js@1.0.1/dist/fireworks.min.js"></script>
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
/* Ajustamos el tamaño del popup y le agregamos scroll */
#birthdayPopup {
    width: 90%;
    max-width: 600px;
    height: auto;
    max-height: 80vh; /* Limitar la altura del popup para que no ocupe toda la pantalla */
    margin: 20px auto;
    background-color: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    overflow-y: auto; /* Habilitar el scroll vertical */
}

/* Lista de cumpleañeros */
.birthday-list {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px; /* Espacio entre cada cumpleañero */
    margin-bottom: 20px;
}

/* Tarjeta de cada cumpleañero */
.birthday-person {
    display: flex;
    align-items: center;
    background-color: #f9f9f9;
    border-radius: 10px;
    padding: 15px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Imagen más grande y ajustada */
.birthday-image {
    width: 100px; /* Imagen más grande */
    height: 100px;
    border-radius: 50%;
    margin-right: 15px;
    object-fit: cover; /* Asegura que la imagen no se deforme */
}

/* Información de cada cumpleañero */
.birthday-info {
    font-size: 14px;
    line-height: 1.5;
}

/* Estilos para el título */
h2 {
    text-align: center;
    color: #f39c12;
}

/* Estilos para el texto general */
p {
    text-align: center;
    font-size: 16px;
    color: #555;
}
/* Estilo para el mini-popup */
.scroll-message-popup {
    position: absolute; /* Ahora será absoluto dentro del contenedor */
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.7);
    color: #fff;
    padding: 10px 20px;
    border-radius: 20px;
    font-size: 14px;
    text-align: center;
    z-index: 9999; /* Asegurarnos de que siempre esté encima */
    display: none; /* Oculto por defecto */
}

/* Estilo para el botón de cierre ("Cerrar") */
.close {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 10px 20px;
    font-size: 16px;
    background-color: #ff3b3b; /* Color rojo para que resalte */
    color: #ff3b3b; /* Texto blanco */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    z-index: 100; /* Asegurarnos de que esté al frente */
}

.close:hover {
    background-color: #e74c3c; /* Cambiar a un rojo más oscuro cuando pases el mouse */
    color: #fff; /* Texto blanco */
}

.close:focus {
    outline: none; /* Evitar el borde azul cuando está enfocado */
}



    </style>

</head>
<!-- body -->

<body class="main-layout">
    <div id="birthdayPopup" class="popup">
        <div class="popup-content">
        <button class="close">Close</button>

            <div id="scrollMessagePopup" class="scroll-message-popup">⬇️ Desplázate hacia abajo para ver más ⬇️</div> <!-- Mini-popup -->

            <p id="birthdayMessage"></p>
        </div>
    </div>




    <!-- loader  -->
    <div class="loader_bg">
        <div class="loader"><img src="images/loading.gif" alt="#" /></div>
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
                    <a class="logo" href="#"><img src="images/logo1.jpg" alt="Logo Left" /></a>
                </div>
                <div class="col-md-6 text-center">
                    <ul class="conta_icon">
                        <li><a href="https://wa.me/+51917176158"><i class="fa fa-phone" aria-hidden="true"></i> Call Us: +51917176158</a></li>
                        <li><a href="mailto:malena@flinslaw.com"><i class="fa fa-envelope" aria-hidden="true"></i> malena@flinslaw.com</a></li>
                    </ul>
                </div>
                <div class="col-md-3 text-right">
                    <a class="logo" href="#"><img src="images/logo2.png" alt="Logo Right" /></a>
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
                                    <li class="nav-item active">
                                        <a class="nav-link" href="index2">Home</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="news/news">News</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="calendar/calendar">Calendar</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Rules
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="rules/rulers2">English</a>
                                            <a class="dropdown-item" href="rules/rulers">Spanish</a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Team
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <div class="dropdown-submenu">
                                                <a class="dropdown-item" href="team/itl/administrativearea">Administrative area</a>
                                                <a class="dropdown-item dropdown-toggle" href="#">ITL</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="team/itl/presuit">Settlement</a>
                                                    <a class="dropdown-item" href="team/itl/HOS">HOS</a>
                                                    <a class="dropdown-item" href="team/itl/filing">Filing</a>
                                                    <a class="dropdown-item" href="team/itl/legalassistant">Legal Assistant</a>
                                                    <a class="dropdown-item" href="team/itl/scheduling">Scheduling</a>
                                                    <a class="dropdown-item" href="team/itl/release">Release</a>
                                                    <a class="dropdown-item" href="team/itl/uploading">Uploading</a>
                                                    <a class="dropdown-item" href="team/itl/account">Account</a>
                                                </div>
                                            </div>
                                            <div class="dropdown-submenu">
                                                <a class="dropdown-item dropdown-toggle" href="#">Claimpay</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="team/claimpay/operations">Operations</a>
                                                    <a class="dropdown-item" href="team/claimpay/finance">Finance</a>
                                                    <a class="dropdown-item" href="team/claimpay/revenue">Revenue</a>
                                                    <a class="dropdown-item" href="team/claimpay/underwriting">Underwriting</a>                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="assistance/vistas/escritorio">Remote assistance</a>
                                    </li>
                                    <!--
                                    <li class="nav-item">
                                        <a class="nav-link" href="contact">contact</a>
                                    </li>
-->
                                    <li class="nav-item">
    <a href="assistance/ajax/usuario.php?op=salir" class="nav-link logout-btn">Log out</a>
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
    <!-- banner -->
    <section class="banner_main">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-lg-7">
                    <div class="text-bg">
                        <h1>Welcome to the Human Resources System of <br>ITL and ClaimPay</h1>
                        <span></span>
                        <p>Hello everyone,

                            We are pleased to welcome you to our new Human Resources System. Here you will be able to manage attendance, check news, events, and access company rules and policies.

                            We hope this system will enhance your work experience. If you need assistance, please do not hesitate to contact the Human Resources team.

                            Thank you and welcome!
                        </p>
                        <a href="#">ITL and ClaimPay Human Resources Team</a>
                    </div>
                </div>
                <div class="col-md-5 col-lg-5">
                    <div class="ban_img">
                        <figure><img src="images/ba_ing.png" alt="#" /></figure>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                            <li><a href="index2" class="dropdown-item active">Home</a></li>
                            <li><a href="news/news" class="dropdown-item">News</a></li>
                            <li><a href="calendar/calendar" class="dropdown-item">Calendar</a></li>
                            <li class="dropdown-submenu">
                                <a href="#" class="dropdown-toggle dropdown-item" data-toggle="dropdown">Rules</a>
                                <ul class="dropdown-menu">

                                <li><a class="dropdown-item" href="rules/rulers2">English</a></li>
                                <li><a class="dropdown-item" href="rules/rulers">Spanish</a></li>

                                </ul>
                            </li>
                            <li class="dropdown-submenu">
                                <a href="#" class="dropdown-toggle dropdown-item" data-toggle="dropdown">Team</a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-submenu">
                                         <a class="dropdown-item" href="team/itl/administrativearea">Administrative area</a>
                                        <a href="#" class="dropdown-toggle dropdown-item" data-toggle="dropdown">ITL</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="team/itl/presuit">Settlement</a></li>
                                            <li><a class="dropdown-item" href="team/itl/HOS">HOS</a></li>
                                            <li><a class="dropdown-item" href="team/itl/filing">Filing</a></li>
                                            <li><a class="dropdown-item" href="team/itl/legalassistant">Legal Assistance</a></li>
                                            <li><a class="dropdown-item" href="team/itl/scheduling">Scheduling</a></li>
                                            <li><a class="dropdown-item" href="team/itl/release">Release</a></li>
                                            <li><a class="dropdown-item" href="team/itl/uploading">Uploading</a></li>
                                            <li><a class="dropdown-item" href="team/itl/account">Account</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown-submenu">
                                        <a href="#" class="dropdown-toggle dropdown-item" data-toggle="dropdown">Claimpay</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="team/claimpay/operations">Operations</a></li>
                                            <li><a class="dropdown-item" href="team/claimpay/finance">Finance</a></li>
                                            <li><a class="dropdown-item" href="team/claimpay/revenue">Revenue</a></li>
                                            <li><a class="dropdown-item" href="team/claimpay/underwriting">Underwriting</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="assistance/vistas/escritorio" class="dropdown-item">Remote assistance</a></li>
                            <!--
                            <li><a href="contact" class="dropdown-item ">Contact</a></li>
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
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.0.0.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <!-- sidebar -->
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/custom.js"></script>
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
        <canvas id="fireworks"></canvas>
        <script src="js/popcumple.js"></script>

</body>

</html>
