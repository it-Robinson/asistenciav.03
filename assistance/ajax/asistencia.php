<?php 
require_once "../modelos/Asistencia.php";
if (strlen(session_id())<1) 
	session_start();
$asistencia=new Asistencia();

$codigo_persona=isset($_POST["codigo_persona"])? limpiarCadena($_POST["codigo_persona"]):"";
$iddepartamento=isset($_POST["iddepartamento"])? limpiarCadena($_POST["iddepartamento"]):"";
$idasistencia=isset($_POST["idasistencia"])? limpiarCadena($_POST["idasistencia"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$result=$asistencia->verificarcodigo_persona($codigo_persona);

      	if($result > 0) {
	date_default_timezone_set('America/Lima');
      		$fecha = date("Y-m-d");
			$hora = date("H:i:s");

			$result2=$asistencia->seleccionarcodigo_persona($codigo_persona);
			   
     		$par = abs($result2%2);

          if ($par == 0){ 
                              
                $tipo = "Entrada";
        		$rspta=$asistencia->registrar_entrada($codigo_persona,$tipo);
    			//$movimiento = 0;
    			echo $rspta ? '<h3><strong>Nombres: </strong> '. $result['nombre'].' '.$result['apellidos'].'</h3><div class="alert alert-success"> Ingreso registrado '.$hora.'</div>' : 'No se pudo registrar el ingreso';
   		  }else{ 
                $tipo = "Salida";
         		$rspta=$asistencia->registrar_salida($codigo_persona,$tipo);
     			//$movimiento = 1;
     			echo $rspta ? '<h3><strong>Nombres: </strong> '. $result['nombre'].' '.$result['apellidos'].'</h3><div class="alert alert-danger"> Salida registrada '.$hora.'</div>' : 'No se pudo registrar la salida';             
        } 
        } else {
		         echo '<div class="alert alert-danger">
                       <i class="icon fa fa-warning"></i> No hay empleado registrado con esa código...!
                         </div>';
        }

	break;

	
	case 'mostrar':
		$rspta=$asistencia->mostrar($idasistencia);
		echo json_encode($rspta);
	break;

	case 'desactivar':
		$rspta = $asistencia->desactivar($idasistencia);
		if ($rspta) {
			echo "Datos eliminados correctamente";
			// Agregar código JavaScript para actualizar la página automáticamente
			echo "<script>window.location.reload();</script>";
		} else {
			echo "No se pudo desactivar los datos";
		}
		break;


		case 'actualizarCampo':
			$id = $_POST['id'];
			$column = $_POST['column'];
			$value = $_POST['value'];
		
			// Asegúrate de validar y sanitizar los datos antes de usarlos en la consulta
			$id = intval($id);
			$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
		
			// Array de mapeo de nombres de columnas a valores de tipo
			$mapa_columnas = array(
				'Ingreso_a_Turno' => 'Entrada de Turno',
				'Ingreso_a_Break' => 'Entrada de Break',
				'Salida_a_Break' => 'Salida de Break',
				'Salida_de_Turno' => 'Salida de Turno',
			);
		
			// Array de mapeo para el caso en que el valor esté vacío
			$mapa_tipo_vacio = array(
				'Ingreso_a_Turno' => 'Entrada de Turno',
				'Ingreso_a_Break' => 'Entrada de Turno',
				'Salida_a_Break' => 'Entrada de Break',
				'Salida_de_Turno' => 'Salida de Break',
			);
		
			// Validar que el nombre de la columna contenga solo caracteres permitidos
			if (preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
				// Obtener información actual de la fila
				$sql_check = "SELECT * FROM asistencia WHERE idasistencia = $id";
				$result_check = $conexion->query($sql_check);
		
				if ($result_check && $result_check->num_rows > 0) {
					$row = $result_check->fetch_assoc();
		
					// Verificar si la columna Salida_de_Turno ya tiene un valor y no se está modificando
					if (!is_null($row['Salida_de_Turno']) && $column !== 'Tipo') {
						// Permitir la modificación de otros campos excepto Tipo cuando Salida_de_Turno tiene valor
						$sql = "UPDATE asistencia SET $column = ";
		
						// Añadir valor según si es NULL o no
						if (empty($value) || $value === 'No Marco') {
							$sql .= "NULL";
						} else {
							$sql .= "'$value'";
						}
		
						$sql .= " WHERE idasistencia = $id";
		
						// Ejecutar la consulta
						$rspta = $conexion->query($sql);
		
						if ($rspta) {
							echo json_encode("Actualización exitosa");
						} else {
							echo json_encode("Error al actualizar");
						}
					} else {
						// Actualizar tipo basado en el nombre de la columna y si el valor es NULL
						if (empty($value) || $value === 'No Marco') {
							// Verificar si las demás columnas adelante están vacías
							$all_columns_ahead_empty = true;
							$column_found = false;
		
							foreach ($mapa_columnas as $col => $desc) {
								if ($col == $column) {
									$column_found = true;
									continue; // Saltar la columna actual
								}
								if ($column_found && !empty($row[$col])) {
									$all_columns_ahead_empty = false;
									break;
								}
							}
		
							if ($all_columns_ahead_empty) {
								// Actualizar tipo basado en el nombre de la columna
								if (array_key_exists($column, $mapa_tipo_vacio)) {
									$tipo = $mapa_tipo_vacio[$column];
								} else {
									echo json_encode("Nombre de columna no reconocido");
									exit;
								}
							} else {
								// Mantener el tipo actual si las columnas adelante no están vacías
								$tipo = $row['tipo'];
							}
		
							$value = 'NULL'; // Asignar NULL a la columna en la base de datos
						} else {
							// En caso de que $value no esté vacío, escapar el valor para usar en SQL
							$value = "'" . $conexion->real_escape_string($value) . "'";
							// Obtener el valor formateado para tipo basado en el nombre de la columna
							if (array_key_exists($column, $mapa_columnas)) {
								$tipo = $mapa_columnas[$column];
							} else {
								// Manejar el caso donde el nombre de la columna no esté en el mapeo
								echo json_encode("Nombre de columna no reconocido");
								exit;
							}
						}
		
						// Construir la consulta SQL dinámicamente
						$sql = "UPDATE asistencia SET $column = $value, tipo = '$tipo' WHERE idasistencia = $id";
		
						// Ejecutar la consulta
						$rspta = $conexion->query($sql);
		
						if ($rspta) {
							echo json_encode("Actualización exitosa");
						} else {
							echo json_encode("Error al actualizar");
						}
					}
				} else {
					echo json_encode("No se encontró la fila con el id proporcionado");
				}
			} else {
				echo json_encode("Nombre de columna inválido");
			}
			break;
		
		
		
		
		
		
		
		
		
		
		
		
		
		





	case 'listar':
		$rspta=$asistencia->listar();
		//declaramos un array
		$data=Array();


		while ($reg=$rspta->fetch_object()) {
			// Aquí integramos el cambio para la columna "4"
			$color_entrada_turno = '';
			$color_texto_entrada_turno='';
			switch ($reg->Ingreso_a_Turno) {
				case 'No Marco':
					$color_entrada_turno = 'red';
					$color_texto_entrada_turno = 'white';
					break;
				default:
					// Si la hora de entrada a turno no es "No Marco", no se aplican estilos
					break;
			}
			
			$color_salida_turno = '';
			$color_texto_salida_turno ='';
			switch ($reg->Salida_de_Turno) {
				case 'No Marco':
					$color_salida_turno = 'red';
					$color_texto_salida_turno = 'white';
					break;
				default:
					// Si la hora de salida de turno no es "No Marco", no se aplican estilos
					break;
			}
			
			$color_entrada_break = '';
			$color_texto_entrada_break ='';
			switch ($reg->Ingreso_a_Break) {
				case 'No Marco':
					$color_entrada_break = 'red';
					$color_texto_entrada_break = 'white';
					break;
				default:
					// Si la hora de entrada a break no es "No Marco", no se aplican estilos
					break;
			}
			
			$color_salida_break = '';
			$color_texto_salida_break ='';
			switch ($reg->Salida_a_Break) {
				case 'No Marco':
					$color_salida_break = 'red';
					$color_texto_salida_break = 'white';
					break;
				default:
					// Si la hora de salida de break no es "No Marco", no se aplican estilos
					break;
			}
			
			
			
			$data[] = array(
				"0" => '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->idasistencia . ')"><i class="fa fa-close"></i></button>',
				"1" => '<span style="white-space: nowrap; min-width: 70px;">' . $reg->fecha . '</span>',
				"2" => '<span style="white-space: nowrap; min-width: 70px;">' . $reg->codigo_persona . '</span>',
				"3" => '<span style="white-space: nowrap; min-width: 120px;">' . $reg->nombre . ' ' . $reg->apellidos . '</span>',
				"4" => '<span style="white-space: nowrap; min-width: 100px;">' . $reg->departamento . '</span>',
				"5" => '<span style="white-space: nowrap; min-width: 70px;">' . $reg->tipo_turno . '</span>',                
				"6" => '<span class="editable" data-id="' . $reg->idasistencia . '" data-column="Ingreso_a_Turno" style="background-color: ' . $color_entrada_turno . '; color: ' . $color_texto_entrada_turno . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 100px;">' . $reg->Ingreso_a_Turno . '</span>',
				"7" => '<span class="editable" data-id="' . $reg->idasistencia . '" data-column="Ingreso_a_Break" style="background-color: ' . $color_entrada_break . '; color: ' . $color_texto_entrada_break . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 100px;">' . $reg->Ingreso_a_Break . '</span>',
				"8" => '<span class="editable" data-id="' . $reg->idasistencia . '" data-column="Salida_a_Break" style="background-color: ' . $color_salida_break . '; color: ' . $color_texto_salida_break . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 90px;">' . $reg->Salida_a_Break . '</span>',
				"9" => '<span class="editable" data-id="' . $reg->idasistencia . '" data-column="Salida_de_Turno" style="background-color: ' . $color_salida_turno . '; color: ' . $color_texto_salida_turno . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 90px;">' . $reg->Salida_de_Turno . '</span>'
			);
		}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);


	break;

	case 'listaru':
    $idusuario=$_SESSION["idusuario"];
		$rspta=$asistencia->listaru($idusuario);
		//declaramos un array
		$data=Array();


		while ($reg=$rspta->fetch_object()) {
			// Aquí integramos el cambio para la columna "4"
			$color_entrada_turno = '';
			$color_texto_entrada_turno='';
			switch ($reg->Ingreso_a_Turno) {
				case 'No Marco':
					$color_entrada_turno = 'red';
					$color_texto_entrada_turno = 'white';
					break;
				default:
					// Si la hora de entrada a turno no es "No Marco", no se aplican estilos
					break;
			}
			
			$color_salida_turno = '';
			$color_texto_salida_turno ='';
			switch ($reg->Salida_de_Turno) {
				case 'No Marco':
					$color_salida_turno = 'red';
					$color_texto_salida_turno = 'white';
					break;
				default:
					// Si la hora de salida de turno no es "No Marco", no se aplican estilos
					break;
			}
			
			$color_entrada_break = '';
			$color_texto_entrada_break ='';
			switch ($reg->Ingreso_a_Break) {
				case 'No Marco':
					$color_entrada_break = 'red';
					$color_texto_entrada_break = 'white';
					break;
				default:
					// Si la hora de entrada a break no es "No Marco", no se aplican estilos
					break;
			}
			
			$color_salida_break = '';
			$color_texto_salida_break ='';
			switch ($reg->Salida_a_Break) {
				case 'No Marco':
					$color_salida_break = 'red';
					$color_texto_salida_break = 'white';
					break;
				default:
					// Si la hora de salida de break no es "No Marco", no se aplican estilos
					break;
			}
			
			
			
			$data[]=array(
				//"0"=>'<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>',
				"0" => '<span style="white-space: nowrap; min-width: 120px;">' . $reg->fecha . '</span>',           
				"1" => '<span style="background-color: ' . $color_entrada_turno . '; color: ' . $color_texto_entrada_turno . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 120px;">' . $reg->Ingreso_a_Turno . '</span>',
				"2" => '<span style="background-color: ' . $color_entrada_break . '; color: ' . $color_texto_entrada_break . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 120px;">' . $reg->Ingreso_a_Break . '</span>',
				"3" => '<span style="background-color: ' . $color_salida_break . '; color: ' . $color_texto_salida_break . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 120px;">' . $reg->Salida_a_Break . '</span>',
				"4" => '<span style="background-color: ' . $color_salida_turno . '; color: ' . $color_texto_salida_turno . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 120px;">' . $reg->Salida_de_Turno . '</span>'

				
				);
		}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;

	case 'listar_asistencia':
    $fecha_inicio=$_REQUEST["fecha_inicio"];
    $fecha_fin=$_REQUEST["fecha_fin"];
    $codigo_persona=$_REQUEST["idcliente"]; 
		$rspta=$asistencia->listar_asistencia($fecha_inicio,$fecha_fin,$codigo_persona);
		//declaramos un array
		$data=Array();


		while ($reg=$rspta->fetch_object()) {
			// Aquí integramos el cambio para la columna "4"
			$color_entrada_turno = '';
			$color_texto_entrada_turno='';
			switch ($reg->Ingreso_a_Turno) {
				case 'No Marco':
					$color_entrada_turno = 'red';
					$color_texto_entrada_turno = 'white';
					break;
				default:
					// Si la hora de entrada a turno no es "No Marco", no se aplican estilos
					break;
			}
			
			$color_salida_turno = '';
			$color_texto_salida_turno ='';
			switch ($reg->Salida_de_Turno) {
				case 'No Marco':
					$color_salida_turno = 'red';
					$color_texto_salida_turno = 'white';
					break;
				default:
					// Si la hora de salida de turno no es "No Marco", no se aplican estilos
					break;
			}
			
			$color_entrada_break = '';
			$color_texto_entrada_break ='';
			switch ($reg->Ingreso_a_Break) {
				case 'No Marco':
					$color_entrada_break = 'red';
					$color_texto_entrada_break = 'white';
					break;
				default:
					// Si la hora de entrada a break no es "No Marco", no se aplican estilos
					break;
			}
			
			$color_salida_break = '';
			$color_texto_salida_break ='';
			switch ($reg->Salida_a_Break) {
				case 'No Marco':
					$color_salida_break = 'red';
					$color_texto_salida_break = 'white';
					break;
				default:
					// Si la hora de salida de break no es "No Marco", no se aplican estilos
					break;
			}
			
			
			
			$data[]=array(
				//"0"=>'<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>',
				"0" => '<span style="white-space: nowrap; min-width: 120px;">' . $reg->fecha . '</span>',
                "1"=> '<span style="white-space: nowrap; min-width: 120px;">' .$reg->codigo_persona . '</span>',
				"2"=> '<span style="white-space: nowrap; min-width: 120px;">' .$reg->nombre. ' ' .$reg->apellidos. '</span>',
				"3"=> '<span style="white-space: nowrap; min-width: 130px;">' .$reg->departamento . '</span>', 
				"4" => '<span style="white-space: nowrap; min-width: 100px;">' . $reg->tipo_turno . '</span>',                
				"5" => '<span style="background-color: ' . $color_entrada_turno . '; color: ' . $color_texto_entrada_turno . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 120px;">' . $reg->Ingreso_a_Turno . '</span>',
				"6" => '<span style="background-color: ' . $color_entrada_break . '; color: ' . $color_texto_entrada_break . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 120px;">' . $reg->Ingreso_a_Break . '</span>',
				"7" => '<span style="background-color: ' . $color_salida_break . '; color: ' . $color_texto_salida_break . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 120px;">' . $reg->Salida_a_Break . '</span>',
				"8" => '<span style="background-color: ' . $color_salida_turno . '; color: ' . $color_texto_salida_turno . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 120px;">' . $reg->Salida_de_Turno . '</span>'

				
				);
		}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;

	
	case 'listar_asistenciau':
    $fecha_inicio=$_REQUEST["fecha_inicio"];
    $fecha_fin=$_REQUEST["fecha_fin"];
    $codigo_persona=$_SESSION["codigo_persona"]; 
		$rspta=$asistencia->listar_asistencia($fecha_inicio,$fecha_fin,$codigo_persona);
		//declaramos un array
		$data=Array();


		while ($reg=$rspta->fetch_object()) {
				// Aquí integramos el cambio para la columna "4"
				$color_entrada_turno = '';
				$color_texto_entrada_turno='';
				switch ($reg->Ingreso_a_Turno) {
					case 'No Marco':
						$color_entrada_turno = 'red';
						$color_texto_entrada_turno = 'white';
						break;
					default:
						// Si la hora de entrada a turno no es "No Marco", no se aplican estilos
						break;
				}
				
				$color_salida_turno = '';
				$color_texto_salida_turno ='';
				switch ($reg->Salida_de_Turno) {
					case 'No Marco':
						$color_salida_turno = 'red';
						$color_texto_salida_turno = 'white';
						break;
					default:
						// Si la hora de salida de turno no es "No Marco", no se aplican estilos
						break;
				}
				
				$color_entrada_break = '';
				$color_texto_entrada_break ='';
				switch ($reg->Ingreso_a_Break) {
					case 'No Marco':
						$color_entrada_break = 'red';
						$color_texto_entrada_break = 'white';
						break;
					default:
						// Si la hora de entrada a break no es "No Marco", no se aplican estilos
						break;
				}
				
				$color_salida_break = '';
				$color_texto_salida_break ='';
				switch ($reg->Salida_a_Break) {
					case 'No Marco':
						$color_salida_break = 'red';
						$color_texto_salida_break = 'white';
						break;
					default:
						// Si la hora de salida de break no es "No Marco", no se aplican estilos
						break;
				}
				
				
				
				$data[]=array(
					//"0"=>'<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>',
					"0" => '<span style="white-space: nowrap; min-width: 120px;">' . $reg->fecha . '</span>',              
					"1" => '<span style="background-color: ' . $color_entrada_turno . '; color: ' . $color_texto_entrada_turno . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 120px;">' . $reg->Ingreso_a_Turno . '</span>',
					"2" => '<span style="background-color: ' . $color_entrada_break . '; color: ' . $color_texto_entrada_break . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 120px;">' . $reg->Ingreso_a_Break . '</span>',
					"3" => '<span style="background-color: ' . $color_salida_break . '; color: ' . $color_texto_salida_break . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 120px;">' . $reg->Salida_a_Break . '</span>',
					"4" => '<span style="background-color: ' . $color_salida_turno . '; color: ' . $color_texto_salida_turno . '; font-weight: bold; text-align: center; display: block; margin: auto; min-width: 120px;">' . $reg->Salida_de_Turno . '</span>'
	
					
					);
				}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;

		case 'selectPersona':
			require_once "../modelos/Usuario.php";
			$usuario=new Usuario();

			$rspta=$usuario->listare();

			while ($reg=$rspta->fetch_object()) {
				echo '<option value=' . $reg->codigo_persona.'>'.$reg->nombre.' '.$reg->apellidos.'</option>';
			}
			break;


