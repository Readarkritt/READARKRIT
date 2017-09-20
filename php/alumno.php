<?php

	require_once("./bbdd.php");
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

	echo json_encode( $respuesta );

?>