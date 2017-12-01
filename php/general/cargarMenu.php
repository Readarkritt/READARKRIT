<?php
	require_once(dirname(__FILE__)."/token.php");


	$token = recuperarToken();
	$rol = null;
	$nombre = null;

	//CÓDIGO BUENO

	if(!is_null($token)){
		if(comprobarToken($token)){
			$rol = recuperarDeToken('rol');
			$nombre = recuperarDeToken('nombreUsuario');
			if(!is_null($rol) && !is_null($nombre)){				
				$respuesta['error'] = false;
				$respuesta['nombre'] = $nombre;				
				if($rol == 'admin'){
					$respuesta['menu'] = file_get_contents('../../html/menuApp/menus/menuAdmin.html');
				} else if($rol == 'profesor'){
					$respuesta['menu'] = file_get_contents('../../html/menuApp/menus/menuProfesor.html');
				} else if($rol == 'alumno'){
					$respuesta['menu'] = file_get_contents('../../html/menuApp/menus/menuAlumno.html');
				} else if($rol == 'profesorInvitado'){
					$respuesta['menu'] = '';
					$respuesta['nombre'] = 'Profesor invitado';
				} 
			} else{
				$respuesta['error'] = true;
			}
		}else{
			$respuesta['error'] = true;
		}
	} else{ //Si son visitantes
		$respuesta['menu'] = '';
		$respuesta['nombre'] = 'Visitante';
	}


	//CÓDIGO A ELIMINAR
	/*$respuesta['error'] = false;
	$rol = '';
	$nombre = 'Nombre';

	if($rol == 'admin'){
		$respuesta['menu'] = file_get_contents(dirname(__FILE__).'/../../html/menuApp/menus/menuAdmin.html');
	} else if($rol == 'profesor'){
		$respuesta['menu'] = file_get_contents(dirname(__FILE__).'/../../html/menuApp/menus/menuProfesor.html');
	} else if($rol == 'profesorInvitado'){
		$respuesta['menu'] = '';
	} else {
		$respuesta['menu'] = file_get_contents(dirname(__FILE__).'/../../html/menuApp/menus/menuAlumno.html');
	}
	$respuesta['nombre'] = $nombre;*/

	//FIN CÓDIGO A ELIMINAR

	echo json_encode( $respuesta );