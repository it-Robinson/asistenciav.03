<?php 
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';
 ?>
  <link rel="stylesheet" href="css/calendar.css">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>

  <div class="content-wrapper">
  <div class="container-login">
  <section class="content">
        <div id="calendar"></div>
        <br>
        <form id="eventForm" method="POST" action="add_event.php">
            <div class="form-group">
                <label for="title">Título:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="start">Fecha:</label>
                <input type="date" id="start" name="start" required>
            </div>
            <div class="form-group">
                <label for="color">Color:</label>
                <input type="color" id="color" name="color" required>
            </div>
            <button type="submit">Añadir Evento</button>
        </form>
    </div>
    
    </div>

  <!-- Modal -->
  <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle"></h2>
            <div id="message" style="color: red; display: none;"></div>
            <div class="form-group">
                <label for="editTitle">Nuevo Título:</label>
                <input type="text" id="editTitle" required>
            </div>
            <div class="form-group">
                <label for="editStart">Nueva Fecha:</label>
                <input type="date" id="editStart" required>
            </div>
            <div class="form-group">
                <label for="editColor">Nuevo Color:</label>
                <input type="color" id="editColor" required>
            </div>
            <button id="editButton">Editar Evento</button>
            <button id="deleteButton">Eliminar Evento</button>
        </div>
    </div>
<script src="js/calendar.js"></script>























<?php 

require 'footer.php';
 ?>
 <script src="scripts/asistencia.js"></script>
 <?php 
}

ob_end_flush();
  ?>