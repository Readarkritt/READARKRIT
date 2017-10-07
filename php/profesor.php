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

		$profesorPeticion = $obj['profesor'];
		$usuarioPeticion = $profesorPeticion['usuario'];
		
		$respuesta['errorContrasena'] = false;
		$respuesta['errorCorreo'] = false;

		$id = recuperarDeToken('id');
		if($id != null){
			$profesor = new Profesor();
			$profesor->cargar($profesorPeticion['idProfesor']);
			$usuario = $profesor->obtenerUsuario();

			if($usuarioPeticion['contrasena'] != ''){
				if(validarContrasena($usuarioPeticion['contrasena'], $usuarioPeticion['contrasenaRepetida'])){
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
	}
	
	echo json_encode( $respuesta );

?>