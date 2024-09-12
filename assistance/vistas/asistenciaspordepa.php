<?php 
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';
 ?>
 <style>
.title-container {
    display: flex; /* Usar flexbox para centrar */
    justify-content: center; /* Centrar horizontalmente */
    align-items: center; /* Centrar verticalmente */

}

.box-title {
    font-size: 3em; /* Aumenta el tamaño de la fuente */
    font-weight: bold; /* Hace el texto más grueso */
    text-align: center; /* (Opcional) Centrar el texto dentro del contenedor */
}
</style>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
<div class="title-container">
    <h1 class="box-title">Reporte por Area</h1>
</div>
  <div class="box-tools pull-right">
    
  </div>
</div>
<!--box-header-->
<!--centro-->
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistadopordepa" class="table table-striped table-bordered table-condensed table-hover">
  <thead>
      <!--
      <th>Opciones</th>
-->
      <th>Fecha</th>
      <th>Código</th>
      <th>Nombres</th>
      <th>Turno</th>       
<th style="background-color: green; color: white; text-align: center;">Entrada de Turno</th>
<th style="background-color: lightblue; color: black; text-align: center;">Entrada de Break</th>
<th style="background-color: orange; color: black; text-align: center;">Salida de Break</th>
<th style="background-color: red; color: white; text-align: center;">Salida de Turno</th>
 
    </thead>
    <tbody>
    </tbody>
  
       <!--  <tfoot>
 
      <th>Opciones</th>

      <th>Código</th>
      <th>Nombres</th>
      <th>Área</th>
      <th>Entrada de Turno</th>
      <th>Entrada de Break</th>
      <th>Salida de Break</th>
      <th>Salida de Turno</th>
      <th>Fecha</th>
    </tfoot>   
-->
  </table>
</div>

      </div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
<?php 

require 'footer.php';
 ?>
 <script src="scripts/asistencia.js"></script>
 <?php 
}

ob_end_flush();
  ?>
