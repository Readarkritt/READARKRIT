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

	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'listar' ){

		$sql = 'SELECT e.id_estanteria, e.nombre, count(rle.id_rel_libro_estanteria) as cantidad_libros FROM estanteria e INNER JOIN rel_libro_estanteria rle ON e.id_estanteria = rle.id_estanteria WHERE e.creada_por = 64 GROUP BY rle.id_estanteria';

		$respuesta['estanterias'] = consulta( '', '', '', $sql);
		$respuesta['error']       = ($respuesta['estanterias'] === false);
	}

	if( $obj['opcion'] == 'estanteria' && $obj['accion'] == 'cambiarNombre' ){

		$estanteria = new Estanteria();

		$estanteria->cargar();

		$respuesta['error'] = !$estanteria->cambiarNombre();
	}

	/*if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'eliminar' ){

		$obj['idProfesor'] = (int) $obj['idProfesor'];

		$profesor = new Profesor();
		$profesor->cargar( $obj['idProfesor'] );

		$respuesta['error'] = !$profesor->eliminar();
	}*/

	if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'alta' ){

		$usuarioValidado  = validarCamposUsuario( $obj['usuario'] );
		$profesorValidado = validarCamposProfesor( $obj['profesor'] );

		if( $usuarioValidado && $profesorValidado ){

			$profesor = new Profesor();
			$profesor->rellenar( $usuarioValidado, $profesorValidado );

			$respuesta['idUsuario']  = $profesor->obtenerIdUsuario();
			$respuesta['idProfesor'] = $profesor->obtenerId();
			$respuesta['error']      = false;
		} else {

			$respuesta['error']            = true;
			$respuesta['descripcionError'] = 'Datos manipulados';
		}
	}

	
	echo json_encode( $respuesta );

?>