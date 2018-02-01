<?php

	require_once("./general/bbdd.php");
	require_once("./general/funciones.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Hash.php");
	require_once("./clases/Alumno.php");
	require_once("./clases/Estanteria.php");

	// CONTROLADOR

	$obj       = $_POST;
	$respuesta = array();
	$alumno    = array();
	$estanteria = array();


	if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'alta' ){

		$usuarioValidado = validarCamposUsuario( $obj['usuario'] );
		$alumnoValidado  = validarCamposAlumno( $obj['alumno'] );

		if(isset($obj['administracion']) && ($obj['administracion'] == 'true' || $obj['administracion'] === 1)){
			$obj['administracion'] = true;
		} else{
			$obj['administracion'] = false;
		}

		if( $usuarioValidado && $alumnoValidado && ( $obj['administracion'] || count($obj['librosLeidos']) != 0  ) ){

			// 1) Crear usuario
			$alumno = new Alumno();
			$alumno->rellenar( $usuarioValidado, $alumnoValidado );

			// 2) Crear la estantería por defecto ('Libros Leídos') para ese usuario
			$estanteria = new Estanteria();
			$estanteria->rellenarDefault( $alumno->obtenerIdUsuario() );

			// 3) Relacionar la estantería con los libros leídos
			if(!$obj['administracion']){
				$camposSQL = 'id_rel_libro_estanteria, id_libro, id_estanteria, libro_leido';
				$arrValores = array();

				for($i=0; $i<count($obj['librosLeidos']); $i++){
				//$idLibro = consulta( 'id_libro', 'libro_anadido', 'id_libro_anadido = '. $obj['librosLeidos'][$i] );

				$arrValores[0] = ''; // id_rel_libro_estanteria
				$arrValores[1] = $obj['librosLeidos'][$i]; 
				$arrValores[2] = $estanteria->obtenerId();
				$arrValores[3] = 1;

					insertar( $camposSQL, $arrValores, 'rel_libro_estanteria' );
				}
			}

			// 4) Respuesta de la petición
			$respuesta['idUsuario'] = $alumno->obtenerIdUsuario();
			$respuesta['idAlumno']  = $alumno->obtenerId();

			//CREAR TOKEN
			$respuesta['token'] = generarToken($alumno->obtenerIdUsuario(), $alumno->obtenerUsuario()->obtenerNombre(), $alumno->obtenerUsuario()->obtenerNombreUsuario(), 'alumno', $alumno->obtenerUsuario()->obtenerCorreo());

			$respuesta['error']     = false;
		} else {

			$respuesta['error']            = true;
			$respuesta['descripcionError'] = 'Datos manipulados';
		}
	}

	if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'existe' ){

		$respuesta['existe'] = existeRegistro($obj['campo'], $obj['valor'], $obj['opcion']);
	}

	if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'modificarConectado' ){
		if(tienePermiso('alumno')){

			$alumnoPeticion = $obj['alumno'];
			$usuarioPeticion = $alumnoPeticion['usuario'];

			$respuesta['errorCorreo'] = false;
			$respuesta['errorTitulacion'] = false;
			$respuesta['errorCurso'] = false;
			$respuesta['errorContrasena'] = false;

			$id = recuperarDeToken('id');
			if($id != null){
				$alumno = new Alumno();
				$alumno->cargar($alumnoPeticion['idAlumno']);
				$usuario = $alumno->obtenerUsuario();

				if($usuarioPeticion['contrasena'] != ''){
					if($usuarioPeticion['contrasena'] == $usuarioPeticion['contrasenaRepetida']){
						$usuario->cambiarContrasena($usuarioPeticion['contrasena']);
					} else{			
						$respuesta['errorContrasena'] = true;
					}
				}

				if($usuario->obtenerCorreo() != $usuarioPeticion['correo']){
					if(validarCorreo($usuarioPeticion['correo'])){
						$usuario->modificarCorreo($usuarioPeticion['correo']);
					} else{
						$respuesta['errorCorreo'] = true;	
					}		
				}
				if(!$respuesta['errorCorreo']){
					$respuesta['correo'] = $usuarioPeticion['correo'];
				}

				if($alumno->obtenerIdTitulacion() != $alumnoPeticion['idTitulacion']){
					if(parseInt($alumnoPeticion['idTitulacion']) > 0){
						$alumno->cambiarIdTitulacion($alumnoPeticion['idTitulacion']);
					} else{
						$respuesta['errorTitulacion'] = true;
					}
				}

				if($alumno->obtenerCurso() != $alumnoPeticion['curso']){
					if(parseInt($alumnoPeticion['curso']) > 0){
						$alumno->cambiarCurso($alumnoPeticion['curso']);
					} else{
						$respuesta['errorCurso'] = true;
					}
				}
			} else{
				$respuesta['error'] = true;
				$respuesta['descripcionError'] = 'Token erróneo';			
			}
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	}

	if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'recuperarConectado' ){

		if(tienePermiso('alumno')){
			$id = recuperarDeToken('id');
			if($id != null){
				$idAlumno = consulta( 'id_Alumno', 'alumno', 'id_Usuario = '.$id );
				if($idAlumno != null){
					$respuesta['tokenErroneo'] = false;
					$alumno = new Alumno();
					$alumno->cargar($idAlumno);
					$respuesta['alumno'] = $alumno->toArray();
					$respuesta['alumno']['usuario']['contrasena'] = '';
				} else{
					$respuesta['tokenErroneo'] = true;
				}
			} else{
				$respuesta['tokenErroneo'] = true;
			}
		}else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	}

	else if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'listar' ){
		if(tienePermiso('alumno')){
			$sql = 'select u.id_usuario, u.nombre, u.primer_apellido, u.segundo_apellido, u.nombre_usuario, u.correo, a.id_alumno, a.num_expediente, a.id_titulacion, a.curso from usuario u inner join alumno a on u.id_usuario = a.id_usuario where f_baja is null';

			$respuesta['alumnos'] = consulta( '', '', '', $sql);
			$respuesta['error']      = ($respuesta['alumnos'] === false);
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	}

	else if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'obtener' ){
		if(tienePermiso('alumno')){
			$sql = 'select u.nombre, u.primer_apellido, u.segundo_apellido, u.nombre_usuario, u.f_nacimiento, u.correo, u.bloqueado, a.id_alumno, a.num_expediente, a.id_titulacion, a.curso from usuario u inner join alumno a on u.id_usuario = a.id_usuario where id_alumno ='.$obj['idAlumno'];

			$respuesta['alumno'] = consulta( '', '', '', $sql);
			$respuesta['alumno']['f_nacimiento'] = formatearFecha($respuesta['alumno']['f_nacimiento'],'objeto');
			$respuesta['error'] = false;
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	}

	else if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'modificar' ){

		if(tienePermiso('alumno')){
			if($obj['usuario']['contrasena'] == '')
				$comprobarContrasena = false;
			else
				$comprobarContrasena = true;

			$usuarioValidado	= validarCamposUsuario($obj['usuario'], false,$comprobarContrasena,true);
			$alumnoValidado		= validarCamposAlumno($obj['alumno'], false);

			if($alumnoValidado && $usuarioValidado){
				$alumnoOriginal = new Alumno();
				$alumnoOriginal->cargar($alumnoValidado['idAlumno']);

				//CAMBIAR DATOS
				if($alumnoOriginal->obtenerUsuario()->obtenerNombre() != $usuarioValidado['nombre'])
					$alumnoOriginal->obtenerUsuario()->cambiarNombre($usuarioValidado['nombre']);

				if($alumnoOriginal->obtenerUsuario()->obtenerPrimerApellido() != $usuarioValidado['primerApellido'])
					$alumnoOriginal->obtenerUsuario()->modificarPrimerApellido($usuarioValidado['primerApellido']);

				if($alumnoOriginal->obtenerUsuario()->obtenerSegundoApellido() != $usuarioValidado['segundoApellido'])
					$alumnoOriginal->obtenerUsuario()->modificarSegundoApellido($usuarioValidado['segundoApellido']);

				if(formatearFecha($alumnoOriginal->obtenerUsuario()->obtenerFNacimiento(),'bbdd') != $usuarioValidado['fNacimiento'])	
					$alumnoOriginal->obtenerUsuario()->modificarFNacimiento($usuarioValidado['fNacimiento'],'bbdd');			

				if($alumnoOriginal->obtenerUsuario()->obtenerCorreo() != $usuarioValidado['correo'])
					$alumnoOriginal->obtenerUsuario()->modificarCorreo($usuarioValidado['correo']);

				if($alumnoOriginal->obtenerUsuario()->obtenerNombreUsuario() != $usuarioValidado['nombreUsuario'])
					$alumnoOriginal->obtenerUsuario()->modificarNombreUsuario($usuarioValidado['nombreUsuario']);
				
				if($obj['usuario']['contrasena'] != '')
					$alumnoOriginal->obtenerUsuario()->cambiarContrasena($usuarioValidado['contrasena']);

				if($alumnoOriginal->obtenerNumExpediente() != $alumnoValidado['numExpediente'])
					$alumnoOriginal->cambiarNumExpediente($alumnoValidado['numExpediente']);

				if($alumnoOriginal->obtenerIdTitulacion() != $alumnoValidado['idTitulacion'])
					$alumnoOriginal->cambiarIdTitulacion($alumnoValidado['idTitulacion']);

				if($alumnoOriginal->obtenerCurso() != $alumnoValidado['curso'])
					$alumnoOriginal->cambiarCurso($alumnoValidado['curso']);			

				if($alumnoOriginal->obtenerUsuario()->estaBloqueado() != $usuarioValidado['bloqueado'])
					$alumnoOriginal->obtenerUsuario()->modificarBloqueado($usuarioValidado['bloqueado']);

				$sql = 'select u.nombre, u.primer_apellido, u.segundo_apellido, u.nombre_usuario, u.correo, a.id_alumno, a.num_expediente, a.id_titulacion, a.curso, u.bloqueado from usuario u inner join alumno a on u.id_usuario = a.id_usuario where u.id_usuario = '.$alumnoValidado['idUsuario'];

				$respuesta['alumno'] = consulta( '', '', '', $sql);
				$respuesta['error'] = false;
			} else{
				$respuesta['error'] = true;
				$respuesta['errorDescripcion'] = 'Datos manipulados';

			}
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}

	}
	echo json_encode( $respuesta );

?>