<?php

	require_once("./bbdd.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Sesion.php");
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


	echo json_encode( $respuesta );

?>