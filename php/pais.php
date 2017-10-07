<?php

	require_once("./general/bbdd.php");
	require_once("./clases/Hash.php");

	$obj       = $_POST;
	$respuesta = array();


	if( $obj['opcion'] == 'pais' && $obj['accion'] == 'listar' ){

		$campos = 'id_pais, iso, nombre';

		$paises = consulta($campos, 'pais');

		if( $paises ){

			$respuesta['paises'] = $paises;
			$respuesta['error']  = false;
		} else
			$respuesta['error'] = true;
	}


	echo json_encode( $respuesta );

?>