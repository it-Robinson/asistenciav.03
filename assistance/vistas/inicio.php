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
  body {
    font-family: 'Arial', sans-serif;
    background-color: #f1f1f1;
    margin: 0;
    padding: 0;
  }

  .content-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
  }

  .masthead-content {
    width: 100%;
    max-width: 600px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    background-color: #fff;
    border-radius: 20px;
    text-align: center;
  }

  .boton-container {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
  }

  .boton {
    display: inline-block;
    width: 200px;
    padding: 20px;
    margin: 10px;
    font-size: 18px;
    color: white;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
  }

  .boton-izquierda {
    background-color: #007bff;
  }

  .boton-derecha {
    background-color: #28a745;
  }

  .boton:hover {
    opacity: 0.9;
    transform: translateY(-2px);
  }

  .emoji {
    margin-right: 10px;
  }

  .lockscreen-name {
    margin-top: 20px;
  }

  .fingerprint-container {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    padding: 20px;
    border: 5px solid #007bff;
    border-radius: 20px;
  }

  .fingerprint-icon {
    font-size: 100px;
    color: #007bff;
    margin-right: 20px;
  }

  .clock-icon {
    font-size: 70px;
    color: #007bff;
  }

  #clock {
    font-size: 30px;
    font-weight: bold;
  }
</style>

<div class="content-wrapper">
<div class="container-login">
  <section class="content">
  
    <div class="masthead-content">
      <div class="lockscreen-name">
        <!-- Contenedor de huella digital y reloj -->
        <div class="fingerprint-container">
          <div class="fingerprint-icon">
            <i class="fas fa-fingerprint"></i>
          </div>
          <div class="clock-icon">
            <i class="fas fa-clock"></i>
            <div id="clock"></div>
          </div>
        </div>
        <h4>Por favor seleccionar una opción:</h4>
      </div>
      <div class="boton-container">
        <button class="boton boton-izquierda" onclick="asistenciaConBreak()">
          <span class="emoji">🍽️</span> Asistencia con Break
        </button>
        <button class="boton boton-derecha" onclick="asistenciaSinBreak()">
          <span class="emoji">🚫</span> Asistencia sin Break
        </button>
      </div>
    </div>
    
  </section>
  </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Font Awesome -->
<script>
  function asistenciaConBreak() {
    window.location.href = 'asistencia_inicio.php';
  }

  function asistenciaSinBreak() {
    window.location.href = 'asistenciasnbreak.php';
  }

  function updateClock() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;
    var timeString = hours + ':' + minutes + ':' + seconds;
    document.getElementById('clock').innerHTML = timeString;
  }

  setInterval(updateClock, 1000); // Actualiza el reloj cada segundo
  updateClock(); // Inicializa el reloj
</script>

<?php 
  require 'footer.php';
}
ob_end_flush();
?>
