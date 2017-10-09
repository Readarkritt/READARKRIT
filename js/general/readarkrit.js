function validarPass(contrasena, contrasenaRepetida){
	errores = '';
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

function validarCorreo(correo){
	errores = '';
	if( correo === undefined || correo == '' ){
		errores += '<li>El correo electrónico es incorrecto.</li>';
	}
	else if( correo.length > 50 ){
		errores += '<li>El correo electrónico no puede superar los 50 caracteres.</li>';
	}
	else if( !emailCorrecto(correo) ){
		errores += '<li>El correo electrónico no sigue un formato conocido.</li>';
	}
	else if( existeRegistro('correo', correo, 'usuario') ){
		errores += '<li>El correo electrónico se encuentra registrado.</li>';
	}
	return errores;
}

function validarCamposUsuario(campos){

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
	if( campos.correo === undefined || campos.correo == '' )
		errores += '<li>El correo electrónico no se ha completado.</li>';
		else if( campos.correo.length > 50 )
			errores += '<li>El correo electrónico no puede superar los 50 caracteres.</li>';
		else if( !emailCorrecto(campos.correo) )
			errores += '<li>El correo electrónico no sigue un formato conocido.</li>';
		else if( existeRegistro('correo', campos.correo, 'usuario') )
			errores += '<li>El correo electrónico se encuentra registrado.</li>';
	if( campos.nombreUsuario === undefined || campos.nombreUsuario == '' )
		errores += '<li>El nombre de usuario no se ha completado.</li>';
		else if( campos.nombreUsuario.length > 20 )
			errores += '<li>El nombre de usuario no puede exceder de los 20 caracteres.</li>';
		else if( !campos.nombreUsuario.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			errores += '<li>El nombre de usuario sólo puede contener letras.</li>';
		else if( existeRegistro('nombre_usuario', campos.nombreUsuario, 'usuario') )
			errores += '<li>El nombre de usuario se encuentra ya en uso.</li>';
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

	return errores;
}

function validarCamposAlumno(campos){

	var errores = '';

	if( campos.numExpediente === undefined || campos.numExpediente == '' )
		errores += '<li>El número de expediente no se ha completado.</li>';
		else if( campos.numExpediente.length > 8 )
			errores += '<li>El número de expediente no puede superar los 8 números.</li>';
		else if( !campos.numExpediente.match( /^[0-9]+$/) )
			errores += '<li>El número de expediente está compuesto únicamente de números.</li>';
		else if( existeRegistro('num_expediente', campos.numExpediente, 'alumno') )
			errores += '<li>El número de expediente se encuentra en uso.</li>';
	if( parseInt(campos.idTitulacion) <= 0 )
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

function validarCamposLibro(campos){
	var errores = '';

	if(existeRegistro('titulo', campos.titulo, 'libro') && existeRegistro('titulo_original',campos.tituloOriginal, 'libro')){
			errores += '<li>Ya existe un libro registrado con el título y el título original indicado.</li>';
	} else{
		if(campos.titulo== undefined || campos.titulo == '')
			errores += '<li>El título no se ha proporcionado.</li>';
			else if(campos.titulo.length>50)
				errores += '<li>El título no puede superar los 50 caracteres.</li>';
			else if( !campos.titulo.match(/^[a-zA-Z0-9áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
				errores += '<li>El título sólo puede contener letras o dígitos.</li>';

		if(campos.tituloOriginal === undefined || campos.tituloOriginal == '')
			errores += '<li>El título original no se ha proporcionado.</li>';
			else if(campos.tituloOriginal.length>50)
				errores += '<li>El título original no puede superar los 50 caracteres.</li>';
			else if( !campos.tituloOriginal.match(/^[a-zA-Z0-9áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
				errores += '<li>El título original sólo puede contener letras o dígitos.</li>';

		if(campos.autor=== undefined || campos.autor == '')
			errores += '<li>El autor no se ha proporcionado.</li>';
			else if(campos.autor.length>50)
				errores += '<li>El autor no puede superar los 50 caracteres.</li>';
			else if( !campos.autor.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
				errores += '<li>El autor sólo puede contener letras o dígitos.</li>';

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

	if( campos.nivelEspecializacion== undefined || campos.nivelEspecializacion == '' )
		errores += '<li>Elija un nivel de especialización válido.</li>';

	if( campos.posicionRanking === undefined)
		errores += '<li>Indique una posición en el ranking válida.</li>';
		else{
			var rango = rangoRanking()+1;
			if( campos.posicionRanking > rangoRanking()+1 || campos.posicionRanking < 0)
			errores += '<li>La posición del ranking debe estar entre 0 y '+rango+'.</li>';
		}	

	//FALTA POSICIONRANKING

	return errores;
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

function obtenerPaises(){	
	var paises = false;
	peticionAJAX('./php/pais.php', {
			opcion: 'pais',
			accion: 'listar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){
				paises = data.paises;
			}
		});
	return paises;
}

function obtenerTitulaciones(){	
	var titulaciones = false;
	peticionAJAX('./php/titulacion.php', {
			opcion: 'titulacion',
			accion: 'listar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){
				titulaciones = data.titulaciones;
			}
		});
	return titulaciones;

}

function obtenerCategoriasLibro(){	
	var categorias = false;
	peticionAJAX('./php/categoriaLibro.php', {
			opcion: 'categoriaLibro',
			accion: 'listar'
		}, false)
		.done(function( data, textStatus, jqXHR ){

			if( !data.error ){
				categorias = data.categorias;
			}
		});
	return categorias;

}

function obtenerNivelesEspecializacion(){
	var niveles = {};
	niveles[0] = {
		'nombre' : 'Básico',
		'id_nivel' : 'basico'
	};
	niveles[1] ={
		'nombre' : 'Especialidad',
		'id_nivel' : 'especialidad'		
	};

	return niveles;
}