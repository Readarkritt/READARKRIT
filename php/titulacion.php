<?php

	require_once("./general/bbdd.php");
	require_once("./clases/Hash.php");

	$obj       = $_POST;
	$respuesta = array();


	if( $obj['opcion'] == 'titulacion' && $obj['accion'] == 'listar' ){

		$campos = 'id_titulacion, nombre, duracion';

		$respuesta = consulta($campos, 'titulacion');
	}


	echo json_encode( $respuesta );

?>