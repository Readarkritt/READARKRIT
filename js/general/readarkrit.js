
function generarRecomendacionesArkrit(){

	var recomendaciones = [];
	
	peticionAJAX('./php/estanteria.php', {

		opcion: 'estanteria',
		accion: 'generarRecomendacionesArkrit'
	}, false)
	.done(function( data, textStatus, jqXHR ){

		recomendaciones = $.makeArray( data.recomendaciones );
	});

	return recomendaciones;
}

function validarPass(contrasena, contrasenaRepetida){
	
	var errores = '';

	if( (contrasena === undefined || contrasena == '') || (contrasenaRepetida === undefined || contrasenaRepetida == '') )
		errores += '<li>Las contraseñas no se han completado.</li>';
	else if( contrasena.length > 20 || contrasenaRepetida.length > 20 )
		errores += '<li>Las contraseñas no pueden tener más de 20 caracteres.</li>';
	else if( contrasena.length != contrasenaRepetida.length )
		errores += '<li>Las contraseñas tienen diferentes longitudes.</li>';
	else if( contrasena != contrasenaRepetida )
		errores += '<li>Las contraseñas no coinciden.</li>';
	else if( !contrasenaSegura(contrasena) || !contrasenaSegura(contrasenaRepetida) )
		errores += '<li>Las contraseñas no son seguras.</li>';

	return errores;
}

function validarCorreo(correo, $comprobarExistente = true){
	var errores = '';
	if( correo === undefined || correo == '' ){
		errores += '<li>El correo electrónico es incorrecto.</li>';
	}
	else if( correo.length > 50 ){
		errores += '<li>El correo electrónico no puede superar los 50 caracteres.</li>';
	}
	else if( !emailCorrecto(correo) ){
		errores += '<li>El correo electrónico no sigue un formato conocido.</li>';
	}
	else if( $comprobarExistente && existeRegistro('correo', correo, 'usuario') ){
		errores += '<li>El correo electrónico se encuentra registrado.</li>';
	}
	return errores;
}

function validarCamposResena(campos){
	var errores = '' ;
	
	peticionAJAX('./php/resena.php', {
			opcion: 'resena',
			accion: 'comentarioHechoConectado',
			idLibro: campos.idLibro
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error && data.existe ){
				errores += '<li>No es posible comentar el mismo libro una segunda vez.</li>';
			}
		});

	if(errores == ''){
		if( campos.nota === undefined || campos.nota == '' )
			errores += '<li>Se debe dar una nota al libro.</li>';
			else if( campos.nota < 0 || campos.nota > 10 )
				errores += '<li>La nota debe estar entre 0 y 10.</li>';

		if( campos.comentario === undefined || campos.comentario == '' )
			errores += '<li>No se ha escrito nada en el comentario.</li>';
			else if( campos.comentario.length > 5000 )
				errores += '<li>El comentario no puede exceder de los 5000 caracteres.</li>';
	}
	return errores;

}

