<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
}else{

 
require 'header.php';
require_once('../modelos/Usuario.php');
  $usuario = new Usuario();
  $rsptan = $usuario->cantidad_usuario();
  $reg=$rsptan->fetch_object();
  $reg->nombre;
?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="panel-body">



   <!--ADMINISTRADOR -->

<?php if ($_SESSION['tipousuario']=='Administrador') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-green">
    
    <a href="asistencia.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Reporte General</strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="fa fa-bar-chart" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>


<?php if ($_SESSION['tipousuario']=='Administrador') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-orange">
    <div class="inner">
      <h4 style="font-size: 20px;">
        <strong>Empleados:   </strong>
      </h4>
      <p>Total <?php echo $reg->nombre; ?></p>
    </div>
    <div class="icon">
       <i class="fa fa-users" aria-hidden="true"></i>
    </div>
    <a href="usuario.php" class="small-box-footer">Agregar <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>
<?php } ?>


<?php if ($_SESSION['tipousuario']=='Administrador') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-aqua">
    
    <a href="rptasistencia.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Reporte de asistencias por fecha </strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>

<?php if ($_SESSION['tipousuario']=='Administrador') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-yellow">
    
    <a href="ListadoPermisos.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Listado de Permisos</strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
    <i class="fa fa-file" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>

<?php if ($_SESSION['tipousuario']=='Administrador') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-red">
    
    <a href="calendar.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Calendario</strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
    <i class="bi bi-calendar3" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>

 <!--EMPLEADO -->
 <?php 
 
 if (!in_array($_SESSION['tipousuario'], $puestosExcluidos)) { 
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-red">
    
    <a href="inicio.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Marcador</strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="bi bi-alarm" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>


<?php 
if (!in_array($_SESSION['tipousuario'], $puestosExcluidos)) { 
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-green">
    
    <a href="asistenciau.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Reporte General</strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="fa fa-bar-chart" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>


<?php 
if (!in_array($_SESSION['tipousuario'], $puestosExcluidos)) { 
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-aqua">
    
    <a href="rptasistenciau.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Reporte por fechas</strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="fa fa-calendar" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>

<?php 
$puestosExcluidos = ['Team Leader', 'Administrador', 'Gerencia', 'RRHH'];

if (!in_array($_SESSION['tipousuario'], $puestosExcluidos)) { 
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-yellow">
    
    <a href="formularioPermiso.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Solicitud de Permisos</strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
    <i class="fa fa-file" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>





<!--
  TEAM LEADER
-->
<?php if ($_SESSION['tipousuario'] =='Team Leader')  {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-orange">
    
    <a href="asistenciau.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Mi lista de asistencias</strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="bi bi-person" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>





<?php if ($_SESSION['tipousuario'] =='Team Leader')  {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-aqua">
    
    <a href="asistenciaspordepa.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Reporte de Asistencia del Área </strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="bi bi-people" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>

<?php if ($_SESSION['tipousuario']=='Team Leader') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-yellow">
    
    <a href="formularioPermiso.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Solicitud de Permisos</strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
    <i class="fa fa-file" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>


<!--
  OTROS USUARIOS
-->

<?php if ($_SESSION['tipousuario'] =='Gerencia y RRHH')  {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-green">
    
    <a href="asistencia.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Reporte General</strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="fa fa-bar-chart" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>

<?php if ($_SESSION['tipousuario']=='Gerencia y RRHH') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-aqua">
    
    <a href="rptasistencia.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Reporte de asistencias por fecha </strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>

<?php if ($_SESSION['tipousuario']=='Gerencia y RRHH') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-yellow">
    
    <a href="ListadoPermisos.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Listado de Permisos</strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
    <i class="fa fa-file" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>


<?php if ($_SESSION['tipousuario']=='Team Leader') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-yellow">
    
    <a href="ListadoPermisos.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Listado de Permisos</strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
    <i class="bi bi-inbox-fill" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>





<!--fin centro-->

      </div>
      
      </div>
      
      </div>
      <!-- /.box -->
      
    </section>
    <!-- /.content -->
  




      
      </div>
  
  
<?php
require 'footer.php'; 
}
ob_end_flush();
?>