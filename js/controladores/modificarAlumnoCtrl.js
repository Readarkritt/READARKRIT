angular.module('readArkrit')
  .controller('modificarAlumnoCtrl', function ($scope) {

  	$scope.alumno = {};
    $scope.copiaAlumno = {};


    $("li.active").removeClass('active');

  	peticionAJAX('./php/alumno.php', {
  		opcion: 'alumno',
  		accion: 'recuperarConectado'
  	}, false)
  	.done(function(data, textStatus, jqXHR){
  		$scope.alumno = data.alumno;
      $scope.copiaAlumno =  jQuery.extend(true, {}, $scope.alumno);
      $scope.alumno.usuario.contrasenaRepetida = '';
      crearAvatar($scope.alumno.usuario.nombreUsuario);
      $('#nombreAvatar').html($scope.alumno.usuario.nombreUsuario);
  	});

    $scope.modificarUsuario = function(){      
      var errores = '';
      $('#errores').addClass('hidden');
      $('#errores span').html('');
      $('#exito').addClass('hidden');
      $('#exito span').html('');

      //Comprobar validez de campos
      if($scope.alumno.usuario.correo != $scope.copiaAlumno.usuario.correo){
        errores += validarCorreo($scope.alumno.usuario.correo);
      }
      if($scope.alumno.usuario.contrasena != ""){
        errores += validarPass($scope.alumno.usuario.contrasena, $scope.alumno.usuario.contrasenaRepetida);
      }
      if( parseInt($scope.alumno.idTitulacion) <= 0 )
        errores += '<li>Elija una titulación válida.</li>';
     if( parseInt($scope.alumno.curso) <= 0 )
        errores += '<li>Elija un curso válido.</li>';

      if( errores == ''){
            peticionAJAX('./php/alumno.php', {
              opcion:'alumno',
              accion:'modificar',
              alumno:$scope.alumno
            }, false).
            done(function(data,textStatus,jqXHR){
              if(data.errorCorreo){
                errores += '<li>Ha habido un problema al cambiar el correo</li>';
              } else{
                $scope.alumno.usuario.correo = data.correo;
                $scope.copiaAlumno.usuario.correo = data.correo;                
              }
              if(data.errorContrasena){
                errores += '<li>Ha habido un problema al cambiar la contraseña</li>';
              }
              if(data.errorTitulacion){
                errores += '<li>Ha habido un problema al cambiar la titulación</li>';
              }
              if(data.errorCurso){
                errores += '<li>Ha habido un problema al cambiar el curso</li>';
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
    }

  });