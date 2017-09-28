<?php

	require_once("./general/bbdd.php");
	require_once("./clases/Hash.php");

	define("TABLA_SQL", "titulacion");

	$obj       = $_POST;
	$respuesta = array();


	if( $obj['opcion'] == 'titulacion' && $obj['accion'] == 'listar' ){

		$campos = 'id_titulacion, nombre, duracion';

		$titulaciones = consulta($campos, TABLA_SQL);

		$respuesta['titulaciones'] = $titulaciones;
	}


	echo json_encode( $respuesta );

?>