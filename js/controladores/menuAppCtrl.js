angular.module('readArkrit')
  .controller('menuAppCtrl', ['$scope', '$location', function ($scope, $location) {
      $scope.nombreUsuario = '';
      $scope.rol = '';

      // MENÚ
      cargarMenu($scope);
      marcarMenu();

      $(".sidebar-wrapper > .nav > li").click(function(e){

        // 1) Quitamos la clase 'active' al elemento que esté marcado
        // 2) Y se la ponemos al elemento en el que se ha hecho click

        $("li.active").removeClass('active');

        $(this).addClass('active');
      });



      

      //Comprobar si es invitado
      var parametros = {};
      var respuesta = {};
      parametros.opcion = 'usuario';
      parametros.accion = 'getRol';

      respuesta = peticionAJAX('./php/usuario.php', parametros, false);


      respuesta.done(function(data, textStatus, jqXHR ){
        $scope.rol = data.rol;
      });
      //Redireccionar si es profesor invitado
      if( $scope.rol != null){
        if( $scope.rol == 'invitado'){
          $location.path('/profesor/terminar');
          $scope.$apply();    

        //Pintar ajustesCuenta
        } else if($scope.rol == 'visitante'){
          var html = '<li>'+
                      '<a href="#" id="loggearse"><i class="fa fa-user-circle" aria-hidden="true" ></i>&nbsp; Iniciar sesión</a>'+
                    '</li>'+
                    '<li>'+
                      '<a href="#" id="registrarse"><i class="fa fa-hand-paper-o" aria-hidden="true"></i>&nbsp; Registrarse</a>'+
                    '</li>';
          $('#ajustesCuenta').html(html);
        }else{
          var html = '<li>'+
                      '<a href="#" id="ajustesUsuario"><i class="fa fa-cogs" aria-hidden="true" ></i>&nbsp; Ajustes</a>'+
                    '</li>'+
                    '<li>'+
                      '<a href="#" id="cerrarSesion"><i class="fa fa-power-off" aria-hidden="true"></i>&nbsp; Cerrar Sesión</a>'+
                    '</li>';
          $('#ajustesCuenta').html(html);
        }
      }



      //desactivar enlace del menú de arriba (cerrar sesión)
      $("#dropdown").click(function(e){

        e.preventDefault();
      });

      $("#loggearse").click(function(){
        window.location.replace('./');
      });

      $("#registrarse").click(function(){
        window.location.replace('./html/alumno/altaAlumno.html');
      });

      $("#cerrarSesion").click(function(){
        sessionStorage.removeItem('tokenREADARKRIT');
        window.location.replace('./');
      });

      $("#ajustesUsuario").click(function(){
        var parametros = {};
        var respuesta = {};

        parametros.opcion = 'usuario';
        parametros.accion = 'getRol';

        respuesta = peticionAJAX('./php/usuario.php', parametros);

        respuesta.done(function(data, textStatus, jqXHR ){
          if(data.rol != null){
            if(data.rol == 'alumno'){
              $location.path('/alumno/modificar');
              $scope.$apply();
            } else if(data.rol == 'profesor' || data.rol == 'admin'){
              $location.path('/profesor/modificar');
              $scope.$apply();
            }
          }
        });
      });
}]);


/*angular.module('readArkrit')
  .controller('LibroCtrl', function ($scope) {
    //$scope.message = "Inicio.";

    $('#tablaEstanteria').DataTable();
    $('#tablaEstanteriasQueSigo').DataTable();
    $('#tablaRecomendacionesArkrit').DataTable();

  });*/