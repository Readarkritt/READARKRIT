angular.module('readArkrit')
  .controller('libroCtrl', function ($scope, DTOptionsBuilder) {

    $scope.librosAnadidos = [];

	$scope.titulaciones = obtenerValores('titulacion');
	$scope.paises 		= obtenerValores('pais');
	$scope.categorias 	= obtenerValores('categoriaLibro');

    // FUNCIONES
    $scope.listarLibrosAnadidos = function(){

    	peticionAJAX('./php/libroAnadido.php', {

			opcion: 'libroAnadido',
			accion: 'listar'
		}, false)
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


    $scope.eliminarLibroAnadido = function(idLibroAnadido, indexScope){

    	peticionAJAX('./php/libroAnadido.php', {

			opcion : 'libroAnadido',
			accion : 'eliminar',
			idLibroAnadido: idLibroAnadido
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( data.error )
				swal("Eliminar Libro", "Error en la transacción.", "error");
			else{

				swal("Libro Eliminado", "Libro eliminado con éxito.", "success");

				$scope.librosAnadidos.splice(indexScope, 1);
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
	                    console.log(data);
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
    $scope.listarLibrosAnadidos();

    	// Añadir

	}); // fin controller