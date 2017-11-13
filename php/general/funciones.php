<?php

	function generarFechaActual(){

		return date('d/m/Y');
	}

	function ordenarArrayMultidimensional($array, $key_array, $order=SORT_ASC){

	    $new_array = array();
	    $sortable_array = array();

	    if (count($array) > 0) {
	        foreach ($array as $k => $v) {
	            if (is_array($v)) {
	                foreach ($v as $k2 => $v2) {
	                    if ($k2 == $key_array) {
	                        $sortable_array[$k] = $v2;
	                    }
	                }
	            } else {
	                $sortable_array[$k] = $v;
	            }
	        }

	        switch ($order) {
	            case SORT_ASC:
	                asort($sortable_array);
	                break;
	            case SORT_DESC:
	                arsort($sortable_array);
	                break;
	        }

	        foreach ($sortable_array as $k => $v) {
	            $new_array[$k] = $array[$k];
	        }
	    }

	    return $new_array;
	}

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


	function contrasenaValida($contrasena, $contrasenaRepetida){
		$valida = true;
		if( $contrasena == '' || $contrasena != $contrasenaRepetida || $contrasena > 20 || !preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/', $contrasena) )
			$valida = false;
		return $valida;

	}

	function validarCampoTexto( $cadena, $longitudMax ){

		$cadena      = (string) $cadena;
		$longitudMax = (int) $longitudMax;

		if( $longitudMax > 0 ){

			if( $cadena == '' || strlen($cadena) > $longitudMax || !preg_match('/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/', $cadena) )
				return false;
			else
				return $cadena;

		} else
			return false;

	}


	function validarCorreo( $correo = '' ){

		if( $correo == '' || strlen($correo) > 50 || !preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $correo) || existeRegistro( 'correo', $correo, 'usuario') )
			return false;
		else
			return true;
	}


	function validarTextArea( $texto, $longitudMax ){

		$texto       = (string) $texto;
		$longitudMax = (int) $longitudMax;

		if( $longitudMax > 0 ){

			if( $texto == '' || strlen($texto) > $longitudMax || !preg_match('/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ _\s]+$/', $texto) )
				return false;
			else
				return $texto;

		} else
			return false;
	}


	function excelTOarray( $rutaExcel ){

		require_once "./clases/PHPExcel.php";
		
		$dataArr = array();

		$objPHPExcel = PHPExcel_IOFactory::load($rutaExcel);

	    foreach( $objPHPExcel->getWorksheetIterator() as $worksheet ){

		    $highestRow    = $worksheet->getHighestRow();
		    $highestColumn = PHPExcel_Cell::columnIndexFromString( $worksheet->getHighestColumn() ) - 1;

		    for( $i = 0; $i < $highestColumn; $i++ ){

		    	$cell = $worksheet->getCellByColumnAndRow($i, 1);

		    	$headerColumn[$i] = $cell->getValue();
		    }

		    $i = 0;

		    for( $row = 2; $row <= $highestRow; $row++ ){

		        for( $col = 0; $col < $highestColumn; $col++ ){

		            $cell = $worksheet->getCellByColumnAndRow($col, $row);

		            $dataArr[$i][ $headerColumn[$col] ] = $cell->getValue();
		        }

		        $i++;
		    }

		}

		return $dataArr;
	}


	function descomprimirZIP( $rutaZIP, $rutaDestino = '' ){

		if( $rutaDestino == '' )
			$rutaDestino = '../img/tmp';

		$zip = new ZipArchive;

		if( $zip->open($rutaZIP) === TRUE ){

		    $zip->extractTo( $rutaDestino );
		    $zip->close();

		    return true;

		} else
		    return false;

	}


	function obtenerExtension( $fichero ){

		$tmp = explode('.', $fichero);

		return end($tmp);
	}


	function extensionValida( $fichero, $extension ){

		/*	EXTENSIONES POSIBLES:
				- rar
				- excel --> xls, xlsx
				- zip
				- img --> jpg
		*/

		$formatosExcel    = array('xls', 'xlsx');
		$formatosImagenes = array('jpg', 'png', 'pneg');

		$extensionObtenida = obtenerExtension( $fichero );

		switch ($extension) {
			case 'rar':
				return ($extension == $extensionObtenida);

			case 'excel':
				return in_array($extensionObtenida, $formatosExcel);

			case 'zip':
				return ($extension == $extensionObtenida);

			case 'img':
				return in_array($extensionObtenida, $formatosImagenes);
			
			default:
				return false;
		}

	}


	function generarFechaMicrosegundos(){

		return round(microtime(true) * 1000);
	}


	// busca en un array de objetos, el valor de la propiedad indicada y devuelve el índice en el que se encuentra
	function buscarValorEnArrObj( $arrObj, $propiedad, $valor ){

		$encontrado = -1;
		$i 		    = 0;

	    for ($i=0; $i < count($arrObj) && $encontrado == -1; $i++)
	    	if( $arrObj[$i][$propiedad] == $valor )
	    		$encontrado = $i;

	    return $encontrado;
	}

	// cuenta las dimensiones de un array
	function dimensionArray( $elemento ){

		// 0 -> es una variable
		// 1 o mas -> es un array/matriz

	    if(is_array($elemento))
	        return dimensionArray(reset($elemento)) + 1;
	    else
	        return 0;
	}

	function strTObool( $str ){

		$str = strtolower($str);

		if( strcmp($str, 'true') == 0 )
			return true;
		else
			return false;
	}
?>