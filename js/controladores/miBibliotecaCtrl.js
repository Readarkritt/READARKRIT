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

    $scope.modalAltaLibroEstanteria = function(){

    	var html 	  = '';
    	var bodyTable = '';

    	for (var i = 0; i < $scope.librosAnadidos.length; i++) {

			bodyTable += '<tr data-idLibro=' + $scope.librosAnadidos[i].id_libro + '>' +
							'<td><img src=' + $scope.librosAnadidos[i].portada + ' alt="portada" class="portada"></td>' +
			        		'<td>' +
			        			$scope.librosAnadidos[i].titulo +
			        			'<br/>' +
			        			'<i>' + $scope.librosAnadidos[i].titulo_original + '</i>' +
			        		'</td>' +
			        		'<td>' + $scope.librosAnadidos[i].autor + '</td>' +
						'</tr>';
		}

    	html = '<div id="altaLibroEstanteriaModal" class="modal fade" role="dialog">' +
					  '<div class="modal-dialog">' +
					    '<div class="modal-content">' +

					      '<div class="modal-header">' +
					        '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
					        '<h4 class="modal-title">Añadir Libro a Estantería</h4>' +
					      '</div>' +

					      '<div class="modal-body">' +
					      	'<div class="container col-md-12">' +
					      		'<div class="row">' +
							        '<div class="col-md-12">' +
										'<div class="tab-pane active table-responsive" id="listadoLibrosAnadidos">' +
											'<table id="tablaLibrosAnadidos" class="table table-striped table-bordered responsive no-wrap row-border hover hidden" width="100%" cellspacing="0">' +
										        '<thead>' +
										            '<tr>' +
										            	'<th class="all">PORTADA</th>' +
										                '<th>TÍTULO</th>' +
										                '<th>AUTOR</th>' +
										            '</tr>' +
										        '</thead>' +
										        '<tbody>' +	
										        	bodyTable +
										        '</tbody>' +
										    '</table>' +
										'</div> ' +
							        '</div>' +
							    '</div>' +

						      	'<div class="row">' +
							        '<div class="col-md-12">' +
										'<button type="button" class="btn btn-xs btn-success pull-right" onclick="altaLibroEstanteria()">AÑADIR LIBROS</button>' +
							        '</div>' +
							    '</div>' +

			                    '<div class="row">' +
			                    	'<div class="alert hidden" id="notificacionesModalAltaLibroEstanteria">' +
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

		$('#altaLibroEstanteriaModal').remove();	// borra modals anteriores

		$('body').append(html);

		$('#altaLibroEstanteriaModal').modal();

		$('#tablaLibrosAnadidos').DataTable();
		$('#tablaLibrosAnadidos').removeClass('hidden');

		$('#tablaLibrosAnadidos tbody').on( 'click', 'tr', function () {
	        $(this).toggleClass('selected');
	    } );
    };

    $scope.actualizarNumLibrosEstanteria = function(){

    	var index = buscarValorEnArrObj($scope.estanterias, 'id_estanteria', $scope.idEstanteriaSeleccionada);

		$scope.estanterias[index].cantidad_libros = $scope.librosEstanteria.length;
    };

    $scope.listarEstanterias = function(){

    	peticionAJAX('./php/estanteria.php', {

			opcion: 'estanteria',
			accion: 'listar'
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

    $scope.listarLibrosEstanteria = function( idEstanteria ){

    	var parametros = {};
		var peticion   = {};

		$scope.idEstanteriaSeleccionada = idEstanteria;

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

				if( arrLibrosEstanteria.length > 0 ){

					for(i=0; i<arrLibrosEstanteria.length; i++){

						index = buscarValorEnArrObj($scope.librosAnadidos, 'id_libro', arrLibrosEstanteria[i].id_libro);

						$scope.librosEstanteria[i] = angular.copy($scope.librosAnadidos[index]);
						$scope.librosEstanteria[i].libro_leido = arrLibrosEstanteria[i].libro_leido;
					}
				}

				$scope.$apply();
	        }
	    });
    };

    $scope.toggleInfoLibro = function( e ){

    	var anchoEstanteria = $('#estanteria').width();
    	var anchoContenedor = $(e.currentTarget).width(); // .contenedorLibro
    	var anchoResponsive = 0;


    	if( anchoContenedor == 140 ){ // No se ha desplegado la info del libro

    		if( anchoEstanteria >= 570 )
    			$(e.currentTarget).find('ul.hidden').removeClass('hidden');
    		else
    			$(e.currentTarget).height('inherit');

    		$(e.currentTarget).animate({ width: '100%' })  
    						  .find('ul.hidden').removeClass('hidden');
    	} else 
    		$(e.currentTarget).animate({ height: '200px', width: '140px' })
    						  .find('ul').addClass('hidden');
    };

    $scope.eliminarLibro = function( indexScope ){

    	peticionAJAX('./php/estanteria.php', {

			opcion: 'estanteria',
			accion: 'eliminarLibro',
			idEstanteria: $scope.idEstanteriaSeleccionada,
			idLibro: $scope.librosEstanteria[indexScope].id_libro
		})
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				$scope.librosEstanteria.splice(indexScope, 1);	// quitar de librosEstanteria

				$scope.actualizarNumLibrosEstanteria();

			} else {

				$('#erroresMisEstanterias span').html(data.descripcionError);
				$('#erroresMisEstanterias').removeClass('hidden');
			}

			$scope.$apply();
		});
    };

    $scope.marcarLibroComoLeido = function( e, indexScope ){

    	peticionAJAX('./php/estanteria.php', {

			opcion: 'estanteria',
			accion: 'marcarLibroComoLeido',
			idEstanteria: $scope.idEstanteriaSeleccionada,
			idLibro: $scope.librosEstanteria[indexScope].id_libro
		})
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				index = buscarValorEnArrObj($scope.librosEstanteria, 'id_libro', $scope.librosEstanteria[indexScope].id_libro);

				$scope.librosEstanteria[index].libro_leido = !$scope.librosEstanteria[index].libro_leido; // actualizo el valor de libro_leido del libro

				$(e.currentTarget).find('.fa').toggleClass("fa-eye-slash fa-eye");

			} else {

				$('#erroresMisEstanterias span').html(data.descripcionError);
				$('#erroresMisEstanterias').removeClass('hidden');
			}
		});
    };


    $scope.listarEstanteriasQueSigo = function(){

    	peticionAJAX('./php/estanteria.php', {

			opcion: 'estanteria',
			accion: 'listarEstanteriasQueSigo'
		})
		.done(function( data, textStatus, jqXHR ){

			if( !data.error )
				$scope.estanteriasSeguidas = $.makeArray(data.estanteriasSeguidas);
		});
    };

    // EVENTOS

    cargarJS("./js/clases/Estanteria.js");

    // Mis Estanterías
    	// Listar
    $scope.listarEstanterias();
    $scope.listarLibrosAnadidos();

    // Recomendaciones ARKRIT
    $scope.recomendaciones = generarRecomendacionesArkrit();

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
	else if( !nombreEstanteria.match(/^[a-zA-Z0-9ñÑÁáÉéÍíÓóÚúÀàÈèÌìÒòÙùÄäËëÏïÖöÜüÂâÊêÎîÔôÛûÇçß_\s]+$/) )
		html += 'El nombre de tu estantería sólo puede contener letras o dígitos.</li>';

	if( html != '' ){

		$('#notificacionesModalEstanteria span').html(html);
		$('#notificacionesModalEstanteria').addClass('alert-danger');
	} else {

		cerrarAlerta($('#notificacionesModalEstanteria'));
		
		if( operacion == 'Alta' ){

			var estanteria = new Estanteria('', nombreEstanteria, ''/*ID_USUARIO*/);

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


function altaLibroEstanteria(){

	var arrIdsLibros = [];
	var scope 		 = angular.element($('#estanteria')).scope();

	$('.selected').each(function( index, element ) {

	    arrIdsLibros[index] = $(element).attr('data-idLibro');
	});
		
	peticionAJAX('./php/estanteria.php', {

		opcion: 'estanteria',
		accion: 'altaLibro',
		idEstanteria: scope.idEstanteriaSeleccionada,
		idsLibros: arrIdsLibros
	})
	.done(function( data, textStatus, jqXHR ){

		$('#notificacionesModalAltaLibroEstanteria').removeClass('alert-success')
									   	   			.removeClass('alert-danger');

		if( !data.error ){

		    scope.$apply(function(){

		    	var index = -1;

		    	for (var i = 0; i < arrIdsLibros.length; i++) {

		    		index = buscarValorEnArrObj(scope.librosEstanteria, 'id_libro', arrIdsLibros[i]);

		    		if( index == -1 ){	// si el libro no está en el scope, se añade

		    			index = buscarValorEnArrObj(scope.librosAnadidos, 'id_libro', arrIdsLibros[i]);

						scope.librosEstanteria.push(scope.librosAnadidos[index]);

						scope.actualizarNumLibrosEstanteria();
		    		}	
		    	}
		    });

			$('#notificacionesModalAltaLibroEstanteria span').html('Libros añadidos.');

			$('#notificacionesModalAltaLibroEstanteria').addClass('alert-success');
		} else{

			$('#notificacionesModalAltaLibroEstanteria span').html(data.descripcionError);

			$('#notificacionesModalAltaLibroEstanteria').addClass('alert-danger');
		}

		$('#notificacionesModalAltaLibroEstanteria').removeClass('hidden');
	});
}