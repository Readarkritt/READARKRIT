<?php
	require_once("./general/bbdd.php");
	require_once("./general/token.php");
	require_once("./general/funciones.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Hash.php");
	require_once("./clases/ComentarioClub.php");

	// CONTROLADOR

	$obj        = $_POST;
	$respuesta  = array();
	$comentario = array();
	$idUsuario  = recuperarDeToken('id');

	if( $obj['opcion'] == 'comentarioClub' && $obj['accion'] == 'listar' ){

		if( !is_null( consulta( 'id_usuario', 'miembro_club', 'id_usuario = ' . $idUsuario . ' and id_club = ' . $obj['idClub']) ) ){

			$sql = 'SELECT cc.id_comentario_club, cc.id_club, cc.id_usuario, u.nombre, u.primer_apellido, cc.fecha, cc.comentario FROM comentario_club cc INNER JOIN usuario u ON cc.id_usuario = u.id_usuario WHERE cc.id_club = ' . $obj['idClub'];

			$respuesta['comentarios'] = consulta( '', '', '', $sql);
			$respuesta['error']       = ($respuesta['comentarios'] === false);
		} else {

			$respuesta['error']            = true;
			$respuesta['descripcionError'] = 'Acceso denegado a este club de lectura.';
		}
	}

	if( $obj['opcion'] == 'comentarioClub' && $obj['accion'] == 'anadir' ){

		$obj['comentario']['comentario'] = validarTextArea( $obj['comentario']['comentario'], 1000 );

		$obj['comentario']['idUsuario'] = $idUsuario;

		if( $obj['comentario']['comentario'] ){

			$comentario = new ComentarioClub();

			$comentario->rellenar( $obj['comentario'] );

			if( $comentario->obtenerId() != 0 )
				$respuesta['error']  = false;
			else {

				$respuesta['error']            = true;
				$respuesta['descripcionError'] = 'No se ha podido añadir el comentario al club.';
			}


		} else {

			$respuesta['error']            = true;
			$respuesta['descripcionError'] = 'Datos manipulados';
		}

	}


	echo json_encode($respuesta);

?>