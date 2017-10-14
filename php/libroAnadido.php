<?php

	require_once("./general/bbdd.php");
	require_once("./general/funciones.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Hash.php");
	require_once("./clases/LibroAnadido.php");

	// CONTROLADOR

	$obj       = $_POST;
	$respuesta = array();
	$libro     = array();

	if( $obj['opcion'] == 'libroAnadido' && $obj['accion'] == 'listar' ){

		$sql = 'select a.id_libro_anadido, l.portada, l.titulo, l.titulo_original, l.autor, l.ano, CASE WHEN l.anadido_por = 0 THEN "ARKRIT" ELSE concat(u.primer_apellido, " ", u.segundo_apellido, ", ", u.nombre) END as anadido_por, t.nombre as titulacion, p.nombre as pais, cl.nombre as categoria, a.posicion_ranking, a.media_num_usuarios, a.nivel_especializacion from libro l inner join libro_anadido a on l.id_libro = a.id_libro left join usuario u on l.anadido_por = u.id_usuario inner join titulacion t on l.id_titulacion = t.id_titulacion inner join pais p on a.id_pais = p.id_pais inner join categoria_libro cl on a.id_categoria = cl.id_categoria where l.f_baja is null';

		$respuesta['librosAnadidos'] = consulta( '', '', '', $sql);
		$respuesta['error']          = ($respuesta['librosAnadidos'] === false);
	}

	if( $obj['opcion'] == 'libroAnadido' && $obj['accion'] == 'alta' ){

	}

	if( $obj['opcion'] == 'libroAnadido' && $obj['accion'] == 'existe' ){

		$respuesta['existe'] = existeRegistro($obj['campo'], $obj['valor'], $obj['opcion']);
	}

	if( $obj['opcion'] == 'libroAnadido' && $obj['accion'] == 'modificar' ){

		
	}

	if( $obj['opcion'] == 'libroAnadido' && $obj['accion'] == 'eliminar' ){

		$obj['idLibroAnadido'] = (int) $obj['idLibroAnadido'];

		$libro = new LibroAnadido();
		$libro->cargar( $obj['idLibroAnadido'] );

		$respuesta['error'] = !$libro->eliminar();
	}

	echo json_encode( $respuesta );

?>