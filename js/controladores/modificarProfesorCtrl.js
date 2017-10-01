angular.module('readArkrit')
  .controller('modificarProfesorCtrl', function ($scope) {

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

  	$("li.active").removeClass('active');

  	peticionAJAX('./php/profesor.php', {
  		opcion: 'profesor',
  		accion: 'recuperarConectado'
  	}, false)
  	.done(function(data, textStatus, jqXHR){
  		$scope.profesor = data.profesor;
  		$scope.copiaProfesor = jQuery.extend(true,{},$scope.profesor);
      	$scope.profesor.usuario.contrasenaRepetida = '';
  	});

    $scope.modificarUsuario = function(){
    	var errores = '';
      	$('#errores').addClass('hidden');
      	$('#errores span').html('');
      	$('#exito').addClass('hidden');
      	$('#exito span').html('');

      	//Comprobar validez de los campos
	    if($scope.profesor.usuario.contrasena != ""){
	       errores += validarPass($scope.profesor.usuario.contrasena, $scope.profesor.usuario.contrasenaRepetida);
	    }
      if($scope.profesor.usuario.correo != $scope.copiaProfesor.usuario.correo){
        errores += validarCorreo($scope.profesor.usuario.correo);
      }

	    if(errores == ''){
		     peticionAJAX('./php/profesor.php', {
		        opcion:'profesor',
		        accion:'modificar',
		        profesor:$scope.profesor
		    }, false).
		    done(function(data,textStatus,jqXHR){
		    	if(data.errorContrasena){
          	errores += '<li>Ha habido un problema al cambiar la contraseña</li>';
        	}
          if(data.errorCorreo){
            errores += '<li>Ha habido un problema al cambiar el correo</li>';
          } else{
           $scope.profesor.usuario.correo = data.correo;
           $scope.copiaProfesor.usuario.correo = data.correo;                
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