//FILTRAR POR DEPARTAMENTO
case 'listar_por_departamento':
    // Supongamos que tienes acceso al nombre del departamento del usuario en $_SESSION['nombre_departamento']
    $nombre_departamento_usuario = $_SESSION['nombre_departamento'];

    $rspta = $asistencia->listar_por_departamento();
    // Declaramos un array
    $data = array();

    while ($reg = $rspta->fetch_object()) {
        // Verificamos si el departamento del registro coincide con el departamento del usuario
        if ($reg->departamento == $nombre_departamento_usuario) {
            // Aquí integramos el cambio para la columna "4"
            $color_entrada_turno = '';
			$color_texto_entrada_turno='';
			switch ($reg->Ingreso_a_Turno) {
				case 'No Marco':
					$color_entrada_turno = 'red';
					$color_texto_entrada_turno = 'white';
					break;
				default:
					// Si la hora de entrada a turno no es "No Marco", no se aplican estilos
					break;
			}
			
			$color_salida_turno = '';
			$color_texto_salida_turno ='';
			switch ($reg->Salida_de_Turno) {
				case 'No Marco':
					$color_salida_turno = 'red';
					$color_texto_salida_turno = 'white';
					break;
				default:
					// Si la hora de salida de turno no es "No Marco", no se aplican estilos
					break;
			}
			
			$color_entrada_break = '';
			$color_texto_entrada_break ='';
			switch ($reg->Ingreso_a_Break) {
				case 'No Marco':
					$color_entrada_break = 'red';
					$color_texto_entrada_break = 'white';
					break;
				default:
					// Si la hora de entrada a break no es "No Marco", no se aplican estilos
					break;
			}
			
			$color_salida_break = '';
			$color_texto_salida_break ='';
			switch ($reg->Salida_a_Break) {
				case 'No Marco':
					$color_salida_break = 'red';
					$color_texto_salida_break = 'white';
					break;
				default:
					// Si la hora de salida de break no es "No Marco", no se aplican estilos
					break;
			}
			
			
			
			$data[]=array(
				//"0"=>'<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>',
				"0"=>$reg->fecha,
                "1"=> '<span style="white-space: nowrap; min-width: 120px;">' .$reg->codigo_persona,
				"2"=> '<span style="white-space: nowrap; min-width: 120px;">' .$reg->nombre. ' ' .$reg->apellidos. '</span>',  
				"3"=>$reg->tipo_turno,               
"4" => '<span style="background-color: ' . $color_entrada_turno . '; color: ' . $color_texto_entrada_turno . '; font-weight: bold; text-align: center; display: block; margin: auto;">' . $reg->Ingreso_a_Turno . '</span>',
"5" => '<span style="background-color: ' . $color_entrada_break . '; color: ' . $color_texto_entrada_break . '; font-weight: bold; text-align: center; display: block; margin: auto;">' . $reg->Ingreso_a_Break . '</span>',
"6" => '<span style="background-color: ' . $color_salida_break . '; color: ' . $color_texto_salida_break . '; font-weight: bold; text-align: center; display: block; margin: auto;">' . $reg->Salida_a_Break . '</span>',
"7" => '<span style="background-color: ' . $color_salida_turno . '; color: ' . $color_texto_salida_turno . '; font-weight: bold; text-align: center; display: block; margin: auto;">' . $reg->Salida_de_Turno . '</span>'

				
				);
        }
    }

    $results = array(
        "sEcho" => 1, // Info para datatables
        "iTotalRecords" => count($data), // Enviamos el total de registros al datatable
        "iTotalDisplayRecords" => count($data), // Enviamos el total de registros a visualizar
        "aaData" => $data
    );
    echo json_encode($results);

    break;

			

}
?>