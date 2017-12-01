angular.module('readArkrit')
  .controller('coleccionArkritCtrl', function ($scope) {

    $scope.coleccionArkrit = [];

    // FUNCIONES  

    $scope.listarColeccionArkrit = function(){

    	peticionAJAX('./php/libroAnadido.php', {

			opcion: 'libroAnadido',
			accion: 'listar'
		})
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){

                var rutaDefinitiva = './img/portadasLibros/';
                
                $('#tablaColeccionArkrit').removeClass('hidden');

				$scope.coleccionArkrit = $.makeArray(data.librosAnadidos);

                $.each( $scope.coleccionArkrit, function( index, value ){
                    $scope.coleccionArkrit[index].portada = rutaDefinitiva + $scope.coleccionArkrit[index].portada;
                });
            }
		});
    };

    $scope.verResenaModal = function(idLibro){

        var index = buscarValorEnArrObj($scope.coleccionArkrit, 'id_libro', idLibro);
        var libro = $scope.coleccionArkrit[index];

        if( libro.resena == null )
            libro.resena = 'No tiene reseña.';

        var html = '<div id="verResenaModal" class="modal fade" role="dialog">' +
                      '<div class="modal-dialog">' +
                        '<div class="modal-content">' +

                          '<div class="modal-header">' +
                            '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                            '<h4 class="modal-title"><b>' + libro.titulo + '</b></h4>' +
                          '</div>' +

                          '<div class="modal-body">' +
                            '<div class="container col-md-12">' +
                                libro.resena +
                            '</div>' +
                          '</div>' +

                          '<div class="modal-footer">' +
                            '&nbsp;' +
                          '</div>' +

                        '</div>' +
                      '</div>' +
                    '</div>';

        $('#verResenaModal').remove();    // borra modals anteriores

        $('body').append(html);

        $('#verResenaModal').modal();
    };    

    // EVENTOS
    $('head').append('<link href="./css/tabla-coleccion-arkrit.css" rel="stylesheet"/>');   // añade el css que necesita la vista

    $('#tablaColeccionArkrit').on('click','.cursorInfo', function (e) { // hace que cuando se de a la clase 'cursorInfo' se muestre el modal, esté en modo responsive o no

        var idLibro = $(e.currentTarget).attr('data-idLibro');

        $scope.verResenaModal(idLibro);
    });

    	// Listar
    $scope.listarColeccionArkrit();

	}); // fin controller