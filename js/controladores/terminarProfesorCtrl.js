angular.module('readArkrit')
  .controller('terminarProfesorCtrl', function ($scope) {

  	$("#navBarGeneral").html('');

  	 $('#nombre').prop("disabled", false);
  	 $('#primerApellido').prop("disabled", false);
  	 $('#segundoApellido').prop("disabled", false);
  	 $('#fNacimiento').prop("disabled", false);
  	 $('#nombreUsuario').prop("disabled", false);

  	 $('#correo').prop("disabled", true);

  	 $('#botonEnviar').html("Terminar");
  	 $('#titulo').html("Crear profesor");

	$scope.profesor = {};
    $scope.copiaProfesor = {};

    $('#fNacimiento').mask("99/99/9999",{placeholder:"dd/mm/aaaa"});

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

  	peticionAJAX('./php/profesor.php', {
  		opcion: 'profesor',
  		accion: 'recuperarInvitado'
  	}, false)
  	.done(function(data, textStatus, jqXHR){
  		$scope.profesor = data.profesor;
  		$scope.copiaProfesor = jQuery.extend(true,{},$scope.profesor);
      	$scope.profesor.usuario.contrasenaRepetida = '';
      	$scope.profesor.esAdmin =  false;      	
  	});
	

  	$scope.modificarUsuario = function(){
    	var errores = '';
    	var datosUsuario = {};
    	var datosProfesor = {};

      	$('#errores').addClass('hidden');
      	$('#errores span').html('');
      	$('#exito').addClass('hidden');
      	$('#exito span').html('');

      	datosUsuario.idUsuario 		 	= '';
		datosUsuario.nombre    			= $scope.profesor.usuario.nombre;
		datosUsuario.primerApellido  	= $scope.profesor.usuario.primerApellido;
		datosUsuario.segundoApellido 	= $scope.profesor.usuario.segundoApellido;
		datosUsuario.fNacimiento 	 	= $scope.profesor.usuario.fNacimiento;
		datosUsuario.correo 		 	= $scope.profesor.usuario.correo;
		datosUsuario.nombreUsuario   	= $scope.profesor.usuario.nombreUsuario;
		datosUsuario.contrasena		 	= $scope.profesor.usuario.contrasena;
		datosUsuario.contrasenaRepetida = $scope.profesor.usuario.contrasenaRepetida;
		datosUsuario.bloqueado	     	= 0;
		datosUsuario.fBaja			 	= null;

		datosProfesor.idProfesor 	 		= '';
		datosProfesor.idUsuario     		= '';
		datosProfesor.esAdmin 				= $scope.profesor.esAdmin;
		datosProfesor.evitarNotificacion  	= $scope.profesor.evitarNotificacion;

      	//Comprobar validez de los campos
	    errores = validarCamposUsuario(datosUsuario);
	    errores += validarCamposProfesor(datosProfesor);

	    if(errores == ''){
		     peticionAJAX('./php/profesor.php', {
		        opcion:'profesor',
		        accion:'terminar',
		        profesor:datosProfesor,
		        usuario:datosUsuario
		    }, false).
		    done(function(data,textStatus,jqXHR){
			   	if(data.error){
			   		swal("Datos incorrectos", data.descripcionError, "error");
			   	} else {
			   		sessionStorage.setItem('tokenREADARKRIT', data.token);
			   		$httpProvider.defaults.headers.common['token'] = data.token;

			   		swal("Alta Profesor", "Profesor creado correctamente, ¡bienvenido!", "success")
					.then((value) => {
						window.location.href='./estadisticas';
					});

			   	}
      		});
	  	}

	  	if( errores != ''){
	        var html =  '<b> Se han encontrado errores al rellenar el formulario: </b>' +
	              '<ul>' +
	                  errores
	              '</ul>';
	        $('#errores').removeClass('hidden');
	        $('#errores span').html(html);
       } else{
        	var html =  '<b> Cambios introducidos con éxito. </b>';
        	$('#exito').removeClass('hidden');
        	$('#exito span').html(html);           	
       }
    };

  });