<?php

	require_once("./general/bbdd.php");
	require_once("./general/token.php");

	define("TABLA_SQL", "permisos");


	function profesorTerminado($idUsuario){

		return true;
	}

	function autorizado($url){

		
		$autorizado = false;
		$rol = null;


		//Recuperar el token
		$token = recuperarToken();
		if(is_null($token)){
			include('./general/autoToken.php');
			exit();
		} else{
			//Comprobar persmisos del fichero
			$campos = "rol";
			$condicion = "RUTA_FICHERO = '".$url."'";
			$permisoPagina = consulta($campos, TABLA_SQL, $condicion);

			//Comprobar token
			if(comprobarToken($token)){
				$rol = getRol($token);
				if(!is_null($rol)){
					//Si el profesor no está terminado, redireccionar
					if($rol == 'profesor' && !profesorTerminado(recuperarDeToken('id'))){
						//TODO: INCLUIR HEADER DE RESPUESTA
						include('./');
						exit();
					}else{				
						
						if($permisoPagina == 'admin' && $rol == 'admin'){
							$autorizado = true;
						} else if($permisoPagina == 'profesor' && ( $rol== 'admin' || $rol=='profesor')){
							$autorizado = true;
						} else if($permisoPagina == 'alumno' || $permisoPagina == 'visitante'){
							$autorizado = true;
						}
					}
				}
			} else if($permisoPagina == 'visitante'){
					$autorizado = true;
			}	
		}

		return $autorizado;
	}


	//COMPROBAR QUE EL FICHERO REQUERIDO EXISTE Y SE TIENEN PERMISOS
	$url = ($_SERVER['REQUEST_URI']);
	if(!file_exists('../..'.$url)){
		$url = '/READARKRIT/html/menuApp/menuApp.html';
	}

	/*if(autorizado($url)){
		include("../..".$url);
	} else{
		include('../html/error/error.html');

	}*/

	include('../..'.$url);