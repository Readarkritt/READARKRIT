// ------------------ FUNCIONES ------------------------

function validarCampos(campos){

	var html = '';
	var errores = '';

	if( campos.nombre == '' )
		errores += '<li>El nombre no se ha completado.</li>';
		else if( campos.nombre.length > 40 )
			errores += '<li>El nombre no puede exceder de los 40 caracteres.</li>';
		else if( !campos.nombre.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			errores += '<li>El nombre sólo puede contener letras.</li>';
	if( campos.primerApellido == '' )
		errores += '<li>El primer apellido no se ha completado.</li>';
		else if( campos.primerApellido.length > 30 )
			errores += '<li>El primer apellido no puede exceder de los 30 caracteres.</li>';
		else if( !campos.primerApellido.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			errores += '<li>El primer apellido sólo puede contener letras.</li>';
	if( campos.segundoApellido == '' )
		errores += '<li>El segundo apellido no se ha completado.</li>';
		else if( campos.segundoApellido.length > 30 )
			errores += '<li>El segundo apellido no puede exceder de los 30 caracteres.</li>';
		else if( !campos.segundoApellido.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			errores += '<li>El segundo apellido sólo puede contener letras.</li>';
	if( campos.fNacimiento.length != 10 || campos.fNacimiento.length == 0 )
		errores += '<li>La fecha de nacimiento tiene que tener exactamente 10 caracteres (incluidas las /).</li>';
		else if( !campos.fNacimiento.match(/^([0-9]{2}\/[0-9]{2}\/[0-9]{4})$/) )
			errores += '<li>La fecha de nacimiento tiene que seguir el patrón "dd/mm/aaaa", rellenado sólo por números.</li>';
		else if( !fechaPermitida(campos.fNacimiento) )
			errores += '<li>La fecha no existe.</li>';
	if( campos.correo == '' )
		errores += '<li>El correo electrónico no se ha completado.</li>';
		else if( campos.correo.length > 50 )
			errores += '<li>El correo electrónico no puede superar los 50 caracteres.</li>';
		else if( !emailCorrecto(campos.correo) )
			errores += '<li>El correo electrónico no sigue un formato conocido.</li>';
		else if( existeRegistro('usuario', 'correo', campos.correo) )
			errores += '<li>El correo electrónico se encuentra registrado.</li>';
	if( campos.nombreUsuario == '' )
		errores += '<li>El nombre de usuario no se ha completado.</li>';
		else if( campos.nombreUsuario.length > 20 )
			errores += '<li>El nombre de usuario no puede exceder de los 20 caracteres.</li>';
		else if( !campos.nombreUsuario.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/) )
			errores += '<li>El nombre de usuario sólo puede contener letras.</li>';
		else if( existeRegistro('usuario', 'nombre_usuario', campos.nombreUsuario) )
			errores += '<li>El nombre de usuario se encuentra ya en uso.</li>';
	if( campos.contrasena == '' || campos.contrasenaRepetida == '' )
		errores += '<li>Las contraseñas no se han completado.</li>';
		else if( campos.contrasena.length > 20 || campos.contrasenaRepetida.length > 20 )
			errores += '<li>Las contraseñas no pueden tener más de 20 caracteres.</li>';
		else if( campos.contrasena.length != campos.contrasenaRepetida.length )
			errores += '<li>Las contraseñas tienen diferentes longitudes.</li>';
		else if( campos.contrasena != campos.contrasenaRepetida )
			errores += '<li>Las contraseñas no coinciden.</li>';
		else if( !contrasenaSegura(campos.contrasena) || !contrasenaSegura(campos.contrasenaRepetida) )
			errores += '<li>Las contraseñas no son seguras.</li>';
	if( campos.numExpediente == '' )
		errores += '<li>El número de expediente no se ha completado.</li>';
		else if( campos.numExpediente.length > 8 )
			errores += '<li>El número de expediente no puede superar los 8 números.</li>';
		else if( !campos.numExpediente.match( /^[0-9]+$/) )
			errores += '<li>El número de expediente está compuesto únicamente de números.</li>';
		else if( existeRegistro('alumno', 'num_expediente', campos.numExpediente) )
			errores += '<li>El número de expediente se encuentra en uso.</li>';
	if( parseInt(campos.idTitulacion) <= 0 )
		errores += '<li>Elija una titulación válida.</li>';
	if( parseInt(campos.curso) <= 0 )
		errores += '<li>Elija un curso válido.</li>';


	if( errores != '' ){

		html = 	'<b> Se han encontrado errores al rellenar el formulario: </b>' +
				'<ul>' +
			   		errores +
				'</ul>';
	}


	return html;
}

function altaAlumno(){

	// El alumno hereda de la clase Usuario

	var datosLeidos = formTOobject();
	var erroresForm = validarCampos(datosLeidos);

	if( erroresForm != '' ){

		$('#erroresAltaAlumno').removeClass('hidden')
		$('#erroresAltaAlumno span').html(erroresForm);

	} else {

		cerrarAlerta($('#erroresAltaAlumno'));


		// Separar datos del usuario y del alumno
		
		var usuario = new Usuario(	'', // id usuario
									datosLeidos.nombre, 
									datosLeidos.primerApellido,
									datosLeidos.segundoApellido,
									datosLeidos.fNacimiento,
									datosLeidos.correo,
									datosLeidos.nombreUsuario,
									datosLeidos.contrasena,
									0);

		var alumno  = new Alumno(   '',	// id alumno
									'',	// id usuario
									datosLeidos.numExpediente,
									datosLeidos.idTitulacion,
									datosLeidos.curso);


		console.log('Usuario: ' + usuario);
		console.log('Alumno: ' + alumno);

		/*usuario.idUsuario          = '';
		usuario.nombre 		       = datosLeidos.nombre;
		usuario.primerApellido     = datosLeidos.primerApellido;
		usuario.segundoApellido    = datosLeidos.segundoApellido;
		usuario.fNacimiento	       = datosLeidos.fNacimiento;
		usuario.correo 		       = datosLeidos.correo;
		usuario.nombreUsuario 	   = datosLeidos.nombreUsuario;
		usuario.contrasena         = datosLeidos.contrasena;
		usuario.bloqueado          = 0;

		alumno.idAlumno      = '';
		alumno.idUsuario 	 = '';
		alumno.numExpediente = datosLeidos.numExpediente;
		alumno.idTitulacion  = datosLeidos.idTitulacion;
		alumno.curso		 = datosLeidos.curso;*/


		peticionAJAX('../../php/alumno.php', {

			opcion: 'alumno',
			accion: 'alta',
			usuario: usuario,
			alumno:  alumno
		})
		.done(function( data, textStatus, jqXHR ){

			if( data.error ){

				swal("Datos incorrectos", data.descripcionError, "error");
			} else {

				usuario.idUsuario = data.idUsuario;
				alumno.idAlumno   = data.idAlumno;
				alumno.idUsuario  = data.idUsuario;

				swal("Alta Alumno", "Alumno creado correctamente, ¡bienvenido!", "success")
				.then((value) => {
					window.location.href='../menuApp/menuApp.html';
				});
			}

		});

	}
}

function emailCorrecto(email) {

    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    return re.test(email);
}

function contrasenaSegura(contrasena) {

	var re = (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/);

	/*
		(/^
		(?=.*\d)                // 1 dígito
		(?=.*[a-z])             // 1 minúscula
		(?=.*[A-Z])             // 1 mayúscula
		[a-zA-Z0-9]{8,}         // debe tener como mínimo 8 caracteres de longitud
		$/)
	*/

	return re.test(contrasena);
}

function fechaPermitida(fecha){

	// comprueba si una fecha con formato dd/mm/aaaa existe en el calendario

	var currVal = fecha;

    if(currVal == '')
        return false;
    
    var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
    var dtArray = currVal.match(rxDatePattern); // is format OK?
    
    if (dtArray == null) 
        return false;
    
    //Checks for dd/mm/yyyy format.
    dtDay   = dtArray[1];
    dtMonth = dtArray[3];
    dtYear  = dtArray[5];        
    
    if (dtDay < 1 || dtDay> 31)
        return false;
    else if (dtMonth < 1 || dtMonth > 12)
        return false;
    else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) 
        return false;
    else if (dtMonth == 2) 
    {
        var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
        if (dtDay> 29 || (dtDay ==29 && !isleap)) 
                return false;
    }

    return true;
}

function cargarTitulacion(elemento){

	var parametros = {};
	var peticion   = {};
	var html       = '<option value=0 data-duracion=0>Selecciona...</option>';

	parametros.opcion = 'titulacion';
    parametros.accion = 'listar';


	peticion = peticionAJAX('../../php/titulacion.php', parametros);

    peticion.done(function( data, textStatus, jqXHR ) {

        if( data.titulaciones ){

			$.each( data.titulaciones, function( index, element ) {

				html += '<option value=' + element.id_titulacion + ' data-duracion=' + element.duracion + '>' + element.nombre + '</option>';
			});
        }

        elemento.html( html );

    });
}

function cargarCurso(elementoTitulacion, elementoCurso){

	var html     = '<option value=0 data-duracion=0>Selecciona...</option>';
	var duracion = elementoTitulacion.find(':selected').data('duracion');

	for( var i=1; i<=duracion; i++ ){

		html += '<option value=' + i + '>' + i + '</option>';
	}

	elementoCurso.html( html );
}

function cargarFormulario(){
	$('#formulario').load('./formAlumno.html');
}

// ------------------ EVENTOS ------------------------

$(function() {
    cargarFormulario();
   	cargarTitulacion( $('#idTitulacion') );
   	cargarCurso( $('#idTitulacion') , $('#curso') );
});


$('#fNacimiento').mask("99/99/9999",{placeholder:"dd/mm/aaaa"});

$('#correo').focus(function(){

				$(this).removeClass('text-danger text-success');
			})
			.blur(function(){

			    if( emailCorrecto(this.value) ) // hasta 50 caracteres
			    	$(this).addClass('text-success');
			    else
			    	$(this).addClass('text-danger');
			});


$('#comprobarDisponibilidadNombreUsuario').click(function(e){

	e.preventDefault();


	var nombreUsuario = $('#nombreUsuario').val();

	$('#nombreUsuario').removeClass('text-danger text-success');

	// petición síncrona
	if( existeRegistro('usuario', 'nombre_usuario', nombreUsuario) ){

		$('#nombreUsuario').addClass('text-danger');
	} else {

		$('#nombreUsuario').addClass('text-success');

		crearAvatar(nombreUsuario);
		$('#nombreAvatar').html(nombreUsuario);
	}
});


$('#contrasena').focus(function(){

					$(this).removeClass('text-danger text-success');

					$('#infoContrasena').removeClass('hidden');
				})
				.blur(function(){

				    if( contrasenaSegura(this.value) )
				    	$(this).addClass('text-success');
				    else
				    	$(this).addClass('text-danger');
				});


$('#contrasenaRepetida').focus(function(){

							$(this).removeClass('text-danger text-success');
						})
						.blur(function(){

						    if( contrasenaSegura(this.value) )
						    	$(this).addClass('text-success');
						    else
						    	$(this).addClass('text-danger');
						});


$('#numExpediente').mask("?99999999",{placeholder:" "}); // hasta 8 números


$('#idTitulacion').change(function(){

	cargarCurso( $('#idTitulacion') , $('#curso') );
});