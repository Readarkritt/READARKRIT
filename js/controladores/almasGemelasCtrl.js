angular.module('readArkrit')
  .controller('almasGemelasCtrl', function ($scope) {

    $scope.almasGemelas = [];

    // FUNCIONES  

    $scope.listarAlmasGemelas = function(){

    	peticionAJAX('./php/almasGemelas.php', {

			opcion: 'almasGemelas',
			accion: 'listar',
			idUsuario: 64 /* ID_USUARIO */
		})
		.done(function( data, textStatus, jqXHR ){

			if( !data.error )
				$scope.almasGemelas = $.makeArray(data.almasGemelas);	
		});
    };


    // EVENTOS

    	// Listar
    $scope.listarAlmasGemelas();

	}); // fin controller