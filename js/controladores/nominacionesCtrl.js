angular.module('readArkrit')
  .controller('nominacionesCtrl', function ($scope, DTOptionsBuilder) { 	
  	$scope.libro = {};
  	$scope.libroAnadir = {};
  	$scope.libroPropuesto = {};
  	$scope.librosPropuesto = [];

	$scope.titulaciones = obtenerValores('titulacion');
	$scope.paises 		= obtenerValores('pais');
	$scope.categorias 	= obtenerValores('categoriaLibro');
	
	$scope.nivelEspecializacion = obtenerNivelesEspecializacion();

    $scope.marcarTabla = function(e){
    	e.preventDefault();    	
        $(".navTabsResponsive>li").removeClass("active");
    	$(this).addClass("active");
    };

    $scope.altaPropuesta = function(){

  		var errores = '';

      	$('#errores').addClass('hidden');
      	$('#errores span').html('');
      	$('#exito').addClass('hidden');
      	$('#exito span').html('');

      	//Preparar datos para la petición
      	var libro = new Libro('','',$scope.libro.titulo,$scope.libro.tituloOriginal,$scope.libro.autor,$scope.libro.ano,$scope.libro.anadidoPor,$scope.libro.idTitulacion,$scope.libro.fBaja,);
      	var libroPropuesto = new LibroPropuesto('','',$scope.libroPropuesto.propuestoPara,$scope.libroPropuesto.motivo);      	

      	var portada = $('#inputPortada').prop('files')[0];

      	//Validar campos
      	errores = validarCamposLibro(libro);
     	errores += validarCamposLibroPropuesto(libroPropuesto);
     	if(portada == undefined)
     		errores += '<li>Se debe añadir una portada.</li>'

      	if(errores==''){

	     	var formData = new FormData();
	      	formData.append('portada', portada);
			formData.append('opcion', 'libroPropuesto');
			formData.append('accion', 'alta');

			formData.append('titulo', 			libro.titulo);
			formData.append('tituloOriginal', 	libro.tituloOriginal);
			formData.append('autor', 			libro.autor);
			formData.append('ano', 				libro.ano);
			formData.append('idTitulacion', 	libro.idTitulacion);

			formData.append('propuestoPara', 	libroPropuesto.propuestoPara);
			formData.append('motivo', 			libroPropuesto.motivo);
			formData.append('token', sessionStorage.getItem('tokenREADARKRIT'));

      		 $.ajax({
      		 	url:'./php/libroPropuesto.php',                    
	            type: 'POST',
		        data:formData,
	            contentType: false,
	            processData: false,
	            async: false,     
	            success: function(data){
	            	data = $.parseJSON(data);
	            	console.log(data);
				   	if(data.error){
				   		errores = data.descripcionError;
				   	} else {
						$scope.libroPropuesto 	= {};
					  	$scope.libro 			= {};
					  	$('#inputPortada').val('');

				   		swal("Propuesta añadida", "");
				   		data.libro.portada = './img/portadasLibros/'+data.libro.portada;

				   		console.log(data.libro);
						$scope.librosPropuestos.push(data.libro);

						var table = $('#tablaPropuestos').DataTable();
						table.draw( false );

						table = $('#tablaAñadir').DataTable();
						table.draw( false );

				   	}
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

  	};

  	$scope.listarNominaciones = function(){
		peticionAJAX('./php/libroPropuesto.php', {
			opcion: 'libroPropuesto',
			accion: 'listar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){
				console.log(data);
				var rutaDefinitiva = './img/portadasLibros/';
				$scope.librosPropuestos = $.makeArray(data.librosPropuestos);

				$('#tablaPropuestos').removeClass('hidden');
				$('#tablaAñadir').removeClass('hidden');

				$.each( $scope.librosPropuestos, function( index, value ){
				    $scope.librosPropuestos[index].portada = rutaDefinitiva + $scope.librosPropuestos[index].portada;
				});

				console.log($scope.librosPropuestos);
			}
		});

  	};
	 $scope.eliminarLibroPropuesto = function(idLibroPropuesto, indexScope){

    	peticionAJAX('./php/libroPropuesto.php', {

			opcion : 'libroPropuesto',
			accion : 'eliminar',
			idLibroPropuesto: idLibroPropuesto
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( data.error )
				swal("Eliminar Propuesta", "Error en la transacción.", "error");
			else{

				swal("Propuesta Eliminada", "Propuesta eliminada con éxito.", "success");

				data.libro.portada = './img/portadasLibros/'+data.libro.portada;
				$scope.librosPropuestos[indexScope] = data.libro;
				
				var table = $('#tablaPropuestos').DataTable();
				table.draw( false );

				table = $('#tablaAñadir').DataTable();
				table.draw( false );
			}
		});
	}

	$scope.reactivarLibroPropuesto = function(idLibroPropuesto, indexScope){
		peticionAJAX('./php/libroPropuesto.php', {

			opcion : 'libroPropuesto',
			accion : 'reactivar',
			idLibroPropuesto: idLibroPropuesto
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( data.error )
				swal("Reactivar Propuesta", "Error en la transacción.", "error");
			else{

				swal("Propuesta Reactivada", "Propuesta reactivada con éxito.", "success");

				data.libro.portada = './img/portadasLibros/'+data.libro.portada;
				$scope.librosPropuestos[indexScope] = data.libro;
				
				var table = $('#tablaPropuestos').DataTable();
				table.draw( false );

				table = $('#tablaAñadir').DataTable();
				table.draw( false );

			}
		});

	}

	$scope.mostrarListadoAnadir = function(){
		$('#formAñadir').removeClass('active');
		$('#añadirColeccion').addClass('active');
	}

	$scope.cargarAnadir = function(idLibro){
		$scope.libroAnadir.idLibro = idLibro;
	}

	$scope.anadirAColeccion = function(){

		var errores  = validarCamposLibroAnadido($scope.libroAnadir);

		if( errores != ''){

			var html = 	'<b> Se han encontrado errores al rellenar el formulario: </b>' +
						'<ul>' +
				   			errores +		
				   		'</ul>';

			$('#erroresColeccion span').html(html);
			$('#erroresColeccion').removeClass('hidden');

		} else {

			cerrarAlerta($('#erroresColeccion'));

			peticionAJAX('./php/libroPropuesto.php', {

			//	opcion: 'libroPropuesto',
						opcion: '',
				accion: 'anadirColeccion',
				libroAnadido: $scope.libroAnadir

			})
			.done(function( data, textStatus, jqXHR ){

				if( data.error ){

					swal("Datos incorrectos", data.descripcionError, "error");
				} else {
					//BUSCAR EN EL ARRAY Y BORRAR POR ID LIBRO	
					var index = buscarValorEnArrObj($scope.librosPropuestos, 'id_libro', $scope.libroAnadir.idLibro);		
					$scope.librosPropuestos.splice(index,1);
					$scope.libroAnadir = {};

					console.log($scope.librosPropuestos);
					
					var table = $('#tablaPropuestos').DataTable();
					table.draw(false);

					table = $('#tablaAñadir').DataTable();
					table.draw(false);
					
					swal("Añadir a Colección", "Libro añadido correctamente", "success");

				    $('#formAñadir').removeClass('active');
				    $('#añadirColeccion').addClass('active');
				    $('#botonAñadir').click();
				}

			});
		}

	}
  	//EVENTOS

    cargarJS("./js/clases/Libro.js");
    cargarJS("./js/clases/LibroPropuesto.js");

    $scope.listarNominaciones();

  });