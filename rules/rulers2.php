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
   <title>HR System</title>
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
   <link rel="icon" href="../images/icono.ico" type="image/gif" >
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
  color: #ffffff; /* Change this to your desired color */
  text-decoration: none; /* Remove underline */
}

.phone-link i {
  margin-right: 8px; /* Space between the icon and the text */
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
  color: #ffffff; /* Change this to your desired color */
  text-decoration: none; /* Remove underline */
}

.email-link i {
  margin-right: 8px; /* Space between the icon and the text */
}

        .conta_icon li {
            display: flex;
            align-items: center;
        }
        .address-link {
  display: inline-flex;
  align-items: center;
  color: #ffffff; /* Change this to your desired color */
  text-decoration: none; /* Remove underline */
}

.address-link i {
  margin-right: 8px; /* Space between the icon and the text */
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
    color: #FFD700; /* Golden color for the icons */
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
                                    <a class="dropdown-item active" href="#">English</a>
                                 </div>
                                 <div class="dropdown-submenu ">
                                    <a class="dropdown-item " href="../rules/rulers">Spanish</a>
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
                                 <a class="nav-link" href="../contact">Contact</a>
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
            <h2 onclick="toggleSection('introduction')"><i class="fas fa-info-circle"></i> Introduction</h2>
            <div id="introduction" class="content">
                <p>Dear colleagues,</p>
                <p>In order to promote a productive and harmonious work environment within the company, we present our Internal Regulations. We request that you read and strictly comply with them to ensure a positive and efficient work atmosphere.</p>
            </div>
        </section>
        <section>
            <h2 onclick="toggleSection('work-schedule')"><i class="fas fa-clock"></i> Work Schedule</h2>
            <div id="work-schedule" class="content">
                <p>The work schedule is from 8:00 a.m. to 6:00 p.m. or from 7:00 a.m. to 5:00 p.m., including a one-hour break for refreshments, with break shifts from 12:00 p.m. to 3:00 p.m. During this period, employees are expected to maintain professional and respectful conduct.</p>
                <ul class="styled-list">
                    <li>Punctuality is essential. In case of delay or absence due to exceptional circumstances, prior notice must be given to the supervisor and the human resources department.</li>
                    <li>Any entry after 7:20 a.m. or 8:20 a.m. (depending on the shift) will not be allowed, resulting in deductions and a warning record.</li>
                    <li>If an employee accumulates three or more tardies in a calendar month, a warning memorandum will be issued.</li>
                    <li>For absences due to illness, a medical certificate issued by the Ministry of Health or a clinic must be presented, along with the prescription and proof of purchase of medications.</li>
                    <li>In cases of medical leave, the employee must notify the direct supervisor, human resources, and management on the same day the medical certificate is obtained.</li>
                    <li>Upon returning to work, the medical certificate must be physically presented to human resources within a maximum period of 48 hours. Otherwise, the days will be deducted.</li>
                    <li>Medical appointments must be requested by email to the direct supervisor, with a copy to human resources and management, at least two days in advance. The time spent on a medical appointment will be compensated with work hours.</li>
                    <li>Personal leave must be requested at least two days in advance and will be evaluated by management. Approval will be communicated by email. Depending on the requested leave, it will be evaluated if the hours are compensated or if the corresponding deduction is made.</li>
                    <li>Vacations must be requested by email, copying the direct supervisor, management, and human resources, at least 15 days in advance. Approval is expected from management.</li>
                    <li>The home office work modality will only be approved by management. Interested employees must submit a request by email, with a copy to the supervisor and human resources. This will be exceptional.</li>
                    <li>Upon resignation or termination of the contract, employees are expected to hand over their pending tasks and return the furniture in good condition.</li>
                </ul>
            </div>
        </section>
        <section>
            <h2 onclick="toggleSection('conduct-rules')"><i class="fas fa-users"></i> Conduct Rules</h2>
            <div id="conduct-rules" class="content">
                <ul class="styled-list">
                    <li>Any disrespect or indiscipline towards colleagues, administrative or executive staff is considered serious and may result in termination of the contract. Courteous and respectful treatment among colleagues is expected.</li>
                    <li>All assigned tasks must be completed during working hours.</li>
                    <li>Any form of discrimination based on race, sexual orientation, religion, ideological orientation, age, or disability is prohibited, according to Peruvian regulations.</li>
                    <li>Harassment, whether sexual, verbal, psychological, physical, or cyber, is considered a serious offense and will be sanctioned according to Peruvian regulations.</li>
                    <li>The use of cell phones and similar devices is prohibited during working hours, unless necessary for specific work tasks.</li>
                    <li>Reporting to work under the influence of alcohol or illegal substances is strictly prohibited and may result in termination of the contract.</li>
                    <li>Clocking in and out is mandatory. Omission must be supported with evidence. Failure to clock in will result in a salary deduction.</li>
                    <li>Employees are expected to keep their work area clean and organized. Organic waste must be placed in bags before being thrown into the trash can.</li>
                    <li>Attire must be appropriate for the work environment, with no inappropriate clothing. Additionally, employees are expected to maintain proper personal hygiene.</li>
                </ul>
            </div>
        </section>
        <section>
            <h2 onclick="toggleSection('equipment-use')"><i class="fas fa-desktop"></i> Office Equipment Use</h2>
            <div id="equipment-use" class="content">
                <ul class="styled-list">
                    <li>The use of company equipment for personal purposes, such as storing music or images, is prohibited.</li>
                    <li>Access to personal emails, social networks, or online videos during working hours is not allowed.</li>
                    <li>Employees are responsible for taking care of the equipment and their assigned work area. Equipment must be turned off at the end of the working hours.</li>
                    <li>Work equipment should be used exclusively for work purposes. Any other use will result in a memorandum.</li>
                    <li>At the end of the working hours, it is the employee's responsibility to turn off the equipment, including screens and CPUs.</li>
                </ul>
            </div>
        </section>
        <section>
            <h2 onclick="toggleSection('breaks')"><i class="fas fa-coffee"></i> Breaks</h2>
            <div id="breaks" class="content">
                <p>The break is one hour, and the schedule will be coordinated with the supervisor, within the range of 12:00 p.m. to a maximum of 2:00 p.m.</p>
                <p>Only snacks are allowed in the office, such as chips, fruit, or cookies.</p>
            </div>
        </section>
        <section>
            <h2 onclick="toggleSection('final-note')"><i class="fas fa-flag"></i> Final Note</h2>
            <div id="final-note" class="content">
                <p>Any violation of the aforementioned rules will result in a memorandum as a warning. If an employee accumulates three memorandums in three months, they will be suspended for three days without pay. Additionally, any further violation after supervision could result in termination of the contract, according to the mechanisms established in Supreme Decree 003-97-TR, the consolidated text of Legislative Decree No. 728, the Productivity and Labor Competitiveness Law.</p>
                <p>We appreciate your cooperation and commitment to maintaining an efficient and respectful work environment.</p>
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

                                <li><a class="dropdown-item active" href="rulers2">English</a></li>
                                <li><a class="dropdown-item " href="rulers">Spanish</a></li>

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
                            <li><a href="../contact" class="dropdown-item ">Contact</a></li>
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
