angular.module('readArkrit')
  .controller('miBibliotecaCtrl', function ($scope, DTOptionsBuilder) {

    $scope.estanterias 				= [];
    $scope.librosAnadidos 			= [];
    $scope.librosEstanteria 		= [];
    $scope.idEstanteriaSeleccionada = 0;
    $scope.estanteriasSeguidas      = [];

    // FUNCIONES

    $scope.modalAltaEstanteria = function(idEstanteria, nombreEstanteria){

    	var operacion = '';
    	var inputHtml = '';
    	var botonHtml = '';

    	if( typeof idEstanteria === 'undefined' && typeof nombreEstanteria === 'undefined' ){

    		operacion = 'Alta';
    		inputHtml = '<input type="text" class="form-control" name="nuevoNombreEstanteria" id="nuevoNombreEstanteria">';
    		botonHtml = '<button type="button" class="btn btn-xs btn-success pull-right" onclick="altaModificarEstanteria(' + "'" + operacion + "'" + ')">DAR DE ALTA</button>';
    	} else {

    		operacion = 'Modificar';
    		inputHtml = '<input type="text" class="form-control" name="nuevoNombreEstanteria" id="nuevoNombreEstanteria" value="' + nombreEstanteria + '">';
    		botonHtml = '<button type="button" class="btn btn-xs btn-success pull-right" onclick="altaModificarEstanteria(' + "'" + operacion + "'" + ', ' + idEstanteria + ')">MODIFICAR</button>';
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
			                    	'<div class="alert hidden" id="notificacionesModalEstanteria">' +
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

    $scope.listarEstanterias = function(){

    	peticionAJAX('./php/estanteria.php', {

			opcion: 'estanteria',
			accion: 'listar',
			idUsuario: 64/*ID_USUARIO*/
		})
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				$scope.estanterias = $.makeArray(data.estanterias);

				$scope.$apply();
			}
		});
    };

    $scope.listarLibrosAnadidos = function(){

    	peticionAJAX('./php/libroAnadido.php', {

			opcion: 'libroAnadido',
			accion: 'listar'
		})
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				var rutaDefinitiva = './img/portadasLibros/';

				$('#tablaListado').removeClass('hidden');
				$scope.librosAnadidos = $.makeArray(data.librosAnadidos);

				$.each( $scope.librosAnadidos, function( index, value ){
				    $scope.librosAnadidos[index].portada = rutaDefinitiva + $scope.librosAnadidos[index].portada;
				});
			}
		});
    };

    $scope.listarLibrosEstanteria = function(){

    	var parametros = {};
		var peticion   = {};

		$scope.idEstanteriaSeleccionada = $('input:checked').val();

		parametros.opcion = 'estanteria';
	    parametros.accion = 'listarLibros';
	    parametros.idEstanteria = $scope.idEstanteriaSeleccionada;

		peticion = peticionAJAX('./php/estanteria.php', parametros);

	    peticion.done(function( data, textStatus, jqXHR ) {

	    	$scope.librosEstanteria = [];

	        if( !data.error ){

	        	var arrLibrosEstanteria = [];
	        	var i = 0;
	        	var index;

				arrLibrosEstanteria = $.makeArray(data.idsLibrosEstanteria);

				$('tr.selected').removeClass('selected');// limpiamos los tr que están seleccionados

				if( arrLibrosEstanteria.length > 0 ){

					for(i=0; i<arrLibrosEstanteria.length; i++){

						index = buscarValorEnArrObj($scope.librosAnadidos, 'id_libro', arrLibrosEstanteria[i].id_libro);

						$scope.librosEstanteria[i] = angular.copy($scope.librosAnadidos[index]);
						$scope.librosEstanteria[i].libro_leido = arrLibrosEstanteria[i].libro_leido;

						//if( arrLibrosEstanteria[i].libro_leido == 1 )	
						$('tr[data-idLibro=' + $scope.librosEstanteria[i].id_libro + ']').addClass('selected'); // Seleccionamos el libro que hay en la estantería
					}
				}

				$scope.$apply();			
	        }
	    });
    };

    $scope.modificarEstanteria = function( e ){

    	var idLibro = $(e.currentTarget).attr('data-idLibro');
    	var index   = -1;

    	if( $(e.currentTarget).hasClass('selected') ){

    		index = buscarValorEnArrObj($scope.librosEstanteria, 'id_libro', idLibro);

    		$scope.librosEstanteria.splice(index, 1);// quitar de librosEstanteria
    	} else {

    		index = buscarValorEnArrObj($scope.librosAnadidos, 'id_libro', idLibro);

    		$scope.librosEstanteria.push($scope.librosAnadidos[index]);
    	}

		$(e.currentTarget).toggleClass('selected');
    };

    $scope.marcarLibroComoLeido = function( e ){

    	$(e.currentTarget).find('.fa').toggleClass("fa-eye-slash fa-eye");
    };

    $scope.guardarModificacionEstanteria = function(){

    	// Grabamos en bbdd los cambios
    	var libros = [];
    	var index = -1;

    	for(var i=0; i< $scope.librosEstanteria.length; i++){

    		libros.push({

    			idLibro: $scope.librosEstanteria[i].id_libro,
    			libroLeido: $('li[data-idLibro=' + $scope.librosEstanteria[i].id_libro + '] button i').hasClass('fa-eye')
    		});
    	}

    	peticionAJAX('./php/estanteria.php', {

			opcion: 'estanteria',
			accion: 'modificarEstanteria',
			idEstanteria: $scope.idEstanteriaSeleccionada,
			libros: libros
		})
		.done(function( data, textStatus, jqXHR ){

			$('#erroresMisEstanterias').removeClass('alert-success')
									   .removeClass('alert-danger');

			if( !data.error ){

				$('#erroresMisEstanterias span').html('Estantería actualizada.');
				$('#erroresMisEstanterias').addClass('alert-success');
			} else {

				$('#erroresMisEstanterias span').html(data.descripcionError);
				$('#erroresMisEstanterias').addClass('alert-danger');
			}

			$('#erroresMisEstanterias').removeClass('hidden');

			index = buscarValorEnArrObj($scope.estanterias, 'id_estanteria', $scope.idEstanteriaSeleccionada);

			$scope.estanterias[index].cantidad_libros = $scope.librosEstanteria.length;

			$scope.$apply();
		});
    };


    $scope.listarEstanteriasQueSigo = function(){

    	peticionAJAX('./php/estanteria.php', {

			opcion: 'estanteria',
			accion: 'listarEstanteriasQueSigo',
			idUsuario: 64 /*ID_USUARIO*/
		})
		.done(function( data, textStatus, jqXHR ){

			if( !data.error )
				$scope.estanteriasSeguidas = $.makeArray(data.estanteriasSeguidas);
		});
    };

    // EVENTOS

    cargarJS("./js/clases/Estanteria.js");
    /*cargarJS("./js/clases/Profesor.js");*/

    // Mis Estanterías
    	// Listar
    $scope.listarEstanterias();
    $scope.listarLibrosAnadidos();

    // Recomendaciones ARKRIT
    $scope.recomendaciones = generarRecomendacionesArkrit( 64 );	/* ID_USUARIO */

    // Listar estanterías que sigo
    $scope.listarEstanteriasQueSigo();

}); // fin controller


