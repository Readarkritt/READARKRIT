<?php

	require_once("./general/bbdd.php");
	require_once("./general/funciones.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Hash.php");

	// CONTROLADOR

	$obj       = $_POST;
	$respuesta = array();


	if( $obj['opcion'] == 'almasGemelas' && $obj['accion'] == 'listar' ){

		$misLibrosLeidos 		 = array();
		$librosLeidosOtroUsuario = array();
		$usuariosConLibrosLeidos = array();
		$numCoincidencias 		 = 0;
		$maxUsuarios			 = 0;
		$almasGemelas			 = array(); // se muestran como máximo 5 usuarios ($maxUsuarios) con las máximas coincidencias 

		// 1) Generamos los libros que he leído yo

		$sql = 'select rle.id_libro from rel_libro_estanteria rle inner join estanteria e on rle.id_estanteria = e.id_estanteria where rle.libro_leido = 1 and e.creada_por = ' . $obj['idUsuario'];

		$misLibrosLeidos = consulta( '', '', '', $sql);

		// 2) Generamos los usuarios que hayan leído algún libro en el sistema

		$sql = 'select e.creada_por from rel_libro_estanteria rle inner join estanteria e on rle.id_estanteria = e.id_estanteria where rle.libro_leido = 1 and e.creada_por != ' . $obj['idUsuario'];

		$usuariosConLibrosLeidos = consulta( '', '', '', $sql);

		// 3) Genero los libros que ha leído cada usuario y voy apuntando las coincidencias que tengo con cada uno

		for($i=0; $i < count($usuariosConLibrosLeidos); $i++){

			$sql = 'select rle.id_libro from rel_libro_estanteria rle inner join estanteria e on rle.id_estanteria = e.id_estanteria where rle.libro_leido = 1 and e.creada_por = ' . $usuariosConLibrosLeidos[$i];

			$librosLeidosOtroUsuario = consulta( '', '', '', $sql);
			
			for($j=0; $j < count($librosLeidosOtroUsuario); $j++)
				if( in_array($librosLeidosOtroUsuario[$i], $misLibrosLeidos) )
					$numCoincidencias++;

			$almasGemelas[$i]['idUsuario']     = $usuariosConLibrosLeidos[$i];
			$almasGemelas[$i]['coincidencias'] = $numCoincidencias;

			$numCoincidencias = 0;
		}

		// 4) Ordeno el array de coincidencias, de mayor a menor coincidencia

		$almasGemelas = ordenarArrayMultidimensional($almasGemelas, 'coincidencias', SORT_ASC);

		// 5) Busco el nombre de los usuarios que se van a devolver (máximo 5 usuarios)

		$maxUsuarios = count($almasGemelas);

		if( $maxUsuarios > 5 )
			$maxUsuarios = 5;

		for ($i=0; $i < $maxUsuarios; $i++){

			$almasGemelas[$i]['nombre_usuario'] = consulta('nombre_usuario', 'usuario', 'id_usuario = ' . $almasGemelas[$i]['idUsuario']);

			array_push($respuesta['almasGemelas'], $almasGemelas[$i]);
		}


		$respuesta['error'] = ( count($respuesta['almasGemelas']) == 0 );
	}

	echo json_encode( $respuesta );

?>