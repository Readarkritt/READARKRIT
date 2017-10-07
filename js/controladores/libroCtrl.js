angular.module('readArkrit')
  .controller('libroCtrl', function ($scope, DTOptionsBuilder) {

  	$scope.usuario  = {};
    $scope.profesor = {};
    $scope.profesores = [];

    $scope.profesor.esAdmin = false;
	$scope.profesor.evitarNotificacion = false;

	$scope.titulaciones = obtenerTitulaciones();

    // FUNCIONES
    $scope.listarProfesores = function(){

    	peticionAJAX('./php/profesor.php', {

			opcion: 'profesor',
			accion: 'listar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				$('#tablaListado').removeClass('hidden');
				$scope.profesores = $.makeArray(data.profesores);

				$.each( $scope.profesores, function( index, value ){
				    $scope.profesores[index].es_admin = smallintTOsino($scope.profesores[index].es_admin);
				    $scope.profesores[index].evitar_notificacion = smallintTOsino($scope.profesores[index].evitar_notificacion);
				});
			}
		});
    };


    $scope.eliminarProfesor = function(idProfesor, indexScope){

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
    };

	$scope.altaProfesor = function() {

    	var erroresUsuario  = validarCamposUsuario($scope.usuario);
    	var erroresProfesor = validarCamposProfesor($scope.profesor);

		if( erroresUsuario != '' || erroresProfesor != '' ){

			var html = 	'<b> Se han encontrado errores al rellenar el formulario: </b>' +
						'<ul>' +
				   			erroresUsuario +
				   			erroresProfesor +
						'</ul>';

			$('#erroresAltaProfesor span').html(html);
			$('#erroresAltaProfesor').removeClass('hidden');

		} else {

			cerrarAlerta($('#erroresAltaProfesor'));

			var usuario = new Usuario(	'', // id usuario
										$scope.usuario.nombre, 
										$scope.usuario.primerApellido,
										$scope.usuario.segundoApellido,
										$scope.usuario.fNacimiento,
										$scope.usuario.correo,
										$scope.usuario.nombreUsuario,
										$scope.usuario.contrasena,
										0,
										null);

	    	var profesor = new Profesor('',	// id profesor
										'',	// id usuario
										$scope.profesor.esAdmin,
										$scope.profesor.evitarNotificacion );

			console.log(usuario);
			console.log(profesor);

			peticionAJAX('./php/profesor.php', {

				opcion: 'profesor',
				accion: 'alta',
				usuario: usuario,
				profesor: profesor
			})
			.done(function( data, textStatus, jqXHR ){

				if( data.error ){

					swal("Datos incorrectos", data.descripcionError, "error");
				} else {

					usuario.idUsuario   = data.idUsuario;
					profesor.idProfesor = data.idProfesor;
					profesor.idUsuario  = data.idUsuario;

					swal("Alta Profesor", "Profesor creado correctamente", "success");
				}

			});
		}
    };

    $scope.procesarLibrosExcel = function(){

    	var ficheroExcel = $('#inputExcel').prop('files')[0];
    	var ficheroComprimido = $('#inputComprimido').prop('files')[0];

    	if( ficheroExcel === undefined || ficheroComprimido === undefined ){

    		var html = 'No has subido todos los ficheros.';

    		$('#erroresProcesamientoLibros span').html(html);
			$('#erroresProcesamientoLibros').removeClass('hidden');

    	} else {

    		var nombreFicheroExcel = ficheroExcel.name;
	    	var extensionExcel     = nombreFicheroExcel.split('.').pop();

	    	var nombreFicheroComprimido = ficheroComprimido.name;
	    	var extensionComprimido     = nombreFicheroComprimido.split('.').pop();

	    	var formData = new FormData();                  
		    formData.append('ficheroExcel', ficheroExcel);
		    formData.append('ficheroComprimido', ficheroComprimido);
		    formData.append('opcion', 'libro');
		    formData.append('accion', 'procesarLibrosExcel');


	    	if( (extensionExcel == 'xls' || extensionExcel == 'xlsx') && extensionComprimido == 'zip' ){

				$.ajax({
	                url: './php/libro.php',
	                dataType: 'text',
	                cache: false,
	                contentType: false,
	                processData: false,
	                data: formData,                         
	                type: 'POST',
	                success: function(data){
	                    alert(data);
	                }
	     		});

	    	} else {

	    		var html = 'Ficheros con formato incorrecto.';

	    		$('#erroresProcesamientoLibros span').html(html);
				$('#erroresProcesamientoLibros').removeClass('hidden');
	    	}
    	}
    };

    // EVENTOS

    /*cargarJS("./js/clases/Usuario.js");
    cargarJS("./js/clases/Profesor.js");*/

    	// Listar
    //$scope.listarProfesores();

    	// Añadir

	}); // fin controller