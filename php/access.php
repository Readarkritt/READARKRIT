<?php

	require_once("./general/bbdd.php");
	require_once("./general/token.php");

	define("TABLA_SQL", "permisos");

	function comprobarPermisos($permisoPagina){
		$autorizado = false;
		$rol = null;

		//COMPROBAR QUE EXISTE TOKEN Y ES CORRECTO EN HEADER O POST. EN CASO DE NO EXISTIR, BUSCARLO EN EL CLIENTE.
		$token = recuperarToken();
		if(is_null($token)){
			include('./general/autoToken.php');
			exit();
		} else if(comprobarToken($token)){
			$rol = getRol($token);
			if(!is_null($rol)){
				if($permisoPagina == 'admin' && $rol == 'admin'){
					$autorizado = true;
				} else if($permisoPagina == 'profesor' && ( $rol== 'admin' || $rol=='profesor')){
					$autorizado = true;
				} else if($permisoPagina == 'alumno'){
					$autorizado = true;
				}
			}
		}


		//SI SE ENCONTRÓ UN TOKEN VÁLIDO, COMPROBAR QUE TIENE EL PERMISO REQUERIDO
		

		return $autorizado;
	}

	function autorizado($url){

		//COMPROBAR PERMISOS REQUERIDOS
		$campos = "rol";
		$condicion = "RUTA_FICHERO = '".$url."'";
		$permisoPagina = consulta($campos, TABLA_SQL, $condicion);

		//COMPROBAR QUE SE TIENEN PERMISOS
		if(!empty($permisoPagina)){
			if($permisoPagina == 'visitante'){
				$autorizado = true;
			} else{
				$autorizado = comprobarPermisos($permisoPagina);
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
	
	require('../..'.$url);	