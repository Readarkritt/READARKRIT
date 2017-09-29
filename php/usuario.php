<?php


	require_once("./general/bbdd.php");
	require_once("./general/token.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Hash.php");
	require_once("./clases/Usuario.php");

	$obj       = $_POST;
	$respuesta = array();

	if( $obj['opcion'] == 'usuario' && $obj['accion'] == 'alta' ){

		$usuario = new Usuario();

		$usuario->rellenar( $obj['usuario'] );

		$respuesta['idUsuario'] = $usuario->obtenerId();
	}

	if( $obj['opcion'] == 'usuario' && $obj['accion'] == 'existe' ){

		$respuesta['existe'] = existeRegistro($obj['campo'], $obj['valor'], $obj['opcion']);
	}

	if( $obj['opcion'] == 'usuario' && $obj['accion'] == 'getRol' ){
		$respuesta['rol'] = recuperarDeToken('rol');
	}


	echo json_encode( $respuesta );

?>