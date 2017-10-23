<?php
	require_once("./general/bbdd.php");
	require_once("./general/token.php");
	require_once("./general/funciones.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Hash.php");
	require_once("./clases/Profesor.php");

	// CONTROLADOR

	$obj       = $_POST;
	$respuesta = array();
	$profesor  = array();

	if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'listar' ){

		$sql = 'select u.nombre, u.primer_apellido, u.segundo_apellido, u.nombre_usuario, u.correo, p.es_admin, p.evitar_notificacion, p.id_profesor from usuario u inner join profesor p on u.id_usuario = p.id_usuario where f_baja is null';

		$respuesta['profesores'] = consulta( '', '', '', $sql);
		$respuesta['error']      = ($respuesta['profesores'] === false);
	}

	if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'eliminar' ){

		$obj['idProfesor'] = (int) $obj['idProfesor'];

		$profesor = new Profesor();
		$profesor->cargar( $obj['idProfesor'] );

		$respuesta['error'] = !$profesor->eliminar();
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
	else if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'modificar' ){
		$idProfesor = $obj['profesor']['idProfesor'];
		$profesorOriginal = new Profesor();
		$profesorOriginal->cargar($idProfesor);

		if($obj['usuario']['contrasena'] == '')
			$comprobarContrasena = false;
		else
			$comprobarContrasena = true;

		$profesorValidado = validarCamposProfesor($obj['profesor']);
		$usuarioValidado = validarCamposUsuario($obj['usuario'],false,$comprobarContrasena);

		//CAMBIAR DATOS
		if($profesorValidado && $usuarioValidado){

			if($profesorOriginal->obtenerUsuario()->obtenerNombre() != $usuarioValidado['nombre'])
				$profesorOriginal->obtenerUsuario()->cambiarNombre($usuarioValidado['nombre']);

			if($profesorOriginal->obtenerUsuario()->obtenerPrimerApellido() != $usuarioValidado['primerApellido'])
				$profesorOriginal->obtenerUsuario()->modificarPrimerApellido($usuarioValidado['primerApellido']);

			if($profesorOriginal->obtenerUsuario()->obtenerSegundoApellido() != $usuarioValidado['segundoApellido'])
				$profesorOriginal->obtenerUsuario()->modificarSegundoApellido($usuarioValidado['segundoApellido']);
			
			if($profesorOriginal->obtenerUsuario()->obtenerFNacimiento() != $usuarioValidado['fNacimiento'])				
				$profesorOriginal->obtenerUsuario()->modificarFNacimiento($usuarioValidado['fNacimiento'],'bbdd');

			if($profesorOriginal->obtenerUsuario()->obtenerCorreo() != $usuarioValidado['correo'])
				$profesorOriginal->obtenerUsuario()->modificarCorreo($usuarioValidado['correo']);

			if($profesorOriginal->obtenerUsuario()->obtenerNombreUsuario() != $usuarioValidado['nombreUsuario'])
				$profesorOriginal->obtenerUsuario()->modificarNombreUsuario($usuarioValidado['nombreUsuario']);

			if($obj['usuario']['contrasena'] != '')
				$profesorOriginal->obtenerUsuario()->cambiarContrasena($usuarioValidado['contrasena']);

			if($profesorOriginal->esAdmin() != $profesorValidado['esAdmin'])
				$profesorOriginal->cambiarEsAdmin($profesorValidado['esAdmin']);

			if($profesorOriginal->evitarNotificacion() != $profesorValidado['evitarNotificacion'])
				$profesorOriginal->cambiarEvitarNotificacion($profesorValidado['evitarNotificacion']);

			if($profesorOriginal->obtenerUsuario()->estaBloqueado() != $usuarioValidado['bloqueado'])
				$profesorOriginal->obtenerUsuario()->modificarBloqueado($usuarioValidado['bloqueado']);

			$sql = 'select u.nombre, u.primer_apellido, u.segundo_apellido, u.nombre_usuario, u.correo, p.es_admin, p.evitar_notificacion, p.id_profesor, u.bloqueado from usuario u inner join profesor p on u.id_usuario = p.id_usuario where u.id_usuario = '.$profesorValidado['idUsuario'];
			$respuesta['profesor'] = consulta( '', '', '', $sql);

			$respuesta['error'] = false;

		} else{			
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Datos manipulados.';		
		}


	}
	else if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'modificarConectado' ){

		$profesorPeticion = $obj['profesor'];
		$usuarioPeticion = $profesorPeticion['usuario'];

		if( $profesorPeticion['evitarNotificacion'] == 'true' || $profesorPeticion['evitarNotificacion'] === 1 )
			$profesorPeticion['evitarNotificacion'] = 1;
		else
			$profesorPeticion['evitarNotificacion'] = 0;
		
		$respuesta['errorContrasena'] = false;
		$respuesta['errorCorreo'] = false;
		$respuesta['errorEvitarNotificacion'] = false;

		$id = recuperarDeToken('id');
		if($id != null){
			$profesor = new Profesor();
			$profesor->cargar($profesorPeticion['idProfesor']);
			$usuario = $profesor->obtenerUsuario();

			if($usuarioPeticion['contrasena'] != ''){
				if($usuarioPeticion['contrasena'] == $usuarioPeticion['contrasenaRepetida']){
					$contra = new Hash($usuarioPeticion['contrasena']);
					$usuario->cambiarContrasena( $contra->get() );
				} else{			
					$respuesta['errorContrasena'] = true;
				}
			}

			if($usuario->obtenerCorreo() != $usuarioPeticion['correo']){
				if(validarCorreo($usuarioPeticion['correo'])){
					$usuario->modificarCorreo($usuarioPeticion['correo']);
				} else{
					$respuesta['errorCorreo'] = true;	
				}		
			}
			if(!$respuesta['errorCorreo']){
				$respuesta['correo'] = $usuarioPeticion['correo'];
			}

			if($profesor->evitarNotificacion() != $profesorPeticion['evitarNotificacion']){
				if( $profesorPeticion['evitarNotificacion'] == 0 || $profesorPeticion['evitarNotificacion'] == 1 ){ 
					$profesor->cambiarEvitarNotificacion($profesorPeticion['evitarNotificacion']);
				}
				else
					$respuesta['errorEvitarNotificacion'] = true;		
			}
			
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Token erróneo';
		}
	}

	else if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'terminar' ){

		$profesor = $obj['profesor'];
		$usuario = $obj['usuario'];


		$usuarioValidado  = validarCamposUsuario($usuario);
		$profesorValidado = validarCamposProfesor($profesor);

		if($usuarioValidado && $profesorValidado){
			unset($usuarioValidado['contrasenaRepetida']);
			$profesor = new Profesor();
			$profesor->rellenar($usuarioValidado, $profesorValidado);

			$tabla = 'invitacion';
			$condicion = 'correo = "'.$profesor->obtenerUsuario()->obtenerCorreo().'"';
			borrar($tabla,$condicion);

			//CREAR TOKEN
			$respuesta['token'] = generarToken($profesor->obtenerIdUsuario(), $profesor->obtenerUsuario()->obtenerNombre(), 'profesor', $profesor->obtenerUsuario()->obtenerCorreo());

			$respuesta['idUsuario'] 	= $profesor->obtenerIdUsuario();
			$respuesta['idProfesor']  	= $profesor->obtenerId();
			$respuesta['error']    		= false;
		} else{
			$respuesta['error']            = true;
			$respuesta['descripcionError'] = 'Datos manipulados';
		}
	}

	else if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'existe' ){

		$respuesta['existe'] = existeRegistro($obj['campo'], $obj['valor'], $obj['opcion']);
	}

	else if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'recuperarConectado' ){
		$id = recuperarDeToken('id');
		if($id != null){
			$idProfesor = consulta( 'id_Profesor', 'profesor', 'id_Usuario = '.$id );
			if($idProfesor != null){
				$respuesta['tokenErroneo'] = false;
				$profesor = new Profesor();
				$profesor->cargar($idProfesor);
				$respuesta['profesor'] = $profesor->toArray();
				$respuesta['profesor']['usuario']['contrasena'] = '';
			} else{
				$respuesta['tokenErroneo'] = true;
			}
		}else{
			$respuesta['tokenErroneo'] = true;
		}
	}

	else if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'recuperarInvitado' ){

		$correo = recuperarDeToken('correo');
		if($correo != null){
			$respuesta['tokenErroneo'] = false;
			$profesor = new Profesor();
			$respuesta['profesor'] = $profesor->toArray();
			$respuesta['profesor']['usuario']['correo'] = $correo;
			$respuesta['profesor']['usuario']['contrasena'] = '';
		}else{
			$respuesta['tokenErroneo'] = true;
		}
	} else if( $obj['opcion'] == 'profesor' && $obj['accion'] == 'obtener' ){

		$idProfesor = $obj['idProfesor'];
		$profesor = new Profesor();
		$profesor->cargar($idProfesor);

		$respuesta['profesor'] = $profesor->toArray();
		$respuesta['error'] = false;
	}
	
	echo json_encode( $respuesta );

?>