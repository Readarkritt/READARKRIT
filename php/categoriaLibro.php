<?php

	require_once("./general/bbdd.php");
	require_once("./clases/Hash.php");

	$obj       = $_POST;
	$respuesta = array();


	if( $obj['opcion'] == 'categoriaLibro' && $obj['accion'] == 'listar' ){

		$campos = 'id_categoria, nombre';

		$categorias = consulta($campos, 'categoria_libro');

		if( $categorias ){

			$respuesta['categorias'] = $categorias;
			$respuesta['error']  = false;
		} else
			$respuesta['error'] = true;
	}


	echo json_encode( $respuesta );

?>