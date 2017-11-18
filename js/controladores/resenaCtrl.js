angular.module('readArkrit')
  .controller('resenaCtrl', function ($scope, DTOptionsBuilder) {

    $scope.librosAnadidos 		= [];
    $scope.resenas		 		= [];
    $scope.resena 				= {};
    $scope.misResenas			= [];
    $scope.libroSeleccionado;

	$scope.titulaciones = obtenerValores('titulacion');
	$scope.paises 		= obtenerValores('pais');
	$scope.categorias 	= obtenerValores('categoriaLibro');

    // FUNCIONES
    $scope.listarLibrosAnadidos = function(){

    	peticionAJAX('./php/libroAnadido.php', {

			opcion: 'libroAnadido',
			accion: 'listar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				var rutaDefinitiva = './img/portadasLibros/';

				$('#tablaLibros').removeClass('hidden');
				$scope.librosAnadidos = $.makeArray(data.librosAnadidos);

				$.each( $scope.librosAnadidos, function( index, value ){
				    $scope.librosAnadidos[index].portada = rutaDefinitiva + $scope.librosAnadidos[index].portada;
				});
			}
		});
    };
    
	$scope.listarMisResenas = function(){
		$('#misComentariosBody').addClass('hidden');
		$('#misComentariosError').addClass('hidden');

    	peticionAJAX('./php/resena.php', {

			opcion: 'resena',
			accion: 'listarResenasConectado'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){
				if(data.resenas != false){
					$scope.misResenas = $.makeArray(data.resenas);
				}
				var rutaDefinitiva = './img/portadasLibros/';
				console.log($scope.misResenas);

				if($scope.misResenas.length == 0){
					$('#misComentariosError').removeClass('hidden');
				}else{	
					$('#misComentariosBody').removeClass('hidden');		
					$.each( $scope.misResenas, function( index, value ){
					    $scope.misResenas[index].portada = rutaDefinitiva + $scope.misResenas[index].portada;
					});
				}
			}
		});
    }

    $scope.cambiarValorSlider = function(valor){
    	$('#valor_slider').html(valor);
    }


    $scope.eliminarResena = function(idResena, index){
    	peticionAJAX('./php/resena.php', {

			opcion: 'resena',
			accion: 'eliminar',
			idResena: idResena
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){
				swal("Comentario eliminado");
				if($scope.libroSeleccionado == $scope.misResenas[index].id_libro){
					$("#listadoComentarios #resena"+idResena).remove();
				}
				$scope.misResenas.splice(index,1);
				
				if($scope.misResenas.length == 0){
					$('#misComentariosError').removeClass('hidden');
					$('#misComentariosBody').addClass('hidden');
				}

			} else{
				swal("Ha habido un problema", data.descripcionError, "error");
			}
		});
    }

    $scope.mostrarLibro = function(idLibro){
    	$scope.resenas = {};
    	$scope.resena.nota = 5;
    	$('#valor_slider span').html(5);
    	$('#slider').val(5);
    	$('#erroresAlta').addClass('hidden');
    	$scope.resena.comentario = '';

		peticionAJAX('./php/resena.php', {
			opcion: 'resena',
			accion: 'listarResenasLibro',
			idLibro: idLibro
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){
				$scope.resenas = $.makeArray(data.resenas);
				console.log($scope.resenas);
				$scope.libroSeleccionado = idLibro;

    			$('#listadoLibros').addClass('hidden');
    			$('#comentariosLibro').removeClass('hidden');

				/*var table = $('#listadoComentarios').draw;
				table.draw( true );*/
    			pintarAvataresResenas();
			}		
		});

				
    }

    function pintarAvataresResenas(){    
    alert(123);	
		$.each($scope.resenas, function(index, value){
			crearAvatarElemento(value.nombre, 'avatarResena'+value.id_resena);
		});
    }

    $scope.altaResena = function(){
    	$('#erroresAlta').addClass('hidden');
      	$('#erroresAlta span').html('');
		console.log($scope.resena);

		$scope.resena.idLibro = $scope.libroSeleccionado;

    	var errores  = validarCamposResena($scope.resena);

		if( errores != '' ){	

			var html = 	'<b> Se han encontrado errores: </b>' +
						'<ul>' +
				   			errores +
						'</ul>';

			$('#erroresAlta span').html(html);
    		$('#erroresAlta').removeClass('hidden');

		} else {

			cerrarAlerta($('#erroresAlta'));
			var resena = new Resena(
					'',
					$scope.resena.nota,
					$scope.resena.comentario,
					$scope.resena.idLibro,
					'',
					''
				);
			
			peticionAJAX('./php/resena.php', {

				opcion: 'resena',
				accion: 'alta',
				resena: resena
			})
			.done(function( data, textStatus, jqXHR ){

				if( data.error ){

					swal("Datos incorrectos", data.descripcionError, "error");
				} else {

					resena = data.resena;

					console.log($scope.misResenas);
					resena.id_resena = resena.idResena;
					resena.portada = './img/portadasLibros/'+resena.portada;
					resena.fecha_alta = resena.fAlta;
					resena.id_libro = resena.idLibro;

					$scope.misResenas.push(resena);
					$scope.resenas.push(resena);
					$scope.$apply();
					pintarAvataresResenas();



					swal("Comentario insertado",'' ,"success");
				}

			});
		}
    }

    $scope.mostrarListaLibros = function(){
		$('#comentariosLibro').addClass('hidden');
		$('#listadoLibros').removeClass('hidden');
    }

     // EVENTOS
    cargarJS("./js/clases/Resena.js");

    	// Listar
    $scope.listarLibrosAnadidos();
    $scope.listarMisResenas();


	}); // fin controller
