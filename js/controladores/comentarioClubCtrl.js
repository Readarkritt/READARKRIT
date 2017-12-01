angular.module('readArkrit')
  .controller('comentarioClubCtrl', function ($scope, DTOptionsBuilder) {

    $scope.clubsLectura       = [];
    $scope.idClubSeleccionado = 0;
    $scope.comentarios        = [];
    $scope.comentario         = '';

    // FUNCIONES
  $scope.listarClubsLectura = function() {

    peticionAJAX('./php/clubLectura.php', {

      opcion: 'clubLectura',
      accion: 'listarMisClubs'
    }, false)
    .done(function( data, textStatus, jqXHR ){

      if( !data.error )
        $scope.clubsLectura = $.makeArray(data.clubsLectura);
    });
  };

  $scope.cargarComentarios = function() {

    peticionAJAX('./php/comentarioClub.php', {

      opcion: 'comentarioClub',
      accion: 'listar',
      idClub: $scope.idClubSeleccionado
    }, false)
    .done(function( data, textStatus, jqXHR ){

      var html = '';

      $scope.comentarios = [];

      if( !data.error ) {

        $scope.comentarios = $.makeArray(data.comentarios);

        for (var i = 0; i < $scope.comentarios.length; i++){

          if( $scope.comentarios[i].nombre_usuario == 'Yo' )
            html += '<div class="comentario me">';
          else
            html += '<div class="comentario">';

          html += '<div class="usuario">' + $scope.comentarios[i].nombre_usuario + ': </div>';
          html += $scope.comentarios[i].comentario;
          html += '<div class="fecha">' + $scope.comentarios[i].fecha + '</div>';
          html += '</div>';
        }

        $('#conversacion').html(html)
                          .removeClass('hidden')
                          .animate({ scrollTop: $('#conversacion').prop('scrollHeight') }, 1000);

        $('#cajaComentario').removeClass('hidden');
      }
    });
  };


  $scope.anadirComentarioClub = function() {

    var erroresComentarioClub = '';

    if( $scope.comentario === undefined || $scope.comentario == '' )
      erroresComentarioClub += '<li>El comentario no se ha completado.</li>';
    else if( $scope.comentario.length > 1000 )
      erroresComentarioClub += '<li>El comentario no puede exceder de los 1000 caracteres.</li>';
    else if( !$scope.comentario.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
      erroresComentarioClub += '<li>El comentario sólo puede contener letras.</li>';

    if( erroresComentarioClub != '' ){

      var html =  '<b> Al añadir un comentario al club debes tener en cuenta: </b>' +
            '<ul>' +
                erroresComentarioClub +
            '</ul>';

      $('#erroresAnadirComentarioClub span').html(html);
      $('#erroresAnadirComentarioClub').removeClass('hidden');

    } else {

      cerrarAlerta($('#erroresAnadirComentarioClub'));

      var comentario = new ComentarioClub(  '', // id comentario club
                                            $scope.idClubSeleccionado,
                                            '', /* ID_USUARIO */
                                            null, // fecha
                                            $scope.comentario);

      console.log(comentario);

      peticionAJAX('./php/comentarioClub.php', {

        opcion: 'comentarioClub',
        accion: 'anadir',
        comentario: comentario
      })
      .done(function( data, textStatus, jqXHR ){

        if( !data.error ){

          var html = '<div class="comentario me">';
          html += '<div class="usuario">Yo: </div>';
          html += $scope.comentario;
          html += '<div class="fecha">' + fechaActual() + '</div>';
          html += '</div>';

          $('#conversacion').append(html)
                            .animate({ scrollTop: $('#conversacion').prop('scrollHeight') }, 1000);

          $('#comentario').val('');

        } else {

          $('#erroresAnadirComentarioClub span').html(data.descripcionError);
          $('#erroresAnadirComentarioClub').removeClass('hidden');
        }

      });
    }
  };



    // EVENTOS

    cargarJS("./js/clases/ComentarioClub.js");

      // Listar clubs de lectura
    $scope.listarClubsLectura();

      // Crear avatar del usuario conectado
    crearAvatar('Narciso'); /*NOMBRE DE USUARIO*/

  }); // fin controller