angular.module('readArkrit')
  .controller('resenaAdminCtrl', function ($scope, DTOptionsBuilder) {

  	$scope.resenas = [];

  	$scope.listarResenas = function(){

    	peticionAJAX('./php/resena.php', {
			opcion: 'resena',
			accion: 'listar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){
				$scope.resenas = $.makeArray(data.resenas);
				var rutaDefinitiva = './img/portadasLibros/';
				console.log($scope.resenas[0]);
			}
		});
    }


	 $scope.eliminarResena = function(idResena, indexScope){

    	peticionAJAX('./php/resena.php', {

			opcion : 'resena',
			accion : 'eliminar',
			idResena: idResena,
			administraccion: true
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( data.error )
				swal("Eliminar Comentario", "Error en la transacción.", "error");
			else{

				swal("Comentario Eliminado", "Comentario eliminado con éxito.", "success");

				$scope.resenas.splice(indexScope, 1);
				$scope.$apply();

				$scope.dtOptions = DTOptionsBuilder.fromFnPromise( $scope.librosPropuestos ).withOption('stateSave', true).withDataProp('data');

			    $scope.reloadData = reloadData;
			    $scope.dtInstance = {};

			    function reloadData() {
			        var resetPaging = false;
			        $scope.dtInstance.reloadData(callback, resetPaging);
			    }
			}
		});
	}

    //EVENTOS
    $scope.listarResenas();
  });