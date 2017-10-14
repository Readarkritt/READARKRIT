<?php

	require_once("./general/bbdd.php");
	require_once("./clases/Hash.php");

	$obj       = $_POST;
	$respuesta = array();


	if( $obj['opcion'] == 'pais' && $obj['accion'] == 'listar' ){

		$campos = 'id_pais, iso, nombre';

		$respuesta = consulta($campos, 'pais');
	}


	echo json_encode( $respuesta );

?>