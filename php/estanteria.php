<?php
	require_once("./general/bbdd.php");
	require_once("./general/token.php");
	require_once("./general/funciones.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Hash.php");
	require_once("./clases/Estanteria.php");

	// CONTROLADOR

	$obj        = $_POST;
	$respuesta  = array();
	$estanteria = array();
	$idUsuario  = recuperarDeToken('id');


	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'listar' ){

		$sql = 'SELECT e.id_estanteria, e.nombre, count(rle.id_rel_libro_estanteria) as cantidad_libros FROM estanteria e LEFT JOIN rel_libro_estanteria rle ON e.id_estanteria = rle.id_estanteria WHERE e.creada_por = ' . $idUsuario . ' GROUP BY e.id_estanteria';

		$respuesta['estanterias'] = consulta( '', '', '', $sql);
		$respuesta['error']       = ($respuesta['estanterias'] === false);
	}

	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'listadoLibrosCompleto' ){

		$sql = 'SELECT l.id_libro, l.portada, l.titulo, l.autor FROM rel_libro_estanteria rle INNER JOIN libro l ON rle.id_libro = l.id_libro WHERE rle.id_estanteria = ' . $obj['idEstanteria'];

		$respuesta['libros'] = consulta( '', '', '', $sql);
		$respuesta['error']  = ($respuesta['libros'] === false);
	}

	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'cambiarNombre' ){
		if(tienePermiso('alumno')){
			$estanteria = new Estanteria();

			$estanteria->cargar( $obj['idEstanteria'] );

			$respuesta['error'] = $estanteria->cambiarNombre( $obj['nombreEstanteria'] );

			if( $respuesta['error'] )
				$respuesta['descripcionError'] = 'No se ha podido cambiar el nombre de la estantería';

		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	}

	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'alta' ){
		if(tienePermiso('alumno')){

			$obj['estanteria']['nombre'] = validarCampoTexto( $obj['estanteria']['nombre'], 20 );

			$obj['estanteria']['creadaPor'] = $idUsuario;

			if( $obj['estanteria'] ){

				$estanteria = new Estanteria();
				$estanteria->rellenar( $obj['estanteria'] );

				$idEstanteria = $estanteria->obtenerId();

				if( $idEstanteria === false ){

					$respuesta['error']            = true;
					$respuesta['descripcionError'] = 'No se ha podido crear la estantería.';

				} else {

					$respuesta['idEstanteria'] = $idEstanteria;
					$respuesta['error'] = false;
				}

			} else {

				$respuesta['error']            = true;
				$respuesta['descripcionError'] = 'Datos manipulados';
			}
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	}

	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'existe' ){

		$respuesta['existe'] = existeRegistro($obj['campo'], $obj['valor'], $obj['opcion']);
	}


	// rel_libro_estanteria

	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'listarLibros' ){

		$condicion = 'id_estanteria = ' . $obj['idEstanteria'];

		$respuesta['idsLibrosEstanteria'] = consulta( 'id_libro, libro_leido', 'rel_libro_estanteria', $condicion);
		$respuesta['error']               = ($respuesta['idsLibrosEstanteria'] === false);
	}


	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'altaLibro' ){

		$valores             = '';
		$idslibrosErroneos   = [];
		$idsLibrosInsertar   = [];
		$condicion           = 'id_estanteria = ' . $obj['idEstanteria'];
		$idsLibrosEstanteria = consulta( 'id_libro', 'rel_libro_estanteria', $condicion);

		if( is_array( $obj['idsLibros'] ) )
			$idsLibrosInsertar = $obj['idsLibros'];
		else
			$idsLibrosInsertar[0] = $obj['idsLibros'];


		if( !is_array( $idsLibrosEstanteria ) )
			$idsLibrosEstanteria[0] = $idsLibrosEstanteria;


		for ($i=0; $i < count($idsLibrosInsertar); $i++) { 
			
			if( in_array($idsLibrosInsertar[$i], $idsLibrosEstanteria) )
				array_push($idslibrosErroneos, $idsLibrosInsertar[$i]);
			else {

				$valores = '"", ' . $idsLibrosInsertar[$i] . ', ' . $obj['idEstanteria'] . ', false';

				if( is_bool( insertar( 'id_rel_libro_estanteria, id_libro, id_estanteria, libro_leido', $valores, 'rel_libro_estanteria' ) ) )
					array_push($idslibrosErroneos, $idsLibrosInsertar[$i]);
			}
		}


		if( count($idslibrosErroneos) != 0 ){

			$respuesta['descripcionError'] = 'Algunos libros de los que has seleccionado ya los tienes, los demás se han añadido';
			$respuesta['error'] = true;
		} else 
			$respuesta['error'] = false;
	}


	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'eliminarLibro' ){

		$condicion            = 'id_estanteria = ' . $obj['idEstanteria'] . ' and id_libro = ' . $obj['idLibro'];
		$idRelLibroEstanteria = consulta( 'id_rel_libro_estanteria', 'rel_libro_estanteria', $condicion);

		if( !is_bool( $idRelLibroEstanteria ) && !is_null( $idRelLibroEstanteria ) ){

			$respuesta['error'] = !borrar( 'rel_libro_estanteria', 'id_rel_libro_estanteria = ' . $idRelLibroEstanteria );

			if( $respuesta['error'] )
				$respuesta['descripcionError'] = 'No se ha podido eliminar el libro de la estantería';
		}
	}


	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'marcarLibroComoLeido' ){

		$condicion = 'id_estanteria = ' . $obj['idEstanteria'] . ' and id_libro = ' . $obj['idLibro'];
		$registro  = consulta( 'id_rel_libro_estanteria, libro_leido', 'rel_libro_estanteria', $condicion);

		if( !is_bool( $registro ) && !is_null( $registro ) ){

			$respuesta['error'] = !actualizar( 'libro_leido', !$registro['libro_leido'], 'rel_libro_estanteria', 'id_rel_libro_estanteria = ' . $registro['id_rel_libro_estanteria'] );

			if( $respuesta['error'] )
				$respuesta['descripcionError'] = 'No se ha podido cambiar el estado del libro';
		}
	}


	// Recomendaciones ARKRIT

	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'generarRecomendacionesArkrit' ){

		$respuesta['recomendaciones'] = generarRecomendacionesArkrit($idUsuario);
		$respuesta['error']           = ($respuesta['recomendaciones'] === false);
	}

	// REL usuario_sigue_estanteria

	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'listarEstanteriasSeguidas' ){	// lista las estanterías que tiene un usuario, además de mostrar si tú las sigues o no

		$sql = 'SELECT e.id_estanteria, e.nombre, count(rle.id_rel_libro_estanteria) as cantidad_libros, case when uses.id_estanteria is null then 0 else 1 end as seguida FROM estanteria e LEFT JOIN rel_libro_estanteria rle ON e.id_estanteria = rle.id_estanteria LEFT JOIN usuario_sigue_estanteria uses ON rle.id_estanteria = uses.id_estanteria WHERE e.creada_por = ' . $obj['propietarioEstanteria'] . ' and (uses.id_usuario is null or uses.id_usuario = ' . $idUsuario . ') GROUP BY e.id_estanteria';

		$respuesta['estanterias'] = consulta( '', '', '', $sql);
		$respuesta['error']       = ($respuesta['estanterias'] === false);
	}

	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'listarEstanteriasQueSigo' ){	// lista las estanterías que sigues en ese momento

		$sql = 'SELECT e.nombre, u.nombre_usuario FROM usuario_sigue_estanteria uses INNER JOIN estanteria e ON uses.id_estanteria = e.id_estanteria INNER JOIN usuario u ON e.creada_por = u.id_usuario WHERE uses.id_usuario = ' . $idUsuario;

		$respuesta['estanteriasSeguidas'] = consulta( '', '', '', $sql);
		$respuesta['error']               = ($respuesta['estanteriasSeguidas'] === false);
	}

	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'seguirEstanteria' ){

		$condicion       = 'id_usuario = ' . $idUsuario . ' and id_estanteria = ' . $obj['idEstanteria'];
		$sigueEstanteria = consulta( 'count(id_estanteria)', 'usuario_sigue_estanteria', $condicion);
		$hayError        = false;

		if( $sigueEstanteria == 0 ){

			// Si no sigue la estantería, creamos la relación para que la siga

			$valores = '"", ' . $obj['idEstanteria'] . ', ' . $idUsuario;

			$hayError = ( insertar( 'id, id_estanteria, id_usuario', $valores, 'usuario_sigue_estanteria' ) === false );
		} else {

			// Si la sigue, se entiende que ya no la quiere seguir, por lo que borramos la relación
			$hayError = !borrar( 'usuario_sigue_estanteria', $condicion ); 
		}


		$respuesta['error'] = $hayError;

		if( $hayError )
			$respuesta['descripcionError'] = 'No se ha podido realizar la acción solicitada.';
	}

	
	echo json_encode( $respuesta );

?>