function altaModificarEstanteria(operacion, idEstanteria){

	var parametros 		 = {};
	var html 			 = '';
	var nombreEstanteria = $('#nuevoNombreEstanteria').val();
	var scope 			 = angular.element($('#listadoEstanterias')).scope();
	var index			 = -1;

	if( nombreEstanteria == '' )
		html += 'Debes introducir un nombre para tu estantería.';
	else if( existeRegistro('nombre', nombreEstanteria, 'estanteria') )
		html += 'Ya tienes una estantería que se llama así.';
	else if( nombreEstanteria.length > 20 )
		html += 'El nombre de tu estantería no puede exceder de los 20 caracteres.</li>';
	else if( !nombreEstanteria.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
		html += 'El nombre de tu estantería sólo puede contener letras.</li>';

	if( html != '' ){

		$('#notificacionesModalEstanteria span').html(html);
		$('#notificacionesModalEstanteria').addClass('alert-danger');
	} else {

		cerrarAlerta($('#notificacionesModalEstanteria'));
		
		if( operacion == 'Alta' ){

			var estanteria = new Estanteria('', nombreEstanteria, 64/*ID_USUARIO*/);

			parametros.opcion = 'estanteria';
			parametros.accion = 'alta';
			parametros.estanteria = estanteria;

		} else {
			// la modificación en este caso sólo es del nombre
			parametros.opcion = 'estanteria';
			parametros.accion = 'cambiarNombre';
			parametros.nombreEstanteria = nombreEstanteria;
			parametros.idEstanteria = idEstanteria;
		}


		peticionAJAX('./php/estanteria.php', parametros)
		.done(function( data, textStatus, jqXHR ){

			$('#notificacionesModalEstanteria').removeClass('alert-success')
									   		   .removeClass('alert-danger');

			if( !data.error ){

				if( operacion == 'Alta' ){

				    scope.$apply(function(){

				        scope.estanterias.push({ id_estanteria: data.idEstanteria, nombre: nombreEstanteria, cantidad_libros: 0 });
				    });

					$('#notificacionesModalEstanteria span').html('Estantería creada correctamente.');

				} else {

					scope.$apply(function(){

						index = buscarValorEnArrObj(scope.estanterias, 'id_estanteria', idEstanteria);

				        scope.estanterias[index].nombre = nombreEstanteria;
				    });

					$('#notificacionesModalEstanteria span').html('Estantería modificada.');
				}

				$('#notificacionesModalEstanteria').addClass('alert-success');
			} else{

				$('#notificacionesModalEstanteria span').html(data.descripcionError);

				$('#notificacionesModalEstanteria').addClass('alert-danger');
			}
		});
	}

	$('#notificacionesModalEstanteria').removeClass('hidden');
}