angular.module('readArkrit')
  .controller('clubLecturaCtrl', function ($scope, DTOptionsBuilder) {

    $scope.alumnos = [];
    $scope.profesores = [];
    $scope.titulaciones = obtenerValores('titulacion');
	$scope.cursos;

	$scope.alumnosMiembros 	  = [];
	$scope.profesoresMiembros = [];
	$scope.clubsLectura 	  = [];

	$scope.clubLectura = {};

    // FUNCIONES
	$scope.limpiarDatosScope = function(){

		// limpia algunos datos del scope y deja las tablas en blanco (tr sin seleccionar)

		$scope.alumnosMiembros 	  = [];
		$scope.profesoresMiembros = [];
		$scope.clubLectura        = {};

		$('tr.selected').removeClass('selected');
	};    

    $scope.listarAlumnos = function(){

    	peticionAJAX('./php/alumno.php', {

			opcion: 'alumno',
			accion: 'listar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				$('#tablaListado').removeClass('hidden');
				$scope.alumnos = $.makeArray(data.alumnos);	
			}
		});
    };

    $scope.listarProfesores = function(){

    	peticionAJAX('./php/profesor.php', {

			opcion: 'profesor',
			accion: 'listar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				$('#tablaListado2').removeClass('hidden');
				$scope.profesores = $.makeArray(data.profesores);
			}
		});
    };

    $scope.listarClubsLectura = function(){

    	peticionAJAX('./php/clubLectura.php', {

			opcion: 'clubLectura',
			accion: 'listar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				$('#tablaListado5').removeClass('hidden');
				$scope.clubsLectura = $.makeArray(data.clubsLectura);
			}
		});
    };

    $scope.listarMiembrosClubLectura = function(indexScope){

    	$scope.limpiarDatosScope();

    	$scope.clubLectura = $scope.clubsLectura[indexScope];

    	peticionAJAX('./php/clubLectura.php', {

			opcion: 'miembrosClub',
			accion: 'listar',
			idClub: $scope.clubLectura.id_club
		})
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				var miembrosClub = $.makeArray(data.idsMiembrosClub);
				var index        = -1;

				for (var i = 0; i < miembrosClub.length; i++) {
					
					index = buscarValorEnArrObj($scope.alumnos, 'id_usuario', miembrosClub[i]);

					if( index != -1 ) 
						$scope.alumnosMiembros.push( $scope.alumnos[index] );
					else {

						index = buscarValorEnArrObj($scope.profesores, 'id_usuario', miembrosClub[i]);

						$scope.profesoresMiembros.push( $scope.profesores[index] );
					}

					$('tr[data-idUsuario=' + miembrosClub[i] + ']').addClass('selected');
				}

				$('#tablaListado3').removeClass('hidden');
    			$('#tablaListado4').removeClass('hidden');

    			$scope.$apply();
			}
		});
    };

    $scope.modificarAlumnosClub = function( e ){

    	var idUsuario = $(e.currentTarget).attr('data-idUsuario');
    	var index     = -1;	

    	if( $(e.currentTarget).hasClass('selected') ){
    		index = buscarValorEnArrObj($scope.alumnosMiembros, 'id_usuario', idUsuario);

    		$scope.alumnosMiembros.splice(index,1);
    	} else {

    		index = buscarValorEnArrObj($scope.alumnos, 'id_usuario', idUsuario);

    		$scope.alumnosMiembros.push( $scope.alumnos[index] );
    	}

		$(e.currentTarget).toggleClass('selected');
    };

    $scope.modificarProfesoresClub = function( e ){

    	var idUsuario = $(e.currentTarget).attr('data-idUsuario');
    	var index     = -1;

    	if( $(e.currentTarget).hasClass('selected') ){

    		index = buscarValorEnArrObj($scope.profesoresMiembros, 'id_usuario', idUsuario);

    		$scope.profesoresMiembros.splice(index,1);
    	} else {

    		index = buscarValorEnArrObj($scope.profesores, 'id_usuario', idUsuario);

    		$scope.profesoresMiembros.push( $scope.profesores[index] );
    	}

		$(e.currentTarget).toggleClass('selected');
    };

	$scope.abrirClubLectura = function() {

		var miembrosClub 	   = [];
    	var erroresClubLectura = validarCamposClubLectura($scope.clubLectura);

    	if( $scope.clubLectura.todosLosAlumnosDelCurso === undefined && $scope.alumnosMiembros.length == 0 && $scope.profesoresMiembros.length == 0 )
    		erroresClubLectura += '<li>No puedes abrir un grupo sin antes seleccionar los miembros.</li>';
    	if( $scope.profesoresMiembros.length == 0)
    		erroresClubLectura += '<li>Se debe seleccionar un profesor para crear un grupo.</li>';

		if( erroresClubLectura != '' ){

			var html = 	'<b> Se han encontrado errores al rellenar el formulario: </b>' +
						'<ul>' +
				   			erroresClubLectura +
						'</ul>';

			$('#erroresAbrirClubLectura span').html(html);
			$('#erroresAbrirClubLectura').removeClass('hidden');

		} else {

			cerrarAlerta($('#erroresAbrirClubLectura'));

			var clubLectura = new ClubLectura(	'', // id club
												'', /* ID_USUARIO */ 
												$scope.clubLectura.nombre,
												null, // f_inicio
												null, // f_fin
												$scope.clubLectura.idTitulacion,
												$scope.clubLectura.curso);
			console.log(clubLectura);

			if( $scope.alumnosMiembros.length != 0 || $scope.profesoresMiembros.length != 0){

				$.merge(miembrosClub, $scope.alumnosMiembros);
				$.merge(miembrosClub, $scope.profesoresMiembros);

				peticionAJAX('./php/clubLectura.php', {

					opcion: 'clubLectura',
					accion: 'abrir',
					clubLectura: clubLectura,
					anadirTodosLosAlumnosDelCurso: $scope.clubLectura.todosLosAlumnosDelCurso, // COMPROBAR SI SE HA SELECCIONADO EL CHECK, Y SI ES ASÍ, AÑADIRLOS EN EL PHP
					miembrosClub: miembrosClub
				})
				.done(function( data, textStatus, jqXHR ){

					if( data.error )
						swal("Datos incorrectos", data.descripcionError, "error");
					else{
						swal("Abrir Club de Lectura", "Club de lectura creado correctamente", "success");						
						$scope.clubsLectura.push(data.club);
						console.log($scope.clubsLectura);
					}

				});
			}
		}
    };

    $scope.modificarMiembrosClubLectura = function() {

    	var miembrosClub = [];

    	if( $scope.alumnosMiembros.length != 0 || $scope.profesoresMiembros.length != 0){

			$.merge(miembrosClub, $scope.alumnosMiembros);
			$.merge(miembrosClub, $scope.profesoresMiembros);

			cerrarAlerta($('#erroresModificarMiembrosClubLectura'));

			peticionAJAX('./php/clubLectura.php', {

				opcion: 'miembrosClub',
				accion: 'modificar',
				idClub: $scope.clubLectura.id_club,
				miembrosClub: miembrosClub
			})
			.done(function( data, textStatus, jqXHR ){

				if( data.error )
					swal("Modificar Miembros Club de Lectura", data.descripcionError, "error");
				else
					swal("Modificar Miembros Club de Lectura", "Club de lectura modificado correctamente", "success");
			});
		} else {

			$('#erroresModificarMiembrosClubLectura span').html('No puedes tener un grupo sin miembros.');
			$('#erroresModificarMiembrosClubLectura').removeClass('hidden');
		}
    };

    $scope.cerrarClubLectura = function(idClub, indexScope){

    	peticionAJAX('./php/clubLectura.php', {

			opcion : 'clubLectura',
			accion : 'cerrar',
			idClub : idClub
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( data.error )
				swal("Cerrar Club de Lectura", "Error en la transacción.", "error");
			else{

				swal("Cerrar Club de Lectura", "Libro eliminado con éxito.", "success");

				$scope.clubsLectura.splice(indexScope, 1);
			}
		});

		$scope.dtOptions = DTOptionsBuilder.fromFnPromise( $scope.librosAnadidos ).withOption('stateSave', true).withDataProp('data');

	    $scope.reloadData = reloadData;
	    $scope.dtInstance = {};

	    function reloadData() {
	        var resetPaging = false;
	        $scope.dtInstance.reloadData(callback, resetPaging);
	    }

    };

    $scope.cargarCursos = function(){

    	var index    = buscarValorEnArrObj($scope.titulaciones, 'id_titulacion', $scope.clubLectura.idTitulacion);
    	var duracion = $scope.titulaciones[index]['duracion'];
    	var cursos   = [];

    	$('#curso').attr('disabled', true);

		for(var i=0; i<duracion; i++ ){

			cursos[i] = {
				'nombre' : (i+1)+'º',
				'curso'	 : (i+1)
			}
		}

		$scope.cursos = cursos;

    	$('#curso').attr('disabled', false);
    }


    // EVENTOS

    cargarJS("./js/clases/ClubLectura.js");

    	// Listar
    $scope.listarAlumnos();
    $scope.listarProfesores();
    $scope.listarClubsLectura();

	}); // fin controller