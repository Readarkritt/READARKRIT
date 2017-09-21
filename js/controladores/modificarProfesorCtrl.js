angular.module('readArkrit')
  .controller('modificarProfesorCtrl', function ($scope) {

  	$scope.profesor = {};

  	peticionAJAX('./php/profesor.php', {
  		opcion: 'profesor',
  		accion: 'recuperarConectado'
  	}, false)
  	.done(function(data, textStatus, jqXHR){
  		$scope.profesor = data.profesor;
  	});

  });