angular.module('readArkrit')
  .controller('nominarLibroCtrl', function ($scope, DTOptionsBuilder) {
  	$scope.libroPropuestoAlta = {};
  	$scope.libroPropuestoBaja = {};
  	$scope.librosPropuestosAlta = [];
  	$scope.librosPropuestosBaja = [];


  	$('#anoAlta').mask("9999",{placeholder:"0000"});
  	$('#anoBaja').mask("9999",{placeholder:"0000"});


    $scope.marcarTabla = function(e){
    	e.preventDefault();    	
        $(".navTabsResponsive>li").removeClass("active");
    	$(this).addClass("active");
    };

    $scope.listarNominacionesAlta = function(){
		peticionAJAX('./php/libroPropuesto.php', {
			opcion: 'libroPropuesto',
			accion: 'listar',
			propuestoPara:'añadir'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){
				console.log(data);
				var rutaDefinitiva = './img/portadasLibros/';
				$scope.librosPropuestosAlta = $.makeArray(data.librosPropuestos);

				$('#tablaAlta').removeClass('hidden');

				console.log($scope.librosPropuestosAlta);
			}
		});

  	}

    $scope.listarNominacionesBaja = function(){
		peticionAJAX('./php/libroPropuesto.php', {
			opcion: 'libroPropuesto',
			accion: 'listar',
			propuestoPara:'eliminar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){
				console.log(data);
				var rutaDefinitiva = './img/portadasLibros/';
				$scope.librosPropuestosBaja = $.makeArray(data.librosPropuestos);

				$('#tablaBaja').removeClass('hidden');

				console.log($scope.librosPropuestosBaja);
			}
		});

  	}

	function nominar(nominacion){
		console.log(nominacion);

		peticionAJAX('./php/libroPropuesto.php', {
			opcion: 'libroPropuesto',
			accion: 'nominar',
			nominacion: nominacion
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( data.error ){	
				swal("Error", data.descripcionError, "error");
			}else{
				swal("Nominación enviada", "Tu propuesta será tomada en cuenta.", "success");

			}
		});

	}

	$scope.nominarAlta = function(){		
      	$('#erroresAlta').addClass('hidden');

		nominacion = $scope.libroPropuestoAlta;
		nominacion.propuestoPara = "añadir";

		errores = validarCamposNominacion(nominacion);

		if(errores != ''){
			var html =  '<b> Se han encontrado errores al rellenar el formulario: </b>' +
	              '<ul>' +
	                  errores
	              '</ul>';
	        $('#erroresAlta').removeClass('hidden');
	        $('#erroresAlta span').html(html);
		} else
			nominar(nominacion);
	}

	$scope.nominarBaja = function(){
      	$('#erroresBaja').addClass('hidden');
		nominacion = $scope.libroPropuestoBaja;
		nominacion.propuestoPara = "eliminar";


		errores = validarCamposNominacion(nominacion);
		
		if(errores != ''){
			var html =  '<b> Se han encontrado errores al rellenar el formulario: </b>' +
	              '<ul>' +
	                  errores
	              '</ul>';
	        $('#erroresBaja').removeClass('hidden');
	        $('#erroresBaja span').html(html);
		} else
			nominar(nominacion);
	}


	$scope.votar = function(idLibroPropuesto,index, tipo){
		var error = '';
		peticionAJAX('./php/libroPropuesto.php', {

				opcion: 'libroPropuesto',
				accion: 'votar',
				idLibroPropuesto: idLibroPropuesto

			}).done(function( data, textStatus, jqXHR ){

				if( data.error ){

					swal("Error", data.descripcionError, "error");
				} else {
					if(tipo == 'alta'){
						$scope.librosPropuestosAlta[index]['num_votos']++;
					} else{
						$scope.librosPropuestosBaja[index]['num_votos']++;						
					}

					$scope.$apply();

					$scope.dtOptions = DTOptionsBuilder.fromFnPromise( $scope.librosPropuestos ).withOption('stateSave', true).withDataProp('data');

				    $scope.reloadData = reloadData;
				    $scope.dtInstance = {};

				    function reloadData() {
				        var resetPaging = false;
				        $scope.dtInstance.reloadData(callback, resetPaging);
				    }

					swal("Votación realizada", "", "success");
				}
			});
	};


  	//EVENTOS
  	$scope.listarNominacionesAlta();
  	$scope.listarNominacionesBaja();

});