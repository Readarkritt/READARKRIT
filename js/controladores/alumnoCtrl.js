angular.module('readArkrit')
  .controller('alumnoCtrl', function ($scope, DTOptionsBuilder) {

  	$scope.usuario  = {};
    $scope.alumno = {};
    $scope.alumnos = [];
    $scope.titulaciones;
	$scope.cursos;

    // FUNCIONES
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


    $scope.eliminarAlumno = function(idAlumno, indexScope){

    	peticionAJAX('./php/alumno.php', {

			opcion    : 'alumno',
			accion	  : 'eliminar',
			idAlumno: idAlumno
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( data.error )
				swal("Eliminar alumno", "Error en la transacción.", "error");
			else{

				swal("Alumno Eliminado", "Alumno eliminado con éxito.", "success");

				$scope.alumnos.splice(indexScope, 1);
			}
		});

		$scope.dtOptions = DTOptionsBuilder.fromFnPromise( $scope.alumnos ).withOption('stateSave', true).withDataProp('data');

	    $scope.reloadData = reloadData;
	    $scope.dtInstance = {};

	    function reloadData() {
	        var resetPaging = false;
	        $scope.dtInstance.reloadData(callback, resetPaging);
	    }
    };

	$scope.altaAlumno = function() {

		console.log($scope.usuario);

    	var erroresUsuario  = validarCamposUsuario($scope.usuario);
    	var erroresAlumno = validarCamposAlumno($scope.alumno);

		if( erroresUsuario != '' || erroresAlumno != '' ){

			var html = 	'<b> Se han encontrado errores al rellenar el formulario: </b>' +
						'<ul>' +
				   			erroresUsuario +
				   			erroresAlumno +
						'</ul>';

			$('#erroresAltaAlumno span').html(html);
			$('#erroresAltaAlumno').removeClass('hidden');

		} else {

			cerrarAlerta($('#erroresAltaAlumno'));

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

	    	var alumno = new Alumno('',	// id alumno
										'',	// id usuario
										$scope.alumno.numExpediente,
										$scope.alumno.idTitulacion,
										$scope.alumno.curso );

			console.log(usuario);
			console.log(alumno);

			peticionAJAX('./php/alumno.php', {

				opcion: 'alumno',
				accion: 'alta',
				usuario: usuario,
				alumno: alumno
			})
			.done(function( data, textStatus, jqXHR ){

				if( data.error ){

					swal("Datos incorrectos", data.descripcionError, "error");
				} else {

					usuario.idUsuario   = data.idUsuario;
					alumno.idAlumno 	= data.idAlumno;
					alumno.idUsuario  	= data.idUsuario;

					swal("Alta Alumno", "Alumno creado correctamente", "success");
				}

			});
		}
    };

    $scope.cargarCursos = function(){
     	var encontrado = false;
    	var i = 0;

    	$('#curso').attr('disabled', true);
    	$scope.cursos = '';
    	$scope.alumno.curso = '';
    	
    	while(!encontrado && i<$scope.titulaciones.length){
    		if($scope.titulaciones[i]['id_titulacion'] == $scope.alumno.idTitulacion){
    			encontrado =true;

    			var duracion = $scope.titulaciones[i]['duracion'];
    			var cursos = {};

    			for(i=0;i<duracion;i++){
    				cursos[i] = {
    					'nombre'	: (i+1)+'º',
    					'curso'		: (i+1)
    				}
    			}

    			$scope.cursos = cursos;
    			$('#curso').attr('disabled', false);
    		} else{
    			i++;
    		}
    	}

    }

   /* $scope.marcarTabla = function(e){
    	e.preventDefault();    	
        $(".navTabsResponsive>li").removeClass("active");
    	$(this).addClass("active");
    };*/

    // EVENTOS

    cargarJS("./js/clases/Usuario.js");
    cargarJS("./js/clases/Alumno.js");

    	//Cargar desplegables de los formularios
	$scope.titulaciones = obtenerValores('titulacion');

    	// Listar
    $scope.listarAlumnos();

    	// Añadir
    $('#fNacimiento').mask("99/99/9999",{placeholder:"dd/mm/aaaa"});

	$('#correo').focus(function(){

					$(this).removeClass('text-danger text-success');
				})
				.blur(function(){

				    if( emailCorrecto(this.value) ) // hasta 50 caracteres
				    	$(this).addClass('text-success');
				    else
				    	$(this).addClass('text-danger');
				});


	$('#comprobarDisponibilidadNombreUsuario').click(function(){

		var nombreUsuario = $('#nombreUsuario').val();

		$('#nombreUsuario').removeClass('text-danger text-success');

		// petición síncrona
		if( existeRegistro( 'nombre_usuario', nombreUsuario, 'usuario') )
			$('#nombreUsuario').addClass('text-danger');
		else
			$('#nombreUsuario').addClass('text-success');
	});


	$('#contrasena').focus(function(){

						$(this).removeClass('text-danger text-success');

						$('#infoContrasena').removeClass('hidden');
					})
					.blur(function(){

					    if( contrasenaSegura(this.value) )
					    	$(this).addClass('text-success');
					    else
					    	$(this).addClass('text-danger');
					});


	$('#contrasenaRepetida').focus(function(){

								$(this).removeClass('text-danger text-success');
							})
							.blur(function(){

							    if( contrasenaSegura(this.value) )
							    	$(this).addClass('text-success');
							    else
							    	$(this).addClass('text-danger');
							});




		// Invitar
	$('#correoInvitacion').focus(function(){

								$(this).removeClass('text-danger text-success');
							})
							.blur(function(){

							    if( emailCorrecto(this.value) ) // hasta 50 caracteres
							    	$(this).addClass('text-success');
							    else
							    	$(this).addClass('text-danger');
							});

	}); // fin controller