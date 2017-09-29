<?php

	function formatearFecha( $fecha, $formato ){

		// $formato: bbdd; -> la fecha se transforma para pasarla a la bbdd 
		// $formato: objeto; -> la fecha se transforma para que la tenga un objeto

		$partesFecha = array();
		$separador   = '';

		if( $fecha != '' && $formato != '' ){

			if( $formato == 'bbdd' ){

				$partesFecha = explode('/', $fecha);
				$separador   = '-'; // aaaa-mm-dd
			} else {

				$partesFecha = explode('-', $fecha);
				$separador   = '/'; // dd/mm/aaaa
			}

			return $partesFecha[2] . $separador . $partesFecha[1] . $separador . $partesFecha[0];
		}

		return false;
	}


	function fechaPermitida( $fecha = '' ){

		// comprueba si una fecha con formato dd/mm/aaaa existe en el calendario

		$currVal = $fecha;

	    if($currVal == '')
	        return false;

	    if( !preg_match('/^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/', $currVal) )
	    	return false;

	    $dtArray = explode('/', $currVal);
	    
	    $dtDay   = (int) $dtArray[0];
	    $dtMonth = (int) $dtArray[1];
	    $dtYear  = (int) $dtArray[2];        
	    
	    if ($dtDay < 1 || $dtDay> 31)
	        return false;
	    else if ($dtMonth < 1 || $dtMonth > 12)
	        return false;
	    else if (($dtMonth==4 || $dtMonth==6 || $dtMonth==9 || $dtMonth==11) && $dtDay ==31) 
	        return false;
	    else if ($dtMonth == 2) 
	    {
	        $isleap = ($dtYear % 4 == 0 && ($dtYear % 100 != 0 || $dtYear % 400 == 0));
	        if ($dtDay> 29 || ($dtDay ==29 && !$isleap)) 
	                return false;
	    }

	    return true;
	}


	function generarContrasenaAleatoria(){

		// Generar una contrseña única y de forma aleatoria

		return substr( md5(microtime()), 1, 10 );
	}


	function validarCorreo( $correo = '' ){

		if( $correo == '' || strlen($correo) > 50 || !preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $correo) || existeRegistro( 'correo', $correo, 'usuario') )
			return false;
		else
			return true;
	}

?>