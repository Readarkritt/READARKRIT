<?php

	require_once("./general/bbdd.php");
	require_once("./general/token.php");

	$obj       = $_POST;
	$respuesta = array();
	$sql       = '';
	$idUsuario = recuperarDeToken('id');

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

		$sql  = 'select count(r.id_resena) ';
		$sql .= 'from resena r ';
		$sql .= 'where r.id_usuario = ' . $idUsuario;
		$respuesta['numResenas'] = consulta('', '', '', $sql);

		$sql  = 'select count(mc.id_club) ';
		$sql .= 'from miembro_club mc ';
		$sql .= 'inner join club_lectura cl on mc.id_club = cl.id_club ';
		$sql .= 'where cl.f_fin is null and mc.id_usuario = ' . $idUsuario;
		$respuesta['numGrupos'] = consulta('', '', '', $sql);

		$sql  = 'select count( distinct uses.id_usuario ) ';
		$sql .= 'from usuario_sigue_estanteria uses ';
		$sql .= 'inner join estanteria e on uses.id_estanteria = e.id_estanteria ';
		$sql .= 'where e.creada_por = ' . $idUsuario;
		$respuesta['numSeguidores'] = consulta('', '', '', $sql);

		$sql  = 'select count( distinct e.creada_por ) ';
		$sql .= 'from usuario_sigue_estanteria uses ';
		$sql .= 'inner join estanteria e on uses.id_estanteria = e.id_estanteria ';
		$sql .= 'where uses.id_usuario = ' . $idUsuario;
		$respuesta['numPersonasSeguidas'] = consulta('', '', '', $sql);


		$respuesta['error'] = false;
	}

	echo json_encode( $respuesta );

?>