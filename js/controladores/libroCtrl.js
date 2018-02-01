angular.module('readArkrit')
  .controller('libroCtrl', function ($scope, DTOptionsBuilder) {

    $scope.librosAnadidos 	= [];
	$scope.libroAnadido 	= {};
  	$scope.libro 			= {};
  	$scope.modLibro 		= {};
  	$scope.modLibroAnadido 	= {};
  	$scope.indexModificando;

	$scope.titulaciones = obtenerValores('titulacion');
	$scope.paises 		= obtenerValores('pais');
	$scope.categorias 	= obtenerValores('categoriaLibro');
	
	$scope.nivelEspecializacion = obtenerNivelesEspecializacion();

  	$('#ano').mask("9999",{placeholder:"0000"});
  	$('#modAno').mask("9999",{placeholder:"0000"});

    // FUNCIONES
    $scope.listarLibrosAnadidos = function(){

    	peticionAJAX('./php/libroAnadido.php', {

			opcion: 'libroAnadido',
			accion: 'listarTodos'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

				var rutaDefinitiva = './img/portadasLibros/';

				$('#tablaListado').removeClass('hidden');
				$scope.librosAnadidos = [];
				$scope.librosAnadidos = $.makeArray(data.librosAnadidos);

				$.each( $scope.librosAnadidos, function( index, value ){
				    $scope.librosAnadidos[index].portada = rutaDefinitiva + $scope.librosAnadidos[index].portada;
				});

				console.log($scope.librosAnadidos);
			}
		});
    };


    $scope.eliminarLibroAnadido = function(idLibroAnadido, indexScope){

    	peticionAJAX('./php/libroAnadido.php', {

			opcion : 'libroAnadido',
			accion : 'eliminar',
			idLibroAnadido: idLibroAnadido
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( data.error )
				swal("Error", data.descripcionError, "error");
			else{

				swal("Libro Eliminado", "Libro eliminado con éxito.", "success");	

				data.libro.portada = './img/portadasLibros/'+data.libro.portada;
				$scope.librosAnadidos[indexScope] = data.libro;
				console.log($scope.librosAnadidos);			
				
			}
		});

		/*var table = $('#tablaListado').DataTable();
		table.draw( false );*/
    };

    $scope.cargarModificarLibro = function(idLibroAnadido, indexScope){
		$scope.indexModificando = indexScope;
		$scope.modLibro = {};

    	$('#menu-adminLibro li').removeClass('active');
    	$scope.librosAnadidos[indexScope]
    	console.log($scope.librosAnadidos[indexScope]);
    	$scope.modLibro = $scope.librosAnadidos[indexScope];
		$('#modPortadaImg').attr("src", $scope.librosAnadidos[indexScope].portada);

    }

    $scope.modificarLibro = function(){
    	var errores = '';
  		var datosLibro = {};
  		var datosLibroAnadido = {};
  		var portada = false;

      	$('#erroresModificar').addClass('hidden');
      	$('#erroresModificar span').html('');
      	$('#exitoModificar').addClass('hidden');
      	$('#exitoModificar span').html('');

      //Preparar datos
      	datosLibro.idLibro 			= $scope.modLibro.id_libro;
      	datosLibro.titulo 			= $scope.modLibro.titulo;
      	datosLibro.tituloOriginal 	= $scope.modLibro.titulo_original;
      	datosLibro.autor 			= $scope.modLibro.autor;
      	datosLibro.ano 				= $scope.modLibro.ano;
      	datosLibro.idTitulacion 	= $scope.modLibro.id_titulacion;

      	datosLibroAnadido.idLibroAnadido 		= $scope.modLibro.id_libro_anadido;
      	datosLibroAnadido.idPais				= $scope.modLibro.id_pais;
      	datosLibroAnadido.idCategoria 			= $scope.modLibro.id_categoria;
      	datosLibroAnadido.posicionRanking 		= $scope.modLibro.posicion_ranking;
      	datosLibroAnadido.nivelEspecializacion 	= $scope.modLibro.nivel_especializacion;
      	datosLibroAnadido.resena 				= $scope.modLibro.resena;

      	if($('#modInputPortada').get(0).files.length > 0){
      		portada = $('#modInputPortada').prop('files')[0];
      		datosLibro.portadaAnadida = true;
      	} else{
      		datosLibro.portadaAnadida = false;
      	}

      	console.log('DATOS PETICION MODIFICAR: ')      
      	console.log(datosLibro);
      	console.log(datosLibroAnadido);

	     //VALIDAR
	     errores = validarCamposLibro(datosLibro, false);
	     errores += validarCamposLibroAnadido(datosLibroAnadido);


	     //PETICIÓN
	     if(errores==''){

	     	//Preparar datos
	     	var formData = new FormData();
	      	formData.append('portada', portada);
			formData.append('opcion', 'libroAnadido');
			formData.append('accion', 'modificar');

			formData.append('idLibro', 			datosLibro.idLibro);
			formData.append('titulo', 			datosLibro.titulo);
			formData.append('tituloOriginal', 	datosLibro.tituloOriginal);
			formData.append('autor', 			datosLibro.autor);
			formData.append('ano', 				datosLibro.ano);
			formData.append('idTitulacion', 	datosLibro.idTitulacion);
			formData.append('portadaAñadida', 	datosLibro.portadaAnadida);

			formData.append('idLibroAnadido', 		datosLibroAnadido.idLibroAnadido);
			formData.append('idPais', 				datosLibroAnadido.idPais);
			formData.append('idCategoria', 			datosLibroAnadido.idCategoria);
			formData.append('posicionRanking', 		datosLibroAnadido.posicionRanking);
			formData.append('nivelEspecializacion',	datosLibroAnadido.nivelEspecializacion);
			formData.append('resena',				datosLibroAnadido.resena);
			
			formData.append('token', sessionStorage.getItem('tokenREADARKRIT'));

			console.log(formData);
      		$.ajax({
      		 	url:'./php/libroAnadido.php',   
      		 	dataType: 'json',                 
	            type: 'POST',
		        data:formData,
	            contentType: false,
	            processData: false,   
	            beforeSend: function(){
				    // Muestra el spinner de loading
				    activarLoading();
				},
	            success: function(data){
				   	if(data.error){
				   		errores = 'Error: datos manipulados.';
				   	} else {				   		
				   		swal("Libro modificado", "");
				   		data.libro.portada = './img/portadasLibros/'+data.libro.portada;
				   		$scope.librosAnadidos.splice($scope.indexModificando,1);
				   		$scope.librosAnadidos.push(data.libro);
				   		$scope.$apply();

						$scope.dtOptions = DTOptionsBuilder.fromFnPromise( $scope.librosAnadidos ).withOption('stateSave', true).withDataProp('data');

					    $scope.reloadData = reloadData;
					    $scope.dtInstance = {};

					    function reloadData() {
					        var resetPaging = false;
					        $scope.dtInstance.reloadData(callback, resetPaging);
					    }
				   	}
	            },
		        complete:function(){
		   			// Oculta el spinner de loading
		    		desactivarLoading();
		   		}
	     	});
		   
      	}

      	if(errores != ''){
	        var html =  '<b> Se han encontrado errores al rellenar el formulario: </b>' +
	              '<ul>' +
	                  errores
	              '</ul>';
	        $('#erroresModificar').removeClass('hidden');
	        $('#erroresModificar span').html(html);
       } else{
        	var html =  '<b> Cambios introducidos con éxito. </b>';
        	$('#exitoModificar').removeClass('hidden');
        	$('#exitoModificar span').html(html);           	
       }

    }


    $scope.procesarLibrosExcel = function(){

    	var ficheroExcel 	  = $('#inputExcel').prop('files')[0];
    	var ficheroComprimido = $('#inputComprimido').prop('files')[0];
    	var html              = '';

    	$('#erroresProcesamientoLibros').removeClass('alert-danger')
    									.removeClass('alert-success');

    	if( ficheroExcel === undefined || ficheroComprimido === undefined ){

    		$('#erroresProcesamientoLibros span').html('No has subido todos los ficheros.');

	    	$('#erroresProcesamientoLibros').addClass('alert-danger')
											.removeClass('hidden');

    	} else {

    		var nombreFicheroExcel = ficheroExcel.name;
	    	var extensionExcel     = nombreFicheroExcel.split('.').pop();

	    	var nombreFicheroComprimido = ficheroComprimido.name;
	    	var extensionComprimido     = nombreFicheroComprimido.split('.').pop();

	    	var formData = new FormData();                  
		    formData.append('ficheroExcel', ficheroExcel);
		    formData.append('ficheroComprimido', ficheroComprimido);
		    formData.append('opcion', 'libro');
		    formData.append('accion', 'procesarLibrosExcel');
		    formData.append('token', sessionStorage.getItem('tokenREADARKRIT'));

	    	if( (extensionExcel == 'xls' || extensionExcel == 'xlsx') && extensionComprimido == 'zip' ){

				$.ajax({
	                url: './php/libro.php',
	                dataType: 'json',
	                cache: false,
	                contentType: false,
	                processData: false,
	                data: formData,                         
	                type: 'POST',
	                beforeSend:function(){
					    // Muestra el spinner de loading
					    activarLoading();
					},
	                success:function(data){

	                    if(!data.error){

	                    	$scope.listarLibrosAnadidos();

	                    	$('#erroresProcesamientoLibros span').html('Libros creados correctamente.');

	                    	$('#erroresProcesamientoLibros').addClass('alert-success');
	                    } else {

	                    	$('#erroresProcesamientoLibros span').html(data.descripcionError);

	                    	$('#erroresProcesamientoLibros').addClass('alert-danger');
	                    }

	                    $('#erroresProcesamientoLibros').removeClass('hidden');
	                },
			        complete:function(){
			   			// Oculta el spinner de loading
			    		desactivarLoading();
			   		}
	     		});

	    	} else {

	    		$('#erroresProcesamientoLibros span').html('Ficheros con formato incorrecto.');

	    		$('#erroresProcesamientoLibros').addClass('alert-danger')
												.removeClass('hidden');
	    	}
    	}
    };

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
      	datosLibro.titulo 			= $scope.libro.titulo;
      	datosLibro.tituloOriginal 	= $scope.libro.tituloOriginal;
      	datosLibro.autor 			= $scope.libro.autor;
      	datosLibro.ano 				= $scope.libro.ano;
      	datosLibro.anadidoPor 		= "";
      	datosLibro.idTitulacion 	= $scope.libro.idTitulacion;

      	datosLibroAnadido.idLibroAnadido 		= '';
      	datosLibroAnadido.idLibro 				= '';
      	datosLibroAnadido.idPais				= $scope.libroAnadido.idPais;
      	datosLibroAnadido.idCategoria 			= $scope.libroAnadido.idCategoria;
      	datosLibroAnadido.posicionRanking 		= $scope.libroAnadido.posicionRanking;
      	datosLibroAnadido.mediaNumUsuarios 		= '';
      	datosLibroAnadido.nivelEspecializacion 	= $scope.libroAnadido.nivelEspecializacion;
      	datosLibroAnadido.resena 				= $scope.libroAnadido.resena;

      	var portada = $('#inputPortada').prop('files')[0];

      	//Validar campos
      	errores = validarCamposLibro(datosLibro);
     	errores += validarCamposLibroAnadido(datosLibroAnadido);
     	if(portada == undefined)
     		errores += '<li>Se debe añadir una portada.</li>'

      	if(errores==''){

	     	var formData = new FormData();
	      	formData.append('portada', portada);
			formData.append('opcion', 'libro');
			formData.append('accion', 'alta');

			formData.append('titulo', 			datosLibro.titulo);
			formData.append('tituloOriginal', 	datosLibro.tituloOriginal);
			formData.append('autor', 			datosLibro.autor);
			formData.append('ano', 				datosLibro.ano);
			formData.append('idTitulacion', 	datosLibro.idTitulacion);

			formData.append('idPais', 				datosLibroAnadido.idPais);
			formData.append('idCategoria', 			datosLibroAnadido.idCategoria);
			formData.append('posicionRanking', 		datosLibroAnadido.posicionRanking);
			formData.append('nivelEspecializacion',	datosLibroAnadido.nivelEspecializacion);
			formData.append('resena',				datosLibroAnadido.resena);

			formData.append('token', sessionStorage.getItem('tokenREADARKRIT'));

      		 $.ajax({
      		 	url:'./php/libro.php', 
      		 	dataType: 'json',                   
	            type: 'POST',
		        data:formData,
	            contentType: false,
	            processData: false,
	            async: false,     
	            beforeSend:function(){
				    // Muestra el spinner de loading
				    activarLoading();
				},
	            success: function(data){
				   	if(data.error){
				   		errores = data.descripcionError;
				   	} else {
				   		swal("Libro añadido", "");
				   		data.libro.portada = './img/portadasLibros/'+data.libro.portada;
				   		$scope.librosAnadidos.push(data.libro);

				   		console.log($scope.librosAnadidos);

				   		$scope.libro = {};
				   		$scope.libroAnadido = {};

						/*$scope.$apply();

						console.log($scope.librosAnadidos);

						$scope.libroAnadido 	= {};
					  	$scope.libro 			= {};
					  	$('#inputPortada').val('');

						$scope.dtOptions = DTOptionsBuilder.fromFnPromise( $scope.librosAnadidos ).withOption('stateSave', true).withDataProp('data');

					    $scope.reloadData = reloadData;
					    $scope.dtInstance = {};

					    function reloadData() {
					        var resetPaging = false;
					        $scope.dtInstance.reloadData(callback, resetPaging);
					    }*/

				   	}
	            },
		        complete:function(){
		   			// Oculta el spinner de loading
		    		desactivarLoading();
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

  	$scope.reactivarLibroAnadido = function(idLibroAnadido, indexScope){
		peticionAJAX('./php/libroAnadido.php', {

			opcion : 'libroAnadido',
			accion : 'reactivar',
			idLibroAnadido: idLibroAnadido
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( data.error )
				swal("Error", data.descripcionError, "error");
			else{

				swal("Libro Reactivado", "Libro reactivado con éxito.", "success");
				
				data.libro.portada = './img/portadasLibros/'+data.libro.portada;
				$scope.librosAnadidos[indexScope] = data.libro;
				console.log($scope.librosAnadidos);
				var table = $('#tablaListado').DataTable();
				table.draw( false );
			}
		});
  	}

    // EVENTOS

    cargarJS("./js/clases/Libro.js");
    cargarJS("./js/clases/LibroAnadido.js");

    	// Listar
    $scope.listarLibrosAnadidos();

    	// Añadir

	}); // fin controller