// ------------------ FUNCIONES ------------------------

function comprobarLogin() {

    var parametros = {};
    var respuesta  = {};

    parametros.opcion     = 'login';
    parametros.accion     = 'comprobar';
    parametros.correo     = $("#correo").val();
    parametros.contrasena = $("#contrasena").val();

    if( parametros.correo == '' || parametros.contrasena == '' ){

        swal("Login incorrecto", "Campos incompletos.", "error");

    } else {

        respuesta = peticionAJAX('php/login.php', parametros);

        respuesta.done(function( data, textStatus, jqXHR ) {

            if( data.error )
                swal("Usuario incorrecto", data.descripcionError, "error");
            else{
                
                sessionStorage.setItem('tokenREADARKRIT', data.token);
                parametros.token = data.token;

                $('#insert_form').html('<form action="/READARKRIT/estadisticas" name="logged" method="post" style="display:none"><input type="text" name="token" value="'+data.token+'"/></form>');
                document.forms['logged'].submit();
            }
        });
    }
}


function contrasenaOlvidada(){

    var parametros = {};
    var respuesta  = {};

    parametros.opcion        = 'login';
    parametros.accion        = 'contrasenaOlvidada';
    parametros.correo        = $("#correoRecuperar").val();
    parametros.nombreUsuario = $("#nombreUsuario").val();

    if( parametros.correo == '' || parametros.nombreUsuario == '' ){

        swal("Recuperación de contraseña", "Campos incompletos.", "error");

    } else {

        respuesta = peticionAJAX('php/login.php', parametros);

        respuesta.done(function( data, textStatus, jqXHR ) {

            if( data.error )
                swal("Datos incorrectos", data.descripcionError, "error");
            else
                swal("Recuperación de contraseña", data.descripcion, "success");
            
        });
    }
}

// ---------------------- EVENTOS ----------------------------

$(function() {
    
    $('#formularioContrasenaOlvidada').hide();
});

$(document).keypress(function(e) {

    // Si la tecla pulsada es el Enter, efectuar el login

    if(e.which == 13) {
        
        comprobarLogin();
    }
});


$('button').click(function(e) {

    e.preventDefault();
});


$('.texto_small > a').click(function(e) {

    // Hace que pulsando los enlaces 'se te ha olvidado la contrseña' ó 'volver' oculte/muestre
    // los respectivos formularios

    $('#formularioLogin').toggle();
    $('#formularioContrasenaOlvidada').toggle();
});