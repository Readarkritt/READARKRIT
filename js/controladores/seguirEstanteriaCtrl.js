angular.module('readArkrit')
  .controller('seguirEstanteriaCtrl', function ($scope, DTOptionsBuilder) {

    $scope.usuarios           = [];
	$scope.estanteriasUsuario = [];
	$scope.idUsuario          = 0;

    // FUNCIONES
	$scope.limpiarDatosScope = function(){

		// limpia algunos datos del scope y deja las tablas en blanco (tr sin seleccionar)

		$scope.estanteriasUsuario = [];

		var table = $('#tablaListado').DataTable();

		table.$('tr.selected').removeClass('selected');
	};    

    $scope.listarUsuarios = function(){

    	peticionAJAX('./php/usuario.php', {

			opcion: 'usuario',
			accion: 'listar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				$('#tablaListado').removeClass('hidden');
				$scope.usuarios = $.makeArray(data.usuarios);	
			}
		});
    };

    $scope.verEstanterias = function( e ){

    	$scope.limpiarDatosScope();

    	peticionAJAX('./php/estanteria.php', {

			opcion: 'estanteria',
			accion: 'listarEstanteriasSeguidas',
			propietarioEstanteria: $(e.currentTarget).attr('data-idUsuario')	// se le pasa el id del usuario conectado para saber si sigue o no las estanterías del usuario seleccionado (propietario estantería) 
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				$('#tablaListado').removeClass('hidden');
				$scope.estanteriasUsuario = $.makeArray(data.estanterias);	
			}
		});

		$(e.currentTarget).addClass('selected');
    };

    $scope.listarlibrosEstanteria = function(idEstanteria){

    	var respuesta = [];

    	peticionAJAX('./php/estanteria.php', {

			opcion: 'estanteria',
			accion: 'listadoLibrosCompleto',
			idEstanteria: idEstanteria
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error )
				respuesta = $.makeArray(data.libros);	
		});

		return respuesta;
    };

    $scope.generarCarousel = function(elementoHTML, datosCarousel){

		var html = 	'<div id="carousel-generic" class="carousel slide" data-interval="false" data-ride="carousel">' +

				'<div class="carousel-inner" role="listbox">' +
					'<div class="item active">' +
						'<img src="./img/portadasLibros/' + datosCarousel[0].portada + '" class="portada">' +
						'<div class="carousel-caption">' +
		                    datosCarousel[0].titulo +
		                '</div>' +
					'</div>';

		for (var i = 1; i < datosCarousel.length; i++)
			html += '<div class="item">' + 
						'<img src="./img/portadasLibros/' + datosCarousel[i].portada + '" class="portada">' +
						'<div class="carousel-caption">' +
		                    datosCarousel[i].titulo +
		                '</div>' +
					'</div>';	

		html += '</div>' +
				  '<a class="left carousel-control" href="#carousel-generic" data-slide="prev">' +
				    '<i class="fa fa-chevron-left" aria-hidden="true"></i>' +
				  '</a>' +
				  '<a class="right carousel-control" href="#carousel-generic" data-slide="next">' +
				    '<i class="fa fa-chevron-right" aria-hidden="true"></i>' +
				  '</a>' +
				'</div>';


		$(elementoHTML).html(html);

		$('.carousel-control').click(function( e ) {
			e.preventDefault();
		});
	};

    $scope.carouselLibrosEstanteria = function(idEstanteria, nombreEstanteria){

    	var datosCarousel = $scope.listarlibrosEstanteria(idEstanteria);

    	var html = '<div id="carouselLibrosEstanteriaModal" class="modal fade" role="dialog">' +
					  '<div class="modal-dialog">' +
					    '<div class="modal-content">' +

					      '<div class="modal-header">' +
					        '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
					        '<h4 class="modal-title">' + nombreEstanteria + '</h4>' +
					      '</div>' +

					      '<div class="modal-body" id="contenedorCarousel">' +
					      '</div>' +

					      '<div class="modal-footer">' +
					     	'&nbsp;' +
					      '</div>' +

					    '</div>' +
					  '</div>' +
					'</div>';

		$('#carouselLibrosEstanteriaModal').remove();	// borra modals anteriores

		$('body').append(html);

		$('#carouselLibrosEstanteriaModal').modal();

		$scope.generarCarousel('#contenedorCarousel', datosCarousel);
    };

    $scope.seguirEstanteria = function( e, idEstanteria ){

    	peticionAJAX('./php/estanteria.php', {

			opcion: 'estanteria',
			accion: 'seguirEstanteria',
			idEstanteria: idEstanteria
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if(data.error)
				swal("Seguir Estantería", data.descripcionError, "error");
			else
				$(e.currentTarget).find('.fa').toggleClass("fa-toggle-off fa-toggle-on");

		});
    };


    // EVENTOS

    	// Listar
    $scope.listarUsuarios();

	}); // fin controller