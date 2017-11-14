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

	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'modificarEstanteria' ){
		if(tienePermiso('alumno')){

			$librosNuevos           = [];
			$conteoLibrosExistentes = 0;
			$conteoLibrosNuevos     = 0;
			$campos 				= 'id_rel_libro_estanteria, id_libro, id_estanteria, libro_leido';
			$tabla                  = 'rel_libro_estanteria';
			$condicion              = 'id_estanteria = ' . $obj['idEstanteria'];
			$hayError               = false;

			if( isset($obj['libros']) ){

				$librosNuevos = $obj['libros'];
				$conteoLibrosNuevos = count($librosNuevos);
			}


			$librosExistentes = consulta( $campos, $tabla, $condicion);
			$conteoLibrosExistentes = dimensionArray($librosExistentes);

			if( $conteoLibrosExistentes == 1 )	// array de una dimensión
				$librosExistentes = array( $librosExistentes );


			if( $conteoLibrosNuevos > 0 ){

				if( $conteoLibrosExistentes > 0 ){	// caso 1: libros nuevos X, libros existentes Y	

					for( $i=0; $i<$conteoLibrosNuevos; $i++ ){

						$index = buscarValorEnArrObj( $librosExistentes, 'id_libro', $librosNuevos[$i]['idLibro'] );

						if( $index > -1 ){

							// el libro sigue estando en la estantería, comprobar si es distinto libro_leído

							if( strTObool($librosNuevos[$i]['libroLeido']) != (bool) $librosExistentes[$index]['libro_leido'] ){

								$hayError = actualizar( 'libro_leido', strTObool($librosNuevos[$i]['libroLeido']), $tabla, 'id_rel_libro_estanteria = ' . $librosExistentes[$index]['id_rel_libro_estanteria'] );
							}

						} else {

							// el libro no existe, se añade a la estantería

							$valores = '"", ' . $librosNuevos[$i]['idLibro'] . ', ' . $obj['idEstanteria'] . ', ' . $librosNuevos[$i]['libroLeido'];

							$hayError = insertar( $campos, $valores, $tabla );
						}
					}


					for( $i=0; $i<$conteoLibrosExistentes; $i++ ){

						$index = buscarValorEnArrObj( $librosNuevos, 'idLibro', $librosExistentes[$i]['id_libro'] );

						if( $index == -1 )	// el libro ya no está en la estantería, se elimina de ella
							$hayError = borrar( $tabla, 'id_rel_libro_estanteria = ' . $librosExistentes[$i]['id_rel_libro_estanteria'] );
					}


				} else {							// caso 2: libros nuevos X, libros existentes 0 --> insert

					for( $i=0; $i<$conteoLibrosNuevos; $i++ ){

						$valores = '"", ' . $librosNuevos[$i]['idLibro'] . ', ' . $obj['idEstanteria'] . ', ' . $librosNuevos[$i]['libroLeido'];

						$hayError = insertar( $campos, $valores, $tabla );
					}
				}

			} else 
				$hayError = borrar( $tabla, $condicion );		// caso 3: libros nuevos 0, libros existentes X --> delete

			// caso 4: libros nuevos 0, libros existentes 0
			// No hay cambios, no se hace nada

			if( $hayError === false ){

				$respuesta['error'] = true;
				$respuesta['descripcionError'] = 'No se han podido hacer los cambios en la estantería.';
			}
			else 
				
				$respuesta['error'] = false;

		
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
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