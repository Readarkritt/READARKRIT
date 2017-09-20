angular.module('readArkrit')
  .controller('profesorCtrl', function ($scope) {
    
  	$scope.usuario  = {};
    $scope.profesor = {};
    $scope.profesores = [];

    $scope.profesor.esAdmin = false;
	$scope.profesor.evitarNotificacion = false;

    // FUNCIONES
    $scope.listarProfesores = function(){

    	peticionAJAX('./php/profesor.php', {

			opcion: 'profesor',
			accion: 'listar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){
				
				$scope.profesores = data.profesores;
				//$('#tablaListado').DataTable();
			}else{

				$('#tablaListado').hide();
				$('#erroresListarProfesores span').html('No hay datos disponibles para mostrar en la tabla.');
				$('#erroresListarProfesores').removeClass('hidden');
			}
		});
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
										0);

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


    $scope.invitarProfesor = function (){

    	if( emailCorrecto($scope.usuario.correo) ){

    		peticionAJAX('./php/profesor.php', {

				opcion: 'profesor',
				accion: 'invitar',
				correo: $scope.usuario.correo
			})
			.done(function( data, textStatus, jqXHR ){

				if( data.error )
					swal("Datos incorrectos", data.descripcionError, "error");
				else 
					swal("Enviar Invitacion", "La invitación se ha enviado con éxito.", "success");
			});

    	} else {

    		$('#erroresInvitarProfesor span').html("El correo introducido no es válido");
    		$('#erroresInvitarProfesor').removeClass('hidden');
    	}
    };

    // EVENTOS

    cargarJS("./js/clases/Usuario.js");
    cargarJS("./js/clases/Profesor.js");

    	// Listar
    $scope.listarProfesores();

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