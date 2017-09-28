angular.module('readArkrit')
  .controller('menuAppCtrl', function ($scope) {


      // MENÚ
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

});


angular.module('readArkrit')
  .controller('LibroCtrl', function ($scope) {
    //$scope.message = "Inicio.";

    $('#tablaEstanteria').DataTable();
    $('#tablaEstanteriasQueSigo').DataTable();
    $('#tablaRecomendacionesArkrit').DataTable();

  });