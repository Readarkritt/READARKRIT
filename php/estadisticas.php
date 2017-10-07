<?php

	require_once("./general/bbdd.php");
	require_once("./general/token.php");

	$obj       = $_POST;
	$respuesta = array();
	$sql       = '';

	if( $obj['opcion'] == 'estadisticas' && $obj['accion'] == 'listar' ){

		$sql  = 'select count(u.id_usuario) ';
		$sql .= 'from usuario u ';
		$sql .= 'left join alumno a on u.id_usuario = a.id_usuario ';
		$sql .= 'left join profesor p on u.id_usuario = p.id_usuario ';
		$sql .= 'where u.f_baja is null';
		$respuesta['miembrosComunidad'] = consulta('', '', '', $sql);

		$sql  = 'select count(l.id_libro) ';
		$sql .= 'from libro l ';
		$sql .= 'inner join libro_anadido la on l.id_libro = la.id_libro ';
		//$sql .= 'where l.f_baja is null';
		$respuesta['totalLibros'] = consulta('', '', '', $sql);

		$sql = '';
		$respuesta['numResenas'] = 0;

		$sql = '';
		$respuesta['numGrupos'] = 0;

		$sql = '';
		$respuesta['numSeguidores'] = 0;

		$sql = '';
		$respuesta['numPersonasSeguidas'] = 0;


		$respuesta['error'] = false;
	}

	echo json_encode( $respuesta );

?>