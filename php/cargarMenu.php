<?php
	require_once("./token.php");


	$token = recuperarToken();
	$rol = null;

	//CÓDIGO BUENO

	/*if(!is_null($token)){
		if(comprobarToken($token)){
			$rol = getRol($token);
			if(!is_null($rol)){				
				$respuesta['error'] = false;

				if($rol == 'admin'){
					$respuesta['menu'] = file_get_contents('../html/menuApp/menus/menuAdmin.html');
				} else if($rol == 'profesor'){
					$respuesta['menu'] = file_get_contents('../html/menuApp/menus/menuProfesor.html');
				} else if($rol == 'alumno'){
					$respuesta['menu'] = file_get_contents('../html/menuApp/menus/menuAlumno.html');
				}

			} else{
				$respuesta['error'] = true;
			}
		}else{
			$respuesta['error'] = true;
		}
	} else{ //Si son visitantes
		$respuesta['menu'] = file_get_contents('../html/menuApp/menus/menuAlumno.html');
	}*/


	//CÓDIGO A ELIMINAR
	$respuesta['error'] = false;
	$rol = '';

	if($rol == 'admin'){
		$respuesta['menu'] = file_get_contents('../html/menuApp/menus/menuAdmin.html');
	} else if($rol == 'profesor'){
		$respuesta['menu'] = file_get_contents('../html/menuApp/menus/menuProfesor.html');
	} else {
		$respuesta['menu'] = file_get_contents('../html/menuApp/menus/menuAlumno.html');
	}

	//FIN CÓDIGO A ELIMINAR

	echo json_encode( $respuesta );