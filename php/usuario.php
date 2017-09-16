<?php

	require_once("./bbdd.php");
	require_once("./clases/Sesion.php");
	require_once("./clases/Hash.php");
	require_once("./clases/Usuario.php");
	require_once("./token.php");

	define("TABLA_SQL", "usuario");

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

	if($obj['opcion'] == 'usuario' && $obj['accion'] == 'comprobarRol'){
		$token = recuperarToken();
		$rol = null;

		if(!is_null($token) && comprobarToken($token)){
			$rol = getRol($token);
		}

		if(!is_null($rol)){
			$respuesta['rol'] = $rol;
		} else{
			$respuesta['rol'] = 'visitante';
		}
	}


	echo json_encode( $respuesta );

?>