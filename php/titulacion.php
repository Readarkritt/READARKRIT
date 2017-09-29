<?php

	require_once("./general/bbdd.php");
	require_once("./clases/Hash.php");

	$obj       = $_POST;
	$respuesta = array();


	if( $obj['opcion'] == 'titulacion' && $obj['accion'] == 'listar' ){

		$campos = 'id_titulacion, nombre, duracion';

		$titulaciones = consulta($campos, 'titulacion');

		$respuesta['titulaciones'] = $titulaciones;
	}


	echo json_encode( $respuesta );

?>