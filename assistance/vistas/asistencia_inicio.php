<?php 
// activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
} else {
  require 'header.php';
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
  color: white;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  100% {
    transform: scale(1.05);
  }
}

/* Diseño Ticket */
.btn-ticket {
  border-radius: 20px; /* Hacemos que los botones tengan bordes redondeados */
  padding: 10px 20px; /* Añadimos espacio interno */
  margin: 5px; /* Añadimos margen entre los botones */
  font-size: 16px; /* Tamaño del texto */
  font-weight: bold; /* Texto en negrita */
  text-transform: uppercase; /* Texto en mayúsculas */
}

.ticket-buttons {
  text-align: center; /* Centramos los botones */
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
            <div name="movimientos" id="movimientos"></div> 
            <div class="lockscreen-logo">
              <h3 class="text-center" style="border: 3px solid blue; font-size: 0.6em; color: #fff; background-color: #007bff; padding: 1px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-weight: bold;">
                ASISTENCIA CON BREAK
                <img src="..\files\Imagenes/coffee_2146193.png" style="width: 80px; height: auto; margin-left: 10px;">
              </h3>
            </div>

            <div class="lockscreen-logo" style="border: 3px solid blue; padding: 10px; background-color: #007bff; color: white;">
              <div class="lockscreen-name">  
                <h4>**Solo seleccionar una vez:**</h4>
              </div>
              <div class="ticket-buttons">
    <a href="asistenciaet.php" class="btn btn-success btn-ticket" id="btn-entrada-turno" style="color: white;">Entrada de Turno</a>
    <a href="asistenciaeb.php" class="btn btn-info btn-ticket" id="btn-entrada-break" style="color: white;">Entrada de Break</a>
    <a href="asistenciasb.php" class="btn btn-warning btn-ticket" id="btn-salida-break" style="color: white;">Salida de Break</a>
    <a href="asistenciast.php" class="btn btn-danger btn-ticket" id="btn-salida-turno" style="color: white;">Salida de Turno</a>
</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const buttons = ['btn-entrada-turno', 'btn-entrada-break', 'btn-salida-break', 'btn-salida-turno'];

    // Obtener la fecha actual
    const today = new Date().toISOString().split('T')[0];

    // Comprobar si la fecha almacenada es diferente a la actual
    const storedDate = localStorage.getItem('date');
    if (!storedDate || storedDate !== today) {
        // Si la fecha es diferente o no existe, restablecer los botones
        buttons.forEach(buttonId => {
            localStorage.removeItem(buttonId);
        });
        // Actualizar la fecha en localStorage
        localStorage.setItem('date', today);
    }

    // Deshabilitar los botones si están en localStorage
    buttons.forEach(buttonId => {
        const button = document.getElementById(buttonId);
        if (localStorage.getItem(buttonId) === 'disabled') {
            button.setAttribute('disabled', 'disabled');
            button.classList.add('disabled-button');

            // Asegurarse de que el botón no sea clickeable
            button.style.pointerEvents = 'none';
        }
    });

    // Manejar el clic en los botones
    buttons.forEach(buttonId => {
        const button = document.getElementById(buttonId);
        button.addEventListener('click', function(event) {
            event.preventDefault();

            // Deshabilitar el botón y guardar el estado en localStorage
            button.setAttribute('disabled', 'disabled');
            button.classList.add('disabled-button');
            localStorage.setItem(buttonId, 'disabled');

            // Asegurarse de que el botón no sea clickeable
            button.style.pointerEvents = 'none';

            setTimeout(() => {
                window.location.href = button.href;
            }, 100);
        });
    });
});


</script>






<?php 
  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/asistencia_inicio.js"></script>
  <script type="text/javascript" src="scripts/scriptsreloj.js"></script>

  <?php 
}
ob_end_flush();
?>
