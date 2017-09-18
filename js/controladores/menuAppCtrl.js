angular.module('readArkrit')
  .controller('menuAppCtrl', function ($scope) {


      // MENÚ
      cargarMenu($scope);
      marcarMenu();

      $(".sidebar-wrapper > .nav > li").click(function(e){

        // 1) Quitamos la clase 'active' al elemento que esté marcado
        // 2) Y se la ponemos al elemento en el que se ha hecho click

        $("li.active").removeClass('active');

        $(this).addClass('active');
      });

      $("#cerrarSesion").click(function(){
        sessionStorage.removeItem('tokenREADARKRIT');
        window.location.replace('./');
      });

});


angular.module('readArkrit')
  .controller('LibroCtrl', function ($scope) {
    //$scope.message = "Inicio.";

    $('#tablaEstanteria').DataTable();
    $('#tablaEstanteriasQueSigo').DataTable();
    $('#tablaRecomendacionesArkrit').DataTable();

  });

angular.module('readArkrit')
  .controller('UsuarioCtrl', function ($scope) {
    //$scope.message = "Perfil.";
    alert('usuario');
  });