function validarCamposUsuario(campos, comprobarExistente = true, comprobarContrasenas = true, comprobarBloqueado = false){

	var errores = '';

	if( campos.nombre === undefined || campos.nombre == '' )
		errores += '<li>El nombre no se ha completado.</li>';
		else if( campos.nombre.length > 40 )
			errores += '<li>El nombre no puede exceder de los 40 caracteres.</li>';
		else if( !campos.nombre.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			errores += '<li>El nombre sólo puede contener letras.</li>';
	if( campos.primerApellido === undefined || campos.primerApellido == '' )
		errores += '<li>El primer apellido no se ha completado.</li>';
		else if( campos.primerApellido.length > 30 )
			errores += '<li>El primer apellido no puede exceder de los 30 caracteres.</li>';
		else if( !campos.primerApellido.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			errores += '<li>El primer apellido sólo puede contener letras.</li>';
	if( campos.segundoApellido === undefined || campos.segundoApellido == '' )
		errores += '<li>El segundo apellido no se ha completado.</li>';
		else if( campos.segundoApellido.length > 30 )
			errores += '<li>El segundo apellido no puede exceder de los 30 caracteres.</li>';
		else if( !campos.segundoApellido.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			errores += '<li>El segundo apellido sólo puede contener letras.</li>';
	if( campos.fNacimiento === undefined || campos.fNacimiento == '' )
			errores += '<li>La fecha de nacimiento no se ha completado.</li>';
		else if( campos.fNacimiento.length != 10 )
			errores += '<li>La fecha de nacimiento tiene que tener exactamente 10 caracteres (incluidas las /).</li>';
		else if( !campos.fNacimiento.match(/^([0-9]{2}\/[0-9]{2}\/[0-9]{4})$/) )
			errores += '<li>La fecha de nacimiento tiene que seguir el patrón "dd/mm/aaaa", rellenado sólo por números.</li>';
		else if( !fechaPermitida(campos.fNacimiento) )
			errores += '<li>La fecha no existe.</li>';
		else if( !fechaMenorQueActual(campos.fNacimiento))
			errores += '<li>La fecha de nacimiento no puede ser una fecha futura.</li>'; 
	if( campos.correo === undefined || campos.correo == '' )
		errores += '<li>El correo electrónico no se ha completado.</li>';
		else if( campos.correo.length > 50 )
			errores += '<li>El correo electrónico no puede superar los 50 caracteres.</li>';
		else if( !emailCorrecto(campos.correo) )
			errores += '<li>El correo electrónico no sigue un formato conocido.</li>';
		else if( comprobarExistente && existeRegistro('correo', campos.correo, 'usuario') )
			errores += '<li>El correo electrónico se encuentra registrado.</li>';
	if( campos.nombreUsuario === undefined || campos.nombreUsuario == '' )
		errores += '<li>El nombre de usuario no se ha completado.</li>';
		else if( campos.nombreUsuario.length > 20 )
			errores += '<li>El nombre de usuario no puede exceder de los 20 caracteres.</li>';
		else if( !campos.nombreUsuario.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			errores += '<li>El nombre de usuario sólo puede contener letras.</li>';
		else if( comprobarExistente && existeRegistro('nombre_usuario', campos.nombreUsuario, 'usuario') )
			errores += '<li>El nombre de usuario se encuentra ya en uso.</li>';

	if(comprobarContrasenas){
		if( (campos.contrasena === undefined || campos.contrasena == '') || (campos.contrasenaRepetida === undefined || campos.contrasenaRepetida == '') )
			errores += '<li>Las contraseñas no se han completado.</li>';
			else if( campos.contrasena.length > 20 || campos.contrasenaRepetida.length > 20 )
				errores += '<li>Las contraseñas no pueden tener más de 20 caracteres.</li>';
			else if( campos.contrasena.length != campos.contrasenaRepetida.length )
				errores += '<li>Las contraseñas tienen diferentes longitudes.</li>';
			else if( campos.contrasena != campos.contrasenaRepetida )
				errores += '<li>Las contraseñas no coinciden.</li>';
			else if( !contrasenaSegura(campos.contrasena) || !contrasenaSegura(campos.contrasenaRepetida) )
				errores += '<li>Las contraseñas no son seguras.</li>';
	}

	if(comprobarBloqueado){
		if( typeof campos.bloqueado != 'boolean' )
			errores += '<li>Dato inválido en el campo de bloqueado.</li>';
	}

	return errores;
}

function validarCamposAlumno(campos, comprobarExistente=true){

	var errores = '';

	if( campos.numExpediente === undefined || campos.numExpediente == '' )
		errores += '<li>El número de expediente no se ha completado.</li>';
		else if( campos.numExpediente.length > 8 )
			errores += '<li>El número de expediente no puede superar los 8 números.</li>';
		else if( !campos.numExpediente.match( /^[0-9]+$/) )
			errores += '<li>El número de expediente está compuesto únicamente de números.</li>';
		else if( comprobarExistente && existeRegistro('num_expediente', campos.numExpediente, 'alumno') )
			errores += '<li>El número de expediente se encuentra en uso.</li>';
	if( campos.idTitulacion === undefined || campos.idTitulacion == '' || parseInt(campos.idTitulacion) <= 0 )
		errores += '<li>Elija una titulación válida.</li>';
	if( parseInt(campos.curso) <= 0 )
		errores += '<li>Elija un curso válido.</li>';

	return errores;
}

function validarCamposProfesor(campos){

	var errores = '';

	if( typeof campos.evitarNotificacion != 'boolean' )
		errores += '<li>Dato inválido en el campo de evitar notificaciones.</li>';
	if( typeof campos.esAdmin != 'boolean' )
		errores += '<li>Dato inválido en el campo de ser profesor administrador.</li>';

	return errores;
}

function validarCamposLibro(campos, comprobarExistente=true){
	var errores = '';

	if(campos.titulo== undefined || campos.titulo == '')
			errores += '<li>El título no se ha proporcionado.</li>';
			else if(campos.titulo.length>100)
				errores += '<li>El título no puede superar los 100 caracteres.</li>';
			else if( !campos.titulo.match(/^[a-zA-Z0-9áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_.,:;&\s]+$/) )
				errores += '<li>El título sólo puede contener letras, dígitos, signos de puntuación o &.</li>';

		if(campos.tituloOriginal === undefined || campos.tituloOriginal == '')
			errores += '<li>El título original no se ha proporcionado.</li>';
			else if(campos.tituloOriginal.length>100)
				errores += '<li>El título original no puede superar los 100 caracteres.</li>';
			else if( !campos.tituloOriginal.match(/^[a-zA-Z0-9áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_.,:;&\s]+$/) )
				errores += '<li>El título original sólo puede contener letras, dígitos, signos de puntuación o &.</li>';

	if(errores=='' && comprobarExistente && existeRegistro('titulo', campos.titulo, 'libro') && existeRegistro('titulo_original',campos.tituloOriginal, 'libro')){
			errores += '<li>Ya existe un libro registrado con el título y el título original indicado.</li>';
	} else{
		
		if(campos.autor=== undefined || campos.autor == '')
			errores += '<li>El autor no se ha proporcionado.</li>';
			else if(campos.autor.length>50)
				errores += '<li>El autor no puede superar los 50 caracteres.</li>';
			else if( !campos.autor.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_,.:;\s]+$/) )
				errores += '<li>El autor sólo puede contener letras, dígitos o signos de puntuación.</li>';

		if( campos.ano === undefined || campos.ano == '' )
				errores += '<li>El año no se ha completado.</li>';
			else if(campos.ano<1900 || campos.ano>(new Date).getFullYear())
				errores += '<li>El año debe estar entre 1900 y la fecha actual.</li>';

		if(	campos.idTitulacion === undefined ||  parseInt(campos.idTitulacion) <= 0 )
			errores += '<li>Elija una titulación válida.</li>';
	}
	return errores;
}

function validarCamposLibroAnadido(campos){

	var errores = '';
	
	if( campos.idPais === undefined || parseInt(campos.idPais) <= 0 )
		errores += '<li>Elija un país válido.</li>';

	if( campos.idCategoria === undefined || parseInt(campos.idCategoria) <= 0 )
		errores += '<li>Elija una categoría válida.</li>';

	if( campos.nivelEspecializacion === undefined || campos.nivelEspecializacion == '' )
		errores += '<li>Elija un nivel de especialización válido.</li>';

	if( campos.posicionRanking === undefined)
		errores += '<li>Indique una posición en el ranking válida.</li>';
		else{
			var rango = rangoRanking()+1;
			if( campos.posicionRanking > rangoRanking()+1 || campos.posicionRanking < 0)
			errores += '<li>La posición del ranking debe estar entre 0 y '+rango+'.</li>';
		}	
		
	if(campos.resena.length>5000)
		errores += '<li>La reseña no puede superar los 5.000 caracteres.</li>';

	return errores;
}

function validarCamposLibroPropuesto(campos){
	var errores = '';

	if(campos.propuestoPara === undefined || (campos.propuestoPara != 'añadir' && campos.propuestoPara != 'eliminar'))
		errores += '<li>El campo propuesto para debe tener un valor válido.</li>';

	if(campos.motivo === undefined || campos.motivo == '')
		errores += '<li>Se debe rellenar el campo motivo</li>';
	else if(campos.motivo.length > 2000)
		errores += '<li>El comentario no puede exceder los 2.000 caracteres.</li>';


	return errores;
}

function validarCamposClubLectura(campos){

	var errores = '';

	if( campos.nombre === undefined || campos.nombre == '' )
		errores += '<li>El nombre del club no se ha proporcionado.</li>';
		else if( campos.nombre.length > 20 )
			errores += '<li>El nombre del club no puede superar los 20 caracteres.</li>';
		else if( !campos.nombre.match(/^[a-zA-Z0-9áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			errores += '<li>El nombre del club sólo puede contener letras o dígitos.</li>';
		else if( existeRegistro('nombre',campos.nombre, 'clubLectura') )
			errores += '<li>El nombre del club ya existe.</li>';

	if( !(campos.idTitulacion === undefined) && parseInt(campos.idTitulacion) <= 0 )
		errores += '<li>Selecciona una titulación válida.</li>';

	if( !(campos.curso === undefined) && parseInt(campos.curso) <= 0 )
		errores += '<li>Selecciona un curso válido.</li>';

	return errores;
}


//Nominación que manda un usuario por correo
function validarCamposNominacion(campos){
	var errores = '';

	if(campos.titulo== undefined || campos.titulo == '')
		errores += '<li>El título no se ha proporcionado.</li>';
		else if(campos.titulo.length>100)
			errores += '<li>El título no puede superar los 100 caracteres.</li>';
		else if( !campos.titulo.match(/^[a-zA-Z0-9áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			errores += '<li>El título sólo puede contener letras o dígitos.</li>';

	if(campos.tituloOriginal === undefined || campos.tituloOriginal == '')
		errores += '<li>El título original no se ha proporcionado.</li>';
		else if(campos.tituloOriginal.length>100)
			errores += '<li>El título original no puede superar los 100 caracteres.</li>';
		else if( !campos.tituloOriginal.match(/^[a-zA-Z0-9áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			errores += '<li>El título original sólo puede contener letras o dígitos.</li>';

	if(campos.autor=== undefined || campos.autor == '')
		errores += '<li>El autor no se ha proporcionado.</li>';
		else if(campos.autor.length>50)
			errores += '<li>El autor no puede superar los 50 caracteres.</li>';
		else if( !campos.autor.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			errores += '<li>El autor sólo puede contener letras o dígitos.</li>';

	if(campos.motivo === undefined || campos.motivo == '')
		errores += '<li>Se debe rellenar el campo motivo</li>';
		else if(campos.motivo.length > 2000)
			errores += '<li>El comentario no puede exceder los 2.000 caracteres.</li>';

	return errores;

}


// Función que obtiene todos los valores de las tablas SQL que no tienen una clase en el modelo
function obtenerValores(tablaSQL){

	var phpUrl     = '';
	var parametros = {};
	var peticion   = {};
	var respuesta  = false;

	phpUrl = './php/' + tablaSQL + '.php'; // url del controlador

	parametros.opcion = tablaSQL;
    parametros.accion = 'listar';

	peticion = peticionAJAX(phpUrl, parametros, false);

    peticion.done(function( data, textStatus, jqXHR ) {
    		
        respuesta = data;
    });

	return respuesta;
}

function rangoRanking(){
	var rangoRanking = null;
	peticionAJAX('./php/libro.php', {
			opcion: 'libro',
			accion: 'obtenerPosicionesRanking'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){
				rangoRanking = parseInt(data.posicionesRanking);
			}
		});
	return rangoRanking;
}

function obtenerNivelesEspecializacion(){

	var niveles = {};

	niveles[0] = {
		'nombre' : 'Básico',
		'id_nivel' : 'Básico'
	};

	niveles[1] ={
		'nombre' : 'Especialidad',
		'id_nivel' : 'Especialidad'		
	};

	return niveles;
}