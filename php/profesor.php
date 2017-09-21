<?php

	require_once("./bbdd.php");
	require_once("./token.php");
	require_once("./general/funciones.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Hash.php");
	require_once("./clases/Profesor.php");

	// CONTROLADOR

	$obj       = $_POST;
	$respuesta = array();
	$profesor  = array();

	if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'listar' ){

		$sql = 'select u.id_usuario, u.nombre, u.primer_apellido, u.segundo_apellido, u.nombre_usuario, u.correo, p.es_admin, p.evitar_notificacion from usuario u inner join profesor p on u.id_usuario = p.id_usuario';

		$respuesta['profesores'] = consulta( '', '', '', $sql);
		$respuesta['error']      = empty($respuesta['profesores']);
	}

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

	if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'existe' ){

		$respuesta['existe'] = existeRegistro($obj['campo'], $obj['valor'], $obj['opcion']);
	}

	if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'invitar' ){

		$obj['correo'] = (string) $obj['correo'];

		if( $obj['correo'] == '' || strlen($obj['correo']) > 50 || !preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $obj['correo']) || existeRegistro('correo', $obj['correo'], 'usuario') ){

			$respuesta['error']            = true;
			$respuesta['descripcionError'] = 'Datos manipulados';
		} else {

			$profesor = new Profesor();
			$profesor->invitar( $obj['correo'] );

			$respuesta['error'] = false;
		}
	}

	if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'recuperarConectado' ){
		$id = recuperarDeToken('id');

		if($id != null){
			$respuesta['tokenErroneo'] = false;
			$profesor = new Profesor();
			$profesor->cargar($id);
			$respuesta['profesor'] = $profesor->toArray();


		} else{
			$respuesta['tokenErroneo'] = true;
		}
	}

	echo json_encode( $respuesta );

?>