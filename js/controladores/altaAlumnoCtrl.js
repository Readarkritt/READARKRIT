// ------------------ FUNCIONES ------------------------

function altaAlumno(){

	// El alumno hereda de la clase Usuario

	var datosLeidos = formTOobject();

	// Separar datos del usuario y del alumno
		
	var usuario = new Usuario(	'', // id usuario
								datosLeidos.nombre, 
								datosLeidos.primerApellido,
								datosLeidos.segundoApellido,
								datosLeidos.fNacimiento,
								datosLeidos.correo,
								datosLeidos.nombreUsuario,
								datosLeidos.contrasena,
								0,
								null);

	var alumno  = new Alumno(   '',	// id alumno
								'',	// id usuario
								datosLeidos.numExpediente,
								datosLeidos.idTitulacion,
								datosLeidos.curso);

	var erroresUsuario = validarCamposUsuario(usuario);
	var erroresAlumno  = validarCamposAlumno(alumno);

	console.log('Usuario: ' + usuario);
	console.log('Alumno: ' + alumno);

	if( erroresUsuario != '' || erroresAlumno != '' ){

		var html = 	'<b> Se han encontrado errores al rellenar el formulario: </b>' +
					'<ul>' +
			   			erroresUsuario +
			   			erroresAlumno +
					'</ul>';

		$('#erroresAltaAlumno').removeClass('hidden');
		$('#erroresAltaAlumno span').html(html);

	} else {

		cerrarAlerta($('#erroresAltaAlumno'));

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

// ------------------ EVENTOS ------------------------

$(function() {
    	
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
	if( existeRegistro('nombre_usuario', nombreUsuario, 'usuario') ){

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