<?php 
// activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
} else {
  require 'header.php';

    // Obtenemos el ID del usuario desde la sesión
    $user_id = isset($_SESSION['codigo_persona']) ? $_SESSION['codigo_persona'] : '';



?>
  <link rel="stylesheet" href="../public/css/estilos.css">  
<style>
.container-login {
  display: flex;
  justify-content: center; /* Centra horizontalmente */
  align-items: center; /* Centra verticalmente */
  height: 100vh; /* Ocupa toda la altura de la ventana */
}

.flex-container {
  display: flex;
  justify-content: center; /* Centra los elementos dentro del contenedor flex */
  align-items: center; /* Alinea los elementos en el centro verticalmente */
  width: 100%;
}

.masthead-content {
  flex: 0 0 auto; /* No permite que este contenedor crezca o se reduzca */
  margin-right: 20px; /* Añade espacio entre el reloj/fecha y el login */
}

.wrap-login {
  flex: 0 0 auto; /* No permite que este contenedor crezca o se reduzca */
}

.fecha, .reloj {
  margin: 10px;
}

.lockscreen-footer {
  margin-top: 20px;
}

.lockscreen-footer a {
  text-decoration: none;
  color: #007bff;
  font-size: 18px;
  transition: color 0.3s;
}

.lockscreen-footer a:hover {
  color: #0056b3;
}

h4 {
  margin: 0;
  font-size: 20px;
  font-weight: bold;
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
  color: #e08e0b;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  100% {
    transform: scale(1.05);
  }
}


/* Diseño de Marcador */


        

        .lockscreen-name h4 {
            color: #333;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .btn-asistencia {
            background-color: #f39c12;
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            font-size: 24px;
            margin: 0 auto;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-asistencia:hover {
            background-color: #e08e0b;
        }
        .fa-fingerprint {
            font-size: 48px;
        }



</style>

<div class="content-wrapper">
  <div class="container-login">
    <section class="content">
      <div class="flex-container">
        <!-- Sección de reloj y fecha -->
        <div class="masthead-content">
          <div class="container-fluid px-lg-0">
            <div class="widget" style="border: 3px solid; padding: 0.1px; background-color: #007bff; color: white;">
              <div class="fecha">
                <p id="diaSemana" class="diaSemana"></p>
                <p id="dia" class="dia"></p>
                <p>de</p>
                <p id="mes" class="mes"></p>
                <p>del</p>
                <p id="year" class="year"></p>
              </div>
              <div class="reloj">
                <p id="horas" class="horas">00</p>
                <p>:</p>
                <p id="minutos" class="minutos">00</p>
                <p>:</p>
                <div class="caja-segundos">
                  <p id="segundos" class="segundos">00</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sección de login -->
        <div class="wrap-login">
        <div class="lockscreen-wrapper">
            <?php //include '../ajax/asistenciam.php' ?>
            <div name="movimientos" id="movimientos"></div> 

            <div class="lockscreen-name">
              <h4>Por favor presionar sobre el botón</h4>
            </div>
            <form action="" name="formulario" id="formulario" method="POST">
                <input type="hidden" name="codigo_persona" id="codigo_persona" value="<?php echo htmlspecialchars($user_id); ?>" required>
                <button id="submitButton" type="submit" class="btn-asistencia">
                    <i class="fas fa-fingerprint"></i>
                </button>
            </form>
        </div>
    </div>



        
      </div>
    </section>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar el botón de envío
    const submitButton = document.getElementById('submitButton');

    // Escuchar clic en el botón de envío
    submitButton.addEventListener('click', function() {
        // Redirigir a asistencia.php después de 3 segundos (3000 milisegundos)
        setTimeout(function() {
            window.location.href = 'asistencia_inicio.php';
        }, 3000); 
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  function actualizarHora() {
    var fecha = new Date();
    var horas = fecha.getHours();
    var minutos = fecha.getMinutes();
    var segundos = fecha.getSeconds();
    
    // Agregar ceros a la izquierda si es necesario
    horas = (horas < 10 ? "0" : "") + horas;
    minutos = (minutos < 10 ? "0" : "") + minutos;
    segundos = (segundos < 10 ? "0" : "") + segundos;
    
    // Mostrar la hora en el formato deseado
    document.getElementById("horas").innerText = horas;
    document.getElementById("minutos").innerText = minutos;
    document.getElementById("segundos").innerText = segundos;

    // Mostrar los elementos de hora y minutos
    document.getElementById("horas").classList.remove("hidden");
    document.getElementById("minutos").classList.remove("hidden");
  }

  // Actualizar la hora cada segundo
  setInterval(actualizarHora, 1000);

  // Llamar a la función para que muestre la hora actual antes de iniciar el intervalo
  actualizarHora();
});
</script>

<?php 
  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/asistencia_inicio.js"></script>
  <script type="text/javascript" src="scripts/asistencia.js"></script>
  <script type="text/javascript" src="scripts/scriptsreloj.js"></script>

  <?php 
}
ob_end_flush();
?>
