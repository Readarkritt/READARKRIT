angular.module('readArkrit')
  .controller('menuAppCtrl', ['$scope', '$location', function ($scope, $location) {

      // MENÚ
      cargarMenu($scope);
      marcarMenu();

      $(".sidebar-wrapper > .nav > li").click(function(e){

        // 1) Quitamos la clase 'active' al elemento que esté marcado
        // 2) Y se la ponemos al elemento en el que se ha hecho click

        $("li.active").removeClass('active');

        $(this).addClass('active');
      });

      //desactivar enlace del menú de arriba (cerrar sesión)
      $("#dropdown").click(function(e){

        e.preventDefault();
      });

      $("#cerrarSesion").click(function(){
        sessionStorage.removeItem('tokenREADARKRIT');
        window.location.replace('./');
      });

    $scope.ajustesPerfil = function(){
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
            } else if(data.rol == 'profesor'){
              $location.path('/profesor/modificar');
              $scope.$apply();
            }
          }
        });
      };
}]);


angular.module('readArkrit')
  .controller('LibroCtrl', function ($scope) {
    //$scope.message = "Inicio.";

    $('#tablaEstanteria').DataTable();
    $('#tablaEstanteriasQueSigo').DataTable();
    $('#tablaRecomendacionesArkrit').DataTable();

  });