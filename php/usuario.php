<?php

	require_once("./general/bbdd.php");
	require_once("./general/token.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Hash.php");
	require_once("./clases/Usuario.php");

	$obj       = $_POST;
	$respuesta = array();

	if( $obj['opcion'] == 'usuario' && $obj['accion'] == 'listar' ){

		$sql = 'SELECT id_usuario, nombre, primer_apellido, segundo_apellido, f_nacimiento, correo, nombre_usuario, contrasena, bloqueado, f_baja FROM usuario';

		$respuesta['usuarios'] = consulta( '', '', '', $sql);
		$respuesta['error']    = ($respuesta['usuarios'] === false);
	}

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
		if($respuesta['rol'] == null)
			$respuesta['rol'] = 'visitante';
	}


	echo json_encode( $respuesta );

?>