angular.module('readArkrit')
  .controller('alumnoCtrl', function ($scope, DTOptionsBuilder) {

  	$scope.usuario  = {};
    $scope.alumno = {};
    $scope.alumnos = [];
    $scope.titulaciones;
	$scope.cursos;
	$scope.modCursos;
	$scope.modAlumno = {};
	$scope.modAlumno.usuario = {};
	$scope.indexModificando;

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

    $scope.cargarModificarAlumno = function(id, indexScope){
    	$scope.modAlumno = {};
    	$scope.modAlumno.usuario = {};
		$scope.indexModificando = indexScope;
		$('#menu-adminAlumno li').removeClass('active');

		peticionAJAX('./php/alumno.php', {
			opcion : 'alumno',
			accion : 'obtener',
			idAlumno: id
		}, false)
		.done(function( data, textStatus, jqXHR ){
			if( data.error )
				swal("Editar alumno", "Error recuperando los datos.", "error");
			else{
				console.log(data);
				if(data.alumno.bloqueado == '1' || data.alumno.bloqueado == 'true' || data.alumno.bloqueado == 1)
					data.alumno.bloqueado = true;
				else
					data.alumno.bloqueado = false;

				$scope.modAlumno.idAlumno		 			= data.alumno.idAlumno;	
				$scope.modAlumno.idUsuario		 			= data.alumno.id_usuario;	
				$scope.modAlumno.usuario.nombre 			= data.alumno.nombre;	
				$scope.modAlumno.usuario.primerApellido 	= data.alumno.primer_apellido;		
				$scope.modAlumno.usuario.segundoApellido 	= data.alumno.segundo_apellido;		
				$scope.modAlumno.usuario.fNacimiento 		= data.alumno.f_nacimiento;		
				$scope.modAlumno.usuario.correo 			= data.alumno.correo;		
				$scope.modAlumno.usuario.nombreUsuario 		= data.alumno.nombre_usuario;
				$scope.modAlumno.usuario.contrasena 		= '';
				$scope.modAlumno.usuario.contrasenaRepetida = '';
				$scope.modAlumno.numExpediente				= data.alumno.num_expediente;
				$scope.modAlumno.idTitulacion 				= data.alumno.id_titulacion;
				$scope.modAlumno.curso 						= data.alumno.curso;
				$scope.modAlumno.usuario.bloqueado			= data.alumno.bloqueado;

				$scope.modAlumno.curso = parseInt(data.alumno.curso);
				console.log($scope.modAlumno);
				$scope.cargarCursosModificacion(true);
			}
		});
    }

    $scope.modificarAlumno = function(){
    	var errores = '';
    	var validarContrasena = true;

      	$('#erroresModificar').addClass('hidden');
      	$('#erroresModificar span').html('');
      	$('#exitoModificar').addClass('hidden');
      	$('#exitoModificar span').html('');


		console.log('DATOS PETICION MODIFICAR: ');      
      	console.log($scope.modAlumno);

    	if($scope.modAlumno.usuario.bloqueado === '1' || $scope.modAlumno.usuario.bloqueado == 'true')
    		$scope.modAlumno.usuario.bloqueado = true;
    	else if($scope.modAlumno.usuario.bloqueado === '0'|| $scope.modAlumno.usuario.bloqueado == 'false')
    		$scope.modAlumno.usuario.bloqueado = false;

    	if($scope.modAlumno.usuario.contrasena === undefined || $scope.modAlumno.usuario.contrasena == ''){
    		$scope.modAlumno.usuario.contrasena = '';
			validarContrasena = false;
    	}

      	//VALIDACIÓN
    	errores = validarCamposAlumno($scope.modAlumno, false);
    	errores += validarCamposUsuario($scope.modAlumno.usuario, false, validarContrasena, true);

      	//PETICIÓN
      	if(errores==''){

			var alumno = {};
			alumno.idAlumno 		= $scope.modAlumno.idAlumno;
			alumno.idUsuario 		= $scope.modAlumno.idUsuario;
			alumno.idTitulacion		= $scope.modAlumno.idTitulacion;
			alumno.numExpediente 	= $scope.modAlumno.numExpediente;
			alumno.curso 			= $scope.modAlumno.curso;


      		peticionAJAX('./php/alumno.php', {
				opcion: 'alumno',
				accion: 'modificar',
				alumno: alumno,
				usuario: $scope.modAlumno.usuario
			}, false).done(function(data,textStatus,jqXHR){
				   	if(data.error){
				   		errores = 'Error: datos manipulados.';
				   	} else {
				   		swal("Alumno modificado", "");

				   		$scope.alumnos.splice($scope.indexModificando,1);
				   		$scope.alumnos.push(data.alumno);
				   		$scope.$apply();
				   		$scope.dtOptions = DTOptionsBuilder.fromFnPromise( $scope.librosAnadidos ).withOption('stateSave', true).withDataProp('data');

					    $scope.reloadData = reloadData;
					    $scope.dtInstance = {};

					    function reloadData() {
					        var resetPaging = false;
					        $scope.dtInstance.reloadData(callback, resetPaging);
					    }
				   	}
	     	});
		   
      	}

      	if(errores != ''){
	        var html =  '<b> Se han encontrado errores al rellenar el formulario: </b>' +
	              '<ul>' +
	                  errores
	              '</ul>';
	        $('#erroresModificar').removeClass('hidden');
	        $('#erroresModificar span').html(html);
       } else{
        	var html =  '<b> Cambios introducidos con éxito. </b>';
        	$('#exitoModificar').removeClass('hidden');
        	$('#exitoModificar span').html(html);           	
       }
    }

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
				alumno: alumno,
				administracion: true

			})
			.done(function( data, textStatus, jqXHR ){

				if( data.error ){

					swal("Datos incorrectos", data.descripcionError, "error");
				} else {

					usuario.idUsuario   = data.idUsuario;
					alumno.idAlumno 	= data.idAlumno;
					alumno.idUsuario  	= data.idUsuario;
					peticionAJAX('./php/alumno.php',{
						opcion: 'alumno',
						accion: 'obtener',
						idAlumno: data.idAlumno
					},false)				
					.done(function(data2,textStatus,jqXHR){
						console.log(data2.alumno);
						$scope.alumnos.push(data2.alumno);
						$scope.$apply();
						console.log($scope.alumnos);
					});

					$scope.dtOptions = DTOptionsBuilder.fromFnPromise( $scope.librosAnadidos ).withOption('stateSave', true).withDataProp('data');

				    $scope.reloadData = reloadData;
				    $scope.dtInstance = {};

				    function reloadData() {
				        var resetPaging = false;
				        $scope.dtInstance.reloadData(callback, resetPaging);
				    }
					$scope.usuario = {};
					$scope.alumno = {};

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

    $scope.cargarCursosModificacion = function(iniciar = false){
     	var encontrado = false;
    	var i = 0;

    	$('#modCurso').attr('disabled', true);
    	$scope.modCursos = '';
    	if(!iniciar){
    		$scope.modAlumno.curso = '';
    	}
    	
    	while(!encontrado && i<$scope.titulaciones.length){    		
    		if($scope.titulaciones[i]['id_titulacion'] == $scope.modAlumno.idTitulacion){
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
    			$('#modCurso').attr('disabled', false);
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
    $('#modFNacimiento').mask("99/99/9999",{placeholder:"dd/mm/aaaa"});

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



	$('#modContrasena').focus(function(){

						$(this).removeClass('text-danger text-success');

						$('#infoContrasena').removeClass('hidden');
					})
					.blur(function(){

					    if( contrasenaSegura(this.value) )
					    	$(this).addClass('text-success');
					    else
					    	$(this).addClass('text-danger');
					});


	$('#modContrasenaRepetida').focus(function(){

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