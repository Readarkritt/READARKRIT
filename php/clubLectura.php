<?php
	require_once("./general/bbdd.php");
	require_once("./general/token.php");
	require_once("./general/funciones.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Hash.php");
	require_once("./clases/ClubLectura.php");

	// CONTROLADOR

	$obj        = $_POST;
	$respuesta  = array();
	$clubLectura = array();

	if( $obj['opcion'] == 'clubLectura' && $obj['accion'] == 'listar' ){

		if( empty( $obj['idUsuario'] ) ){

			$campos = 'id_club, creado_por, nombre, f_inicio, f_fin, id_titulacion, curso';
			$tabla  = 'club_lectura';
			$where  = 'f_fin is null';

			$respuesta['clubsLectura'] = consulta( $campos, $tabla, $where );

		} else {

			$sql = 'SELECT cl.id_club, cl.creado_por, cl.nombre, cl.f_inicio, cl.f_fin, cl.id_titulacion, cl.curso FROM club_lectura cl INNER JOIN miembro_club mc on cl.id_club = mc.id_club WHERE cl.f_fin is null and mc.id_usuario = ' . $obj['idUsuario']; 

			$respuesta['clubsLectura'] = consulta( '', '', '', $sql );
		}

		$respuesta['error'] = ($respuesta['clubsLectura'] === false);
	}

	if( $obj['opcion'] == 'clubLectura' && $obj['accion'] == 'existe' ){

		$respuesta['existe'] = existeRegistro($obj['campo'], $obj['valor'], 'club_lectura');
	}

	if( $obj['opcion'] == 'clubLectura' && $obj['accion'] == 'abrir' ){

		$clubLecturaValidado = validarCamposClubLectura( $obj['clubLectura'] );

		if( $clubLecturaValidado ){

			$clubLectura = new ClubLectura();

			$clubLectura->rellenar( $clubLecturaValidado );

			if( !empty($obj['anadirTodosLosAlumnosDelCurso']) ){

				$where = 'id_titulacion = ' . $obj['clubLectura']['idTitulacion'];

				if( $obj['clubLectura']['idTitulacion'] == 1 )
					$where .= ' AND curso = ' . $obj['clubLectura']['curso'];

				$idsUsuarioCurso = consulta( 'id_usuario', 'alumno', $where );

				for( $i=0; $i < count($idsUsuarioCurso); $i++ ){

					$valores = '"", ' . $clubLectura->obtenerId() . ', ' . $idsUsuarioCurso[$i];

					insertar( 'id, id_club, id_usuario', $valores, 'miembro_club' );
				}
				
			} 


			for( $i=0; $i < count($obj['miembrosClub']); $i++ ){

				$valores = '"", ' . $clubLectura->obtenerId() . ', ' . $obj['miembrosClub'][$i]['id_usuario'];

				insertar( 'id, id_club, id_usuario', $valores, 'miembro_club' );
			}


			$respuesta['error']  = false;

		} else {

			$respuesta['error']            = true;
			$respuesta['descripcionError'] = 'Datos manipulados';
		}

	}


	if( $obj['opcion'] == 'clubLectura' && $obj['accion'] == 'cerrar' ){

		$obj['idClub'] = (int) $obj['idClub'];

		$clubLectura = new ClubLectura();

		$clubLectura->cargar( $obj['idClub'] );

		$respuesta['error'] = !$clubLectura->cerrar();
	}


	/* MIEMBROS_CLUB */

	if( $obj['opcion'] == 'miembrosClub' && $obj['accion'] == 'listar' ){

		$where = 'id_club = ' . $obj['idClub']; 

		$respuesta['idsMiembrosClub'] = consulta( 'id_usuario', 'miembro_club', $where );

		$respuesta['error'] = ($respuesta['idsMiembrosClub'] === false);
	}

	if( $obj['opcion'] == 'miembrosClub' && $obj['accion'] == 'modificar' ){

		$problemasInsertar = false;
		$problemasBorrar   = false;


		$where = 'id_club = ' . $obj['idClub']; 

		$miembrosActuales = consulta( 'id_usuario', 'miembro_club', $where );

		// insertar los nuevos elementos

		if( is_null($miembrosActuales) ){

			for($i=0; $i < count($obj['miembrosClub']); $i++){

				$valores = '"", ' . $obj['idClub'] . ', ' . $obj['miembrosClub'][$i]['id_usuario'];

				$problemasInsertar = (insertar( 'id, id_club, id_usuario', $valores, 'miembro_club' ) === false);
			}

		} else {

			if( is_array($miembrosActuales) ){ // hay más de un miembro

				for($i=0; $i < count($obj['miembrosClub']); $i++){

					if( !in_array($obj['miembrosClub'][$i]['id_usuario'], $miembrosActuales) ){	// si no está en los miembros actuales, se añade

						$valores = '"", ' . $obj['idClub'] . ', ' . $obj['miembrosClub'][$i]['id_usuario'];

						$problemasInsertar = (insertar( 'id, id_club, id_usuario', $valores, 'miembro_club' ) === false);
					}
				}

			} else {	// si sólo hay un miembro

				for($i=0; $i < count($obj['miembrosClub']); $i++){

					if( $obj['miembrosClub'][$i]['id_usuario'] != $miembrosActuales ){

						$valores = '"", ' . $obj['idClub'] . ', ' . $obj['miembrosClub'][$i]['id_usuario'];

						$problemasInsertar = (insertar( 'id, id_club, id_usuario', $valores, 'miembro_club' ) === false);
					}
				}
			}

		}

		// borrar elementos que ya no están

		if( count($obj['miembrosClub']) == 0 ){

			$where = 'id_club = ' . $obj['idClub'];

			$problemasBorrar = !borrar( 'miembro_club', $where );

		} else {

			if( is_array($miembrosActuales) ){ // hay más de un miembro

				for($i=0; $i < count($miembrosActuales); $i++){

					$j          = 0;
					$encontrado = false;

					while( $j < count($obj['miembrosClub']) && !$encontrado ) {
						
						if( $obj['miembrosClub'][$j]['id_usuario'] == $miembrosActuales[$i] )
							$encontrado = true;

						$j++;
					}

					if( !$encontrado ){

						$where = 'id_club = ' . $obj['idClub'] . ' and id_usuario = ' . $miembrosActuales[$i];

						$problemasBorrar = !borrar( 'miembro_club', $where );
					}
				}

			} else {	// si sólo hay un miembro

				$i          = 0;
				$encontrado = false;

				while( $i < count($obj['miembrosClub']) && !$encontrado ) {
					
					if( $obj['miembrosClub'][$i]['id_usuario'] == $miembrosActuales )
						$encontrado = true;

					$i++;
				}

				if( !$encontrado ){

					$where = 'id_club = ' . $obj['idClub'] . ' and id_usuario = ' . $miembrosActuales;

					$problemasBorrar = !borrar( 'miembro_club', $where );
				}
			}

		}


		if( $problemasInsertar ) {

			$respuesta['error']            = true;
			$respuesta['descripcionError'] = 'Ha habido problemas al insertar los nuevos miembros del club.';

		} elseif( $problemasBorrar ) {

			$respuesta['error']            = true;
			$respuesta['descripcionError'] = 'Ha habido problemas al borrar antiguos miembros del club.';

		} else
			$respuesta['error'] = false;
	}

	echo json_encode($respuesta);

?>