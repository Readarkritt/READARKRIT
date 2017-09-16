// ---------------------- FUNCIONES --------------------------

function crearUsuario(){

	var usuario = new Usuario();

	// Recoger datos
	usuario = formTOobject();

	peticionAJAX('', {

		opcion: 'usuario',
		accion: 'insertar',
		usuario: usuario
	})
	.done(function( data, textStatus, jqXHR ){

		alert("bien");
	});
}

// ---------------------- EVENTOS ----------------------------

$('#username').change(function(e){

	$('#username_avatar').text(e.target.value);

	crearAvatar(e.target.value);
});