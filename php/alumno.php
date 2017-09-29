<?php

	require_once("./general/bbdd.php");
	require_once("./general/funciones.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Sesion.php");
	require_once("./clases/Hash.php");
	require_once("./clases/Alumno.php");

	// CONTROLADOR

	$obj       = $_POST;
	$respuesta = array();
	$alumno    = array();


	if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'alta' ){

		$usuarioValidado = validarCamposUsuario( $obj['usuario'] );
		$alumnoValidado  = validarCamposAlumno( $obj['alumno'] );

		if( $usuarioValidado && $alumnoValidado ){

			$alumno = new Alumno();
			$alumno->rellenar( $usuarioValidado, $alumnoValidado );

			$respuesta['idUsuario'] = $alumno->obtenerIdUsuario();
			$respuesta['idAlumno']  = $alumno->obtenerId();
			$respuesta['error']     = false;
		} else {

			$respuesta['error']            = true;
			$respuesta['descripcionError'] = 'Datos manipulados';
		}
	}

	if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'existe' ){

		$respuesta['existe'] = existeRegistro($obj['campo'], $obj['valor'], $obj['opcion']);
	}

	if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'modificar' ){

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
				if(Hash::esValido( $usuarioPeticion['contrasenaVieja'], $usuario->obtenerContrasena()) && validarContrasena($usuarioPeticion['contrasena'], $usuarioPeticion['contrasenaRepetida'])){
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
	}

	if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'recuperarConectado' ){
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
	}

	echo json_encode( $respuesta );

?>