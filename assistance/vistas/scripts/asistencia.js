var tabla;

//funcion que se ejecuta al inicio
function init(){
   listar();
      listaru();
	  listar_por_departamento();
$("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   })

    //cargamos los items al select cliente
   $.post("../ajax/asistencia.php?op=selectPersona", function(r){
   	$("#idcliente").html(r);
   	$('#idcliente').selectpicker('refresh');
   });

}


//Editar y Guardar datos

$(document).ready(function() {
    $('#tbllistado').on('click', '.editable', function() {
        var $this = $(this);
        var originalContent = $this.text().trim();
        var id = $this.data('id');
        var column = $this.data('column');

        if ($this.find('input').length > 0) {
            return;
        }

        var valueToEdit = (originalContent === '' || originalContent === 'No Marco') ? 'No Marco' : originalContent;

        var input = $('<input>', {
            value: valueToEdit,
            type: 'text',
            css: {
                'background-color': '#fff0f0',
                'border': '1px solid #ccc',
                'padding': '5px',
                'width': '100%',
                'color': 'black'
            },
            blur: function() {
                var newContent = $(this).val().trim();

                // Validar y formatear la hora ingresada
                if (newContent !== '' && newContent !== 'No Marco') {
                    var regex = /^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/;
                    if (!regex.test(newContent)) {
                        alert("Por favor ingresa una hora válida en formato HH:MM:SS");
                        $(this).focus();
                        return;
                    }

                    // Si la hora está en formato HH:MM, añadir los segundos
                    if (newContent.length === 5) {
                        newContent += ':00';
                    }
                }

                if (newContent === '') {
                    newContent = 'No Marco';
                }

                if (newContent !== originalContent) {
                    $.ajax({
                        url: '../ajax/asistencia.php?op=actualizarCampo',
                        method: 'POST',
                        data: {
                            id: id,
                            column: column,
                            value: newContent
                        },
                        success: function(response) {
                            // Actualizar el contenido de la celda con el nuevo valor
                            $this.text(newContent);

                            // Forzar la re-renderización de la celda
                            $this.css({
                                'font-weight': 'bold',  // Aplicar negrita al texto
                                'color': newContent === 'No Marco' ? 'white' : 'black',  // Aplicar color adecuado
                                'background-color': newContent === 'No Marco' ? 'red' : '',  // Fondo rojo para "No Marco"
                                'text-align': 'center'  // Centrar el texto
                            });

                            // Forzar la re-renderización quitando y volviendo a añadir la celda al DOM
                            $this.html($this.html());
                        },
                        error: function() {
                            $this.text(originalContent === '' ? 'No Marco' : originalContent);
                        }
                    });
                } else {
                    $this.text(originalContent === '' ? 'No Marco' : originalContent);
                }
            },
            keyup: function(e) {
                if (e.which === 13) $(this).blur();
            }
        }).appendTo($this.empty()).focus();

        input[0].setSelectionRange(valueToEdit.length, valueToEdit.length);
    });
});




function listar() {
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/asistencia.php?op=listar',
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "asc"]]
    }).DataTable();
}



function listaru(){
	tabla=$('#tbllistadou').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdf'
		],
		"ajax":
		{
			url:'../ajax/asistencia.php?op=listaru',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,//paginacion
		"order":[[0,"asc"]]//ordenar (columna, orden)
	}).DataTable();
}



function listar_asistencia(){
var  fecha_inicio = $("#fecha_inicio").val();
 var fecha_fin = $("#fecha_fin").val();
 var idcliente = $("#idcliente").val();

	tabla=$('#tbllistado_asistencia').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdf'
		],
		"ajax":
		{
			url:'../ajax/asistencia.php?op=listar_asistencia',
			data:{fecha_inicio:fecha_inicio, fecha_fin:fecha_fin, idcliente: idcliente},
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,//paginacion
		"order":[[0,"asc"]]//ordenar (columna, orden)
	}).DataTable();
}
function listar_asistenciau(){
var  fecha_inicio = $("#fecha_inicio").val();
 var fecha_fin = $("#fecha_fin").val();

	tabla=$('#tbllistado_asistenciau').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdf'
		],
		"ajax":
		{
			url:'../ajax/asistencia.php?op=listar_asistenciau',
			data:{fecha_inicio:fecha_inicio, fecha_fin:fecha_fin},
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,//paginacion
		"order":[[0,"asc"]]//ordenar (columna, orden)
	}).DataTable();
}
function listar_toda_asistencia() {
    tabla = $('#tbllistado_asistenciau').dataTable({
        "aProcessing": true, //activamos el procedimiento del datatable
        "aServerSide": true, //paginacion y filrado realizados por el server
        dom: 'Bfrtip', //definimos los elementos del control de la tabla
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/asistencia.php?op=listar_asistenciau', // Cambiar la URL para reflejar el nuevo endpoint
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, //paginacion
        "order": [
            [0, "desc"]
        ] //ordenar (columna, orden)
    }).DataTable();
}

//VER TABLA POR DEPARTAMENTO
function listar_por_departamento(){
	tabla=$('#tbllistadopordepa').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdf'
		],
		"ajax":
		{
			url:'../ajax/asistencia.php?op=listar_por_departamento',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,//paginacion
		"order":[[0,"asc"]]//ordenar (columna, orden)
	}).DataTable();
}

function desactivar(idasistencia){
	bootbox.confirm("¿Esta seguro de eliminar este dato?", function(result){
		if (result) {
			$.post("../ajax/asistencia.php?op=desactivar", {idasistencia : idasistencia}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

init();