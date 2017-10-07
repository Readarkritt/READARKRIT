angular.module('readArkrit')
  .controller('altaLibroCtrl', function ($scope) {

  	$scope.libroAnadido = {};
  	$scope.libro = {};

  	$('#ano').mask("9999");

  	$scope.altaLibro = function(){
  		var errores = '';
  		var datosLibro = {};
  		var datosLibroAnadido = {};

      	$('#errores').addClass('hidden');
      	$('#errores span').html('');
      	$('#exito').addClass('hidden');
      	$('#exito span').html('');

      	//Preparar datos para la petición
      	datosLibro.idLibro 			= '';
      	datosLibro.portada 			= $scope.libro.portada;
      	datosLibro.titulo 			= $scope.libro.titulo;
      	datosLibro.tituloOriginal 	= $scope.libro.tituloOriginal;
      	datosLibro.autor 			= $scope.libro.autor;
      	datosLibro.ano 				= $scope.libro.ano;
      	datosLibro.idAnadidoPor 	= "";
      	datosLibro.idTitulacion 	= $scope.libro.idTitulacion;

      	datosLibroAnadido.idLibroAnadido 		= '';
      	datosLibroAnadido.idLibro 				= '';
      	datosLibroAnadido.idPais				= $scope.libroAnadido.idPais;
      	datosLibroAnadido.idCategoria 			= $scope.libroAnadido.idCategoria;
      	datosLibroAnadido.posicionRanking 		= $scope.libroAnadido.posicionRanking;
      	datosLibroAnadido.mediaNumUsuarios 		= '';
      	datosLibroAnadido.nivelEspecializacion 	= $scope.libroAnadido.nivelEspecializacion;

      	//Validar campos
      	errores = validarCamposLibro(datosLibro);
      	errores += validarCamposLibroAnadido(datosLibroAnadido);

      	if(errores=''){
      		 peticionAJAX('./php/libro.php', {
		        opcion:'libro',
		        accion:'anadir',
		        libro:datosLibro,
		        libroAnadido:datosLibroAnadido
		    }, false).
		    done(function(data,textStatus,jqXHR){
			   	if(data.error){
			   		swal("Datos incorrectos", data.descripcionError, "error");
			   	} else {
			   		swal("Libro añadido", "success");
			   	}
      		});
      	}

      	if(errores != ''){
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