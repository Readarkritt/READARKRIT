angular.module('readArkrit')
  .controller('estadisticasCtrl', function ($scope) {

  	$scope.miembrosComunidad   = '-';
	$scope.totalLibros 		   = '-';
    $scope.numResenas 		   = '-';
    $scope.numGrupos 		   = '-';
    $scope.numSeguidores 	   = '-';
    $scope.numPersonasSeguidas = '-';

  	peticionAJAX('./php/estadisticas.php', {

		opcion: 'estadisticas',
		accion: 'listar'
	})
	.done(function( data, textStatus, jqXHR ){

		$scope.$apply(function () {

			if( data.error ){
				$scope.miembrosComunidad   = 'No se ha podido obtener la información.';
			    $scope.totalLibros 		   = 'No se ha podido obtener la información.';
			    $scope.numResenas 		   = 'No se ha podido obtener la información.';
			    $scope.numGrupos 		   = 'No se ha podido obtener la información.';
			    $scope.numSeguidores 	   = 'No se ha podido obtener la información.';
			    $scope.numPersonasSeguidas = 'No se ha podido obtener la información.';
			}else{
				$scope.miembrosComunidad   = data.miembrosComunidad;
				$scope.totalLibros 		   = data.totalLibros;
			    $scope.numResenas 		   = data.numResenas;
			    $scope.numGrupos 		   = data.numGrupos;
			    $scope.numSeguidores 	   = data.numSeguidores;
			    $scope.numPersonasSeguidas = data.numPersonasSeguidas;
			}

		});
	});

  });