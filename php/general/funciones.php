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

	function fechaMenorQueActual ($fecha = '' ){
		$fechaComprobar = explode('/',$fecha);
		$fechaComprobar = $fechaComprobar[1].'/'.$fechaComprobar[0].'/'.$fechaComprobar[2].' 00:00:00';
		$fechaComprobar = strtotime($fechaComprobar);

		//La suma de 60*60 es para cambiar la fecha a GTM+1
		if($fechaComprobar <= time()+(60*60)){
			$menor = true;
		} else{
			$menor = false;
		}
		return $menor;
	}


	function generarContrasenaAleatoria(){

		// Generar una contrseña única y de forma aleatoria

		return substr( md5(microtime()), 1, 10 );
	}


	function validarCorreo( $correo = '', $comprobarExistente = true ){

		if( $correo == '' || strlen($correo) > 50 || !preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $correo) || ($comprobarExistente && existeRegistro( 'correo', $correo, 'usuario') ) )
			return false;
		else
			return true;
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
?>