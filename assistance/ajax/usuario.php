<?php 
session_start();
require_once "../modelos/Usuario.php";

$usuario=new Usuario();

$idusuarioc=isset($_POST["idusuarioc"])? limpiarCadena($_POST["idusuarioc"]):"";
$clavec=isset($_POST["clavec"])? limpiarCadena($_POST["clavec"]):"";
$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$apellidos=isset($_POST["apellidos"])? limpiarCadena($_POST["apellidos"]):"";
$login=isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$iddepartamento=isset($_POST["iddepartamento"])? limpiarCadena($_POST["iddepartamento"]):"";
$idtipousuario=isset($_POST["idtipousuario"])? limpiarCadena($_POST["idtipousuario"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$codigo_persona=isset($_POST["codigo_persona"])? limpiarCadena($_POST["codigo_persona"]):"";
$password=isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$usuariocreado=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$idmensaje=isset($_POST["idmensaje"])? limpiarCadena($_POST["idmensaje"]):"";
$tipo_turno=isset($_POST["tipo_turno"])? limpiarCadena($_POST["tipo_turno"]):"";
$empresa=isset($_POST["empresa"])? limpiarCadena($_POST["empresa"]):"";
$extension=isset($_POST["extension"])? limpiarCadena($_POST["extension"]):"";
$primary_phone_number=isset($_POST["primary_phone_number"])? limpiarCadena($_POST["primary_phone_number"]):"";
$fecha_nacimiento=isset($_POST["fecha_nacimiento"])? limpiarCadena($_POST["fecha_nacimiento"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':

		if (!file_exists($_FILES['imagen']['tmp_name'])|| !is_uploaded_file($_FILES['imagen']['tmp_name']))  
		{
			$imagen=$_POST["imagenactual"];
		}else
		{

			$ext=explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
			 {

			   $imagen = round(microtime(true)).'.'. end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
		 	}
		}

		//Hash SHA256 para la contraseña
		$clavehash=hash("SHA256", $password);

		if (empty($idusuario)) {
			$idusuario=$_SESSION["idusuario"];
			$rspta=$usuario->insertar($nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$clavehash,$imagen,$usuariocreado,$tipo_turno,$codigo_persona,$fecha_nacimiento,$empresa,$extension,$primary_phone_number);
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar todos los datos del usuario";
		}
		else {
			$rspta=$usuario->editar($idusuario,$nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$imagen,$usuariocreado,$tipo_turno,$codigo_persona,$fecha_nacimiento,$empresa,$extension,$primary_phone_number);
			echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
		}
	break;
	

	case 'desactivar':
		$rspta=$usuario->desactivar($idusuario);
		echo $rspta ? "Datos eliminados correctamente" : "No se pudo desactivar los datos";
	break;

	case 'activar':
		$rspta=$usuario->activar($idusuario);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
	break;
	
	case 'mostrar':
		$rspta=$usuario->mostrar($idusuario);
		echo json_encode($rspta);
	break;

	case 'editar_clave':
		$clavehash=hash("SHA256", $clavec);

		$rspta=$usuario->editar_clave($idusuarioc,$clavehash);
		echo $rspta ? "Password actualizado correctamente" : "No se pudo actualizar el password";
	break;

	case 'mostrar_clave':
		$rspta=$usuario->mostrar_clave($idusuario);
		echo json_encode($rspta);
	break;
	
	case 'listar':
		$rspta=$usuario->listar();
		//declaramos un array
		$data=Array();


		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => ($reg->estado) ? 
					'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>' . 
					' ' . 
					'<button class="btn btn-info btn-xs" onclick="mostrar_clave('.$reg->idusuario.')"><i class="fa fa-key"></i></button>' . 
					' ' . 
					'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idusuario.')"><i class="fa fa-close"></i></button>' :
					'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>' . 
					' ' . 
					'<button class="btn btn-info btn-xs" onclick="mostrar_clave('.$reg->idusuario.')"><i class="fa fa-key"></i></button>' . 
					' ' . 
					'<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idusuario.')"><i class="fa fa-check"></i></button>',
				"1" => '<div style="text-align: center;">' . $reg->fechacreado . '</div>',
				"2" => '<div style="text-align: center;">' . $reg->empresa . '</div>',
				"3" => '<div style="text-align: center;">' . $reg->nombre_departamento . '</div>',				
				"4" => '<div style="text-align: center;">' . $reg->tipo_turno . '</div>',
				"5" => '<div style="text-align: center;">' . $reg->nombre . '</div>',
				"6" => '<div style="text-align: center;">' . $reg->apellidos . '</div>',
				"7" => '<div style="text-align: center;">' . $reg->fecha_nacimiento . '</div>',
				"8" => '<div style="text-align: center;">' . $reg->login . '</div>',
				"9" => '<div style="text-align: center;">' . $reg->email . '</div>',
				"10" => '<div style="text-align: center;">' . $reg->extension . '</div>',
				"11" => '<div style="text-align: center;">' . $reg->primary_phone_number . '</div>',
				"12" => "<div style='text-align: center;'><img src='../files/usuarios/".$reg->imagen."' height='50px' width='50px' style='display: inline-block;'></div>",
			);
		}
		

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;


	case 'verificar':
		//validar si el usuario tiene acceso al sistema
		$logina=$_POST['logina'];
		$clavea=$_POST['clavea'];

		//Hash SHA256 en la contraseña
		$clavehash=hash("SHA256", $clavea);
	
		$rspta=$usuario->verificar($logina, $clavehash);

		$fetch=$rspta->fetch_object();

		if (isset($fetch)) 
		{
			# Declaramos la variables de sesion
			$_SESSION['idusuario']=$fetch->idusuario;
			$id=$fetch->idusuario;
			$_SESSION['nombre']=$fetch->nombre;
			$_SESSION['codigo_persona']=$fetch->codigo_persona;
			$_SESSION['apellidos']=$fetch->apellidos;
			$_SESSION['imagen']=$fetch->imagen;
			$_SESSION['login']=$fetch->login;
			$_SESSION['tipousuario']=$fetch->tipousuario;
			$_SESSION['nombre_departamento']=$fetch->nombre_departamento;
			$_SESSION['departamento']=$fetch->iddepartamento;
			$_SESSION['extension']=$fetch->extension;
			$_SESSION['primary_phone_number']=$fetch->primary_phone_number;



			require "../config/Conexion.php";

			$sql="UPDATE usuarios SET iteracion='1' WHERE idusuario='$id'";
			echo $sql; 
	 		ejecutarConsulta($sql);	 		

		}

		echo json_encode($fetch);

	break;

	case 'salir':
			
			$id=$_SESSION['idusuario'];
			$sql="UPDATE usuarios SET iteracion='0' WHERE idusuario='$id'";
			echo $sql; 
	 		ejecutarConsulta($sql);	 		


		//Limpiamos las variables de sesión   
        session_unset();
        //Destruìmos la sesión
        session_destroy();
        //Redireccionamos al login
        header("Location: ../vistas/login.html");

	break;

}
?>