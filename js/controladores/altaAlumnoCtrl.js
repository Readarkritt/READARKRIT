// ------------------ FUNCIONES ------------------------

function altaAlumno(){

	// El alumno hereda de la clase Usuario
	var datosLeidos    = {};
	var datosUsuario   = {};
	var datosAlumno    = {};
	var erroresUsuario = '';
	var erroresAlumno  = '';
	var librosLeidos   = [];
	var erroresLibros  = '';

	datosLeidos = formTOobject();

	// Separar datos del usuario y del alumno

	datosUsuario.idUsuario 		 = '';
	datosUsuario.nombre    		 = datosLeidos.nombre;
	datosUsuario.primerApellido  = datosLeidos.primerApellido;
	datosUsuario.segundoApellido = datosLeidos.segundoApellido;
	datosUsuario.fNacimiento 	 = datosLeidos.fNacimiento;
	datosUsuario.correo 		 = datosLeidos.correo;
	datosUsuario.nombreUsuario   = datosLeidos.nombreUsuario;
	datosUsuario.contrasena		 = datosLeidos.contrasena;
	datosUsuario.contrasenaRepetida = datosLeidos.contrasenaRepetida;
	datosUsuario.bloqueado	     = 0;
	datosUsuario.fBaja			 = null;

	datosAlumno.idAlumno 	  = '';
	datosAlumno.idUsuario     = '';
	datosAlumno.numExpediente = datosLeidos.numExpediente;
	datosAlumno.idTitulacion  = datosLeidos.idTitulacion;
	datosAlumno.curso 		  = datosLeidos.curso;

	erroresUsuario = validarCamposUsuario(datosUsuario);
	erroresAlumno  = validarCamposAlumno(datosAlumno);

	console.log('Usuario: ' + datosUsuario);
	console.log('Alumno: ' + datosAlumno);

	// Comprobar que se han seleccionado libros

	$.each( $('#tablaListado').find('.selected'), function( index, element ) {
		librosLeidos.push( $(element).attr('data-idLibro') );			
	});

	console.log(librosLeidos);

	if( librosLeidos.length == 0 ){
		erroresLibros = '<li>Debes seleccionar al menos un libro.</li>';
	}


	if( erroresUsuario != '' || erroresAlumno != '' || erroresLibros != '' ){

		var html = 	'<b> Se han encontrado errores al rellenar el formulario: </b>' +
					'<ul>' +
			   			erroresUsuario +
			   			erroresAlumno +
			   			erroresLibros +
					'</ul>';

		$('#erroresAltaAlumno').removeClass('hidden');
		$('#erroresAltaAlumno span').html(html);

	} else {

		cerrarAlerta($('#erroresAltaAlumno'));

		var usuario = new Usuario(	datosUsuario.idUsuario,
									datosUsuario.nombre, 
									datosUsuario.primerApellido,
									datosUsuario.segundoApellido,
									datosUsuario.fNacimiento,
									datosUsuario.correo,
									datosUsuario.nombreUsuario,
									datosUsuario.contrasena,
									datosUsuario.bloqueado,
									datosUsuario.fBaja);

	    var alumno = new Alumno(datosAlumno.idAlumno,
								datosAlumno.idUsuario,
								datosAlumno.numExpediente,
								datosAlumno.idTitulacion,
								datosAlumno.curso);

		peticionAJAX('../../php/alumno.php', {

			opcion: 'alumno',
			accion: 'alta',
			usuario: usuario,
			alumno:  alumno,
			librosLeidos: librosLeidos
		})
		.done(function( data, textStatus, jqXHR ){

			if( data.error ){

				swal("Datos incorrectos", data.descripcionError, "error");
			} else {

				usuario.idUsuario = data.idUsuario;
				alumno.idAlumno   = data.idAlumno;
				alumno.idUsuario  = data.idUsuario;
				
                sessionStorage.setItem('tokenREADARKRIT', data.token);

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

        if( data ){

			$.each( data, function( index, element ) {

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

function cargarLibrosAnadidos(){

	var parametros = {};
	var peticion   = {};
	var html       = '';

	parametros.opcion = 'libroAnadido';
    parametros.accion = 'listar';

	peticion = peticionAJAX('../../php/libroAnadido.php', parametros);

    peticion.done(function( data, textStatus, jqXHR ) {

        if( !data.error ){

			$.each( data.librosAnadidos, function( index, element ) {

				html += '<tr data-idLibro=' + element.id_libro + '>';
				html += '<td><img src="../../img/portadasLibros/' + element.portada + '" alt="portada" class="portada"></td>';
				html += '<td>' + element.titulo + '</td>';
				html += '<td>' + element.titulo_original + '</td>';
				html += '<td>' + element.autor + '</td>';
				html += '<td>' + element.ano + '</td>';
				html += '</tr>';
			});
        }
        
        $('#tablaListado tbody').append( html );

        $('#tablaListado').DataTable({
        	// hace que no se pueda ordenar por la columna de checkbox
		    "ordering": true,
		    columnDefs: [{
		      orderable: false,
		      targets: "no-sort"
		    }],
		    "order": [[ 2, "asc" ]]	
		});
    });
}
// ------------------ EVENTOS ------------------------

$(function() {
    	
   	cargarTitulacion( $('#idTitulacion') );
   	cargarCurso( $('#idTitulacion') , $('#curso') );
   	cargarLibrosAnadidos();
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
	if( existeRegistro('nombre_usuario', nombreUsuario, 'usuario', false) ){

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

$('#tablaListado tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
} );