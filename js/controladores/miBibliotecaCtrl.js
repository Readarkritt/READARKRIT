angular.module('readArkrit')
  .controller('miBibliotecaCtrl', function ($scope, DTOptionsBuilder) {

  	$scope.nuevoNombreEstanteria = '';
    $scope.estanterias = [];
    $scope.nombreEstanterias = [];
    $scope.librosAnadidos = [];
    $scope.librosEstanteria = [];
    $scope.htmlLibrosAnadidos = '';

    // FUNCIONES
    $scope.obtenerNombreEstanterias = function(){

    	if( $scope.estanterias.lenght != 0 ){

    		$.each( $scope.estanterias, function( index, element ) {

    			$scope.nombreEstanterias.push( element.nombre );
    		});
    	}
    };

    $scope.modalAltaEstanteria = function(idEstanteria, nombreEstanteria){

    	var operacion = '';
    	var inputHtml = '';
    	var botonHtml = '';

    	if( typeof idEstanteria === 'undefined' && typeof nombreEstanteria === 'undefined' ){

    		operacion = 'Alta';
    		inputHtml = '<input type="text" class="form-control" name="nuevoNombreEstanteria" ng-model="nuevoNombreEstanteria">';
    		botonHtml = '<button type="button" class="btn btn-xs btn-success pull-right" ng-click="altaEstanteria()">DAR DE ALTA</button>';
    	} else {

    		operacion = 'Modificar';
    		inputHtml = '<input type="text" class="form-control" name="nuevoNombreEstanteria" ng-model="nuevoNombreEstanteria" value="' + nombreEstanteria + '">';
    		botonHtml = '<button type="button" class="btn btn-xs btn-success pull-right" ng-click="cambiarNombreEstanteria()">MODIFICAR</button>';
    	}


    	var html = '<div id="altaEstanteriaModal" class="modal fade" role="dialog">' +
					  '<div class="modal-dialog">' +
					    '<div class="modal-content">' +

					      '<div class="modal-header">' +
					        '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
					        '<h4 class="modal-title">' + operacion + ' Estantería</h4>' +
					      '</div>' +

					      '<div class="modal-body">' +
					      	'<div class="container col-md-12">' +
						      	'<div class="row">' +
							        '<div class="col-md-12">' +
							        	'<form>' +
							        		'<div class="form-group label-floating">' +
												'<label class="control-label">Nombre</label>' +
												inputHtml +
											'</div>' +
											botonHtml +
							        	'</form>' +
							        '</div>' +
							    '</div>' +

			                    '<div class="row">' +
			                    	'<div class="alert hidden" id="notificacionesAltaEstanteria">' +
		                                '<button type="button" aria-hidden="true" class="close" onclick="cerrarAlerta(this)">×</button>' +
		                                '<span>' +
		                                '</span>' +
				                    '</div>' +
							    '</div>' +
							'</div>' +
					      '</div>' +

					      '<div class="modal-footer">' +
					     	'&nbsp;' +
					      '</div>' +

					    '</div>' +
					  '</div>' +
					'</div>';

		$('#altaEstanteriaModal').remove();	// borra modals anteriores

		$('body').append(html);

		$('#altaEstanteriaModal').modal();
    };

    $scope.modalAdminEstanteria = function(){

    	$scope.listarLibrosAnadidos();
    	//$scope.cargarLibrosEstanteria();

    	var html = '<div id="adminEstanteriaModal" class="modal fade" role="dialog">' +
					  '<div class="modal-dialog">' +
					    '<div class="modal-content">' +

					      '<div class="modal-header">' +
					        '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
					        '<h4 class="modal-title">Modificar Libros Estantería</h4>' +
					      '</div>' +

					      '<div class="modal-body">' +
					      	'<div class="container col-md-12">' +
					      		'<div class="row">' +
							        '<div class="col-md-12">' +
							        	'Selecciona y deselecciona a tu gusto:</br></br>' + 
							        	'<div class="tab-pane active table-responsive">' +
											'<table id="tablaListadoLibrosAnadidos" class="table table-striped table-bordered dt-responsive nowrap row-border" width="100%" datatable="ng" cellspacing="0">' +
										        '<thead>' +
										            '<tr>' +
										            	'<th class="no-sort all">PORTADA</th>' +
										                '<th class="all">TÍTULO</th>' +
										                '<th>TÍTULO ORIGINAL</th>' +
										                '<th>AUTOR</th>' +
										                '<th>AÑO</th>' +
										            '</tr>' +
										        '</thead>' +
										        '<tbody>' +
													$scope.htmlLibrosAnadidos +									     
										        '</tbody>' +
										    '</table>' +
										'</div>' +
							        '</div>' +
							    '</div>' +
							    '<div class="row">' +
							    	'<div class="col-md-12">' +
							        	'</br>Los libros que tienes en esta estantería son:</br>' +
							        	'<ul>' + 
							        	'</ul>' +
							        '</div>' +
							    '</div>' +
						      	'<div class="row">' +
							        '<div class="col-md-12">' +
							        	'<form>' +
											'<button type="button" class="btn btn-xs btn-success pull-right" ng-click="modificarEstanteria()">MODIFICAR</button>' +
							        	'</form>' +
							        '</div>' +
							    '</div>' +

			                    '<div class="row">' +
			                    	'<div class="alert hidden" id="notificacionesAltaEstanteria">' +
		                                '<button type="button" aria-hidden="true" class="close" onclick="cerrarAlerta(this)">×</button>' +
		                                '<span>' +
		                                '</span>' +
				                    '</div>' +
							    '</div>' +
							'</div>' +
					      '</div>' +

					      '<div class="modal-footer">' +
					     	'&nbsp;' +
					      '</div>' +

					    '</div>' +
					  '</div>' +
					'</div>';

		$('body').append(html);

		$('#adminEstanteriaModal').modal();
		$('#tablaListadoLibrosAnadidos').DataTable({
        	// hace que no se pueda ordenar por la columna de checkbox
		    "ordering": true,
		    columnDefs: [{
		      orderable: false,
		      targets: "no-sort"
		    }],
		    "order": [[ 2, "asc" ]]	
		});
    };

    $scope.altaEstanteria = function(){

    	var estanteria = new Estanteria('', $scope.nuevoNombreEstanteria, ID_USUARIO);
    	var html = '';

    	if( $scope.nuevoNombreEstanteria == '' )
    		html += 'Debes introducir un nombre para tu estantería.';
    	else if( $.inArray( $scope.nuevoNombreEstanteria, $scope.nombreEstanterias ) != -1 )
    		html += 'Ya tienes una estantería que se llama así.';
		else if( $scope.nuevoNombreEstanteria.length > 20 )
			html += 'El nombre de tu estantería no puede exceder de los 20 caracteres.</li>';
		else if( !$scope.nuevoNombreEstanteria.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			html += 'El nombre de tu estantería sólo puede contener letras.</li>';

    	if( html != '' ){

    		$('#notificacionesAltaEstanteria span').html(html);
			$('#notificacionesAltaEstanteria').addClass('alert-danger');
    	} else {

    		cerrarAlerta($('#notificacionesAltaEstanteria'));

    		peticionAJAX('./php/estantería.php', {

				opcion: 'estanteria',
				accion: 'alta',
				estanteria: estanteria
			})
			.done(function( data, textStatus, jqXHR ){

				if( !data.error ){

					$('#notificacionesAltaEstanteria span').html('Estantería creada correctamente.');
					$('#notificacionesAltaEstanteria').addClass('alert-success');
				} else{

					$('#notificacionesAltaEstanteria span').html('No se ha podido crear la estantería.');
					$('#notificacionesAltaEstanteria').addClass('alert-danger');
				}
			});
    	}

    	$('#notificacionesAltaEstanteria').removeClass('hidden');
    };

    $scope.listarEstanterias = function(){

    	peticionAJAX('./php/estanteria.php', {

			opcion: 'estanteria',
			accion: 'listar',
			idUsuario: 64/*ID_USUARIO*/
		})
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				$('#tablaListado').removeClass('hidden');
				$scope.estanterias = $.makeArray(data.estanterias);
				$scope.nombreEstanterias = $scope.obtenerNombreEstanterias();
			}
		});
    };

    $scope.cambiarNombreEstanteria = function(){

    	if( $.inArray( $scope.nuevoNombreEstanteria, $scope.nombreEstanterias ) == -1 ){

    		peticionAJAX('./php/estanteria.php', {

				opcion: 'estanteria',
				accion: 'cambiarNombre',
				nombreEstanteria: $scope.nuevoNombreEstanteria,
				idUsuario: 64/*ID_USUARIO*/
			})
			.done(function( data, textStatus, jqXHR ){

				if( !data.error ){

					$('#notificacionesAltaEstanteria span').html('Estantería actualizada.');
					$('#notificacionesAltaEstanteria').addClass('alert-success');
				} else {

					$('#notificacionesAltaEstanteria span').html('No se ha podido cambiar el nombre de la estantería.');
					$('#notificacionesAltaEstanteria').addClass('alert-danger');
				}
			});
    	} else {

    		$('#notificacionesAltaEstanteria span').html('Ya tienes una estantería que se llama así.');
			$('#notificacionesAltaEstanteria').addClass('alert-danger');
    	}

    	$('#notificacionesAltaEstanteria').removeClass('hidden');
    };

    /*$scope.cargarLibrosAnadidos = function(){

    	var parametros = {};
		var peticion   = {};

		parametros.opcion = 'libroAnadido';
	    parametros.accion = 'listar';

		peticion = peticionAJAX('./php/libroAnadido.php', parametros);

	    peticion.done(function( data, textStatus, jqXHR ) {

	        if( !data.error ){

	        	$('#tablaListadoLibrosAnadidos').removeClass('hidden');
				$scope.librosAnadidos = $.makeArray(data.librosAnadidos);
	        }
	    });
    };*/

    $scope.listarLibrosAnadidos = function(){

    	peticionAJAX('./php/libroAnadido.php', {

			opcion: 'libroAnadido',
			accion: 'listar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				var rutaDefinitiva = './img/portadasLibros/';

				$('#tablaListadoLibrosAnadidos').removeClass('hidden');
				$scope.librosAnadidos = $.makeArray(data.librosAnadidos);

				$.each( $scope.librosAnadidos, function( index, element ){

					$scope.htmlLibrosAnadidos += '<tr data-idLibroAnadido=' + element.id_libro_anadido + '>';
					$scope.htmlLibrosAnadidos += '<td><img src="' + rutaDefinitiva + element.portada + '" alt="portada" height="40" width="40"></td>';
					$scope.htmlLibrosAnadidos += '<td>' + element.titulo + '</td>';
					$scope.htmlLibrosAnadidos += '<td>' + element.titulo_original + '</td>';
					$scope.htmlLibrosAnadidos += '<td>' + element.autor + '</td>';
					$scope.htmlLibrosAnadidos += '<td>' + element.ano + '</td>';
					$scope.htmlLibrosAnadidos += '</tr>';

				    $scope.librosAnadidos[index].portada = rutaDefinitiva + $scope.librosAnadidos[index].portada;
				});
			}
		});
    };

    /*$scope.cargarLibrosEstanteria = function(){

    	var parametros = {};
		var peticion   = {};

		parametros.opcion = 'libroAnadido';
	    parametros.accion = 'listar';

		peticion = peticionAJAX('./php/libroAnadido.php', parametros);

	    peticion.done(function( data, textStatus, jqXHR ) {

	        if( !data.error ){

				$scope.librosEstanteria = $.makeArray(data.librosEstanteria);
	        }
	    });
    };*/

    /*$scope.eliminarProfesor = function(idProfesor, indexScope){

    	peticionAJAX('./php/profesor.php', {

			opcion    : 'profesor',
			accion	  : 'eliminar',
			idProfesor: idProfesor
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( data.error )
				swal("Eliminar Profesor", "Error en la transacción.", "error");
			else{

				swal("Profesor Eliminado", "Profesor eliminado con éxito.", "success");

				$scope.profesores.splice(indexScope, 1);
			}
		});

		$scope.dtOptions = DTOptionsBuilder.fromFnPromise( $scope.profesores ).withOption('stateSave', true).withDataProp('data');

	    $scope.reloadData = reloadData;
	    $scope.dtInstance = {};

	    function reloadData() {
	        var resetPaging = false;
	        $scope.dtInstance.reloadData(callback, resetPaging);
	    }
    };*/

    // EVENTOS

    cargarJS("./js/clases/Estanteria.js");
    /*cargarJS("./js/clases/Profesor.js");*/

    	// Listar
    $scope.listarEstanterias();

    	// Añadir Estanterías


    $('#tablaListadoLibrosAnadidos tbody tr').click(function(){
        $(this).toggleClass('selected');
        console.log('daaaa');
	});

	$('#tablaListadoLibrosAnadidos tbody').click(function(){
        $(this).toggleClass('selected');
        console.log('daaaa');
	});

}); // fin controller