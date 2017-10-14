<?php

	require_once("./general/bbdd.php");
	require_once("./clases/Hash.php");

	$obj       = $_POST;
	$respuesta = array();


	if( $obj['opcion'] == 'categoriaLibro' && $obj['accion'] == 'listar' ){

		$campos = 'id_categoria, nombre';

		$respuesta = consulta($campos, 'categoria_libro');
	}


	echo json_encode( $respuesta );

?>