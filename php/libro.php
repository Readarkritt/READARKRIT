<?php

	require_once("./general/bbdd.php");
	require_once("./general/funciones.php");
	require_once("./general/token.php");
	require_once("./clases/LibroAnadido.php");

	$obj       = $_POST;
	$respuesta = array();


	function asignarPortadaALibro( $arrLibros ){

		$libroProblematico   = true;
		$librosProblematicos = array();
		$rutaTmp 			 = 'C:/xampp/htdocs/READARKRIT/img/tmp/';
		$rutaDefinitiva 	 = 'C:/xampp/htdocs/READARKRIT/img/portadasLibros/';

		// 1) Asignamos el nombre de la portada que hay en el servidor al $arrLibros
		for( $i=0; $i < count($arrLibros); $i++ ) { 

			$ficheroTmp = $rutaTmp . $arrLibros[$i]['PORTADA'];
			
			if( file_exists( $ficheroTmp ) ){

				$nuevoNombre = generarFechaMicrosegundos() . '.' . obtenerExtension($arrLibros[$i]['PORTADA']);

				rename($ficheroTmp, $rutaDefinitiva.$nuevoNombre);

				$arrLibros[$i]['PORTADA'] = $nuevoNombre;

				// 1.2) Comprobamos que los datos que no requieren de ID son válidos
				if( $arrLibros[$i]['TITULO'] != '' && $arrLibros[$i]['TITULO_ORIGINAL'] != '' && $arrLibros[$i]['AUTOR'] != '' && ( (int)$arrLibros[$i]['ANO'] >= 1900 && (int)$arrLibros[$i]['ANO'] <= (int) date('Y') ) ){

					// 2) Comprobar quién lo ha subido

					if( $arrLibros[$i]['ANADIDO_POR'] == 'ARKRIT' ){

						$arrLibros[$i]['ANADIDO_POR'] = 0;

						// 3) Obtener el id de la titulación para la que va a ayudar el libro
						$condicion = 'nombre LIKE "' . $arrLibros[$i]['TITULACION'] . '"';
						$arrLibros[$i]['ID_TITULACION'] = consulta('id_titulacion', 'titulacion', $condicion);

						if( $arrLibros[$i]['ID_TITULACION'] ){

							// 4) Obtener el id del país
							$condicion = 'nombre LIKE "' . $arrLibros[$i]['PAIS'] . '"';
							$arrLibros[$i]['ID_PAIS'] = consulta('id_pais', 'pais', $condicion);

							if( $arrLibros[$i]['ID_PAIS'] ){

								// 5) Obtener el id de la categoría
								$condicion = 'nombre LIKE "' . $arrLibros[$i]['CATEGORIA'] . '"';
								$arrLibros[$i]['ID_CATEGORIA'] = consulta('id_categoria', 'categoria_libro', $condicion);

								if( $arrLibros[$i]['ID_CATEGORIA'] ){

									// 6) Posición en el ranking, se consulta si algún libro está en ese puesto, y si no es así se deja como está; si está ocupado se deja a 0 (para que lo asigne más tarde y manualmente)
									if( existeRegistro( 'posicion_ranking', 'POSICION_RANKING', 'libro_anadido' ) )
										$arrLibros[$i]['POSICION_RANKING'] = 0;
									
									// 7) MEDIA_NUM_USUARIOS se pone a 0
									$arrLibros[$i]['MEDIA_NUM_USUARIOS'] = 0;

									// 8) NIVEL_ESPECIALIZACION se comprueba si es básico o especialidad
									if( $arrLibros[$i]['NIVEL_ESPECIALIZACION'] == 'Básico' || $arrLibros[$i]['NIVEL_ESPECIALIZACION'] == 'Especialidad' )
										$libroProblematico = false;
								}
							}
						}
					} 
				}

			}

			// 9) Compruebo que el libro no haya dado ningún problema y lo inserto en la BBDD
			if( $libroProblematico ){

				unlink($rutaDefinitiva.$nuevoNombre);	// borro la portada del libro del servidor
				array_push($librosProblematicos, $i+2);
			} else {

				// 10) Separamos los valores de libro y libroAnadido

				$valoresLibro['idLibro'] 		 = '';
				$valoresLibro['portada'] 		 = $arrLibros[$i]['PORTADA'];
				$valoresLibro['titulo'] 		 = $arrLibros[$i]['TITULO'];
				$valoresLibro['tituloOriginal']  = $arrLibros[$i]['TITULO_ORIGINAL'];
				$valoresLibro['autor'] 			 = $arrLibros[$i]['AUTOR'];
				$valoresLibro['ano'] 			 = $arrLibros[$i]['ANO'];
				$valoresLibro['anadidoPor'] 	 = $arrLibros[$i]['ANADIDO_POR'];
				$valoresLibro['idTitulacion'] 	 = $arrLibros[$i]['ID_TITULACION'];
				$valoresLibro['fBaja']			 = null;

				$valoresLibroAnadido['idLibroAnadido'] 	  	 = '';
				$valoresLibroAnadido['idLibro'] 			 = '';
				$valoresLibroAnadido['idPais'] 			  	 = $arrLibros[$i]['ID_PAIS'];
				$valoresLibroAnadido['idCategoria'] 		 = $arrLibros[$i]['ID_CATEGORIA'];
				$valoresLibroAnadido['posicionRanking'] 	 = $arrLibros[$i]['POSICION_RANKING'];
				$valoresLibroAnadido['mediaNumUsuarios']     = $arrLibros[$i]['MEDIA_NUM_USUARIOS'];
				$valoresLibroAnadido['nivelEspecializacion'] = $arrLibros[$i]['NIVEL_ESPECIALIZACION'];

				// 12) Creamos el libro

				$libroAnadido = new LibroAnadido();
				$libroAnadido->rellenar($valoresLibro, $valoresLibroAnadido);

				// 13) establezco de nuevo el valor de libro problemático
				$libroProblematico = true;
			}

		} // fin del for


		// Devolvemos los elementos que han dado problemas
		return $librosProblematicos;
	}



	if( $obj['opcion'] == 'libro' && $obj['accion'] == 'procesarLibrosExcel' ){

		if( isset($_FILES['ficheroExcel'], $_FILES['ficheroComprimido']) ){

			if( extensionValida( $_FILES['ficheroExcel']['name'], 'excel' ) && extensionValida( $_FILES['ficheroComprimido']['name'], 'zip' ) ){

				$arrLibros = excelTOarray( $_FILES['ficheroExcel']['tmp_name'] );
				descomprimirZIP( $_FILES['ficheroComprimido']['tmp_name'] );

				$librosProblematicos = asignarPortadaALibro( $arrLibros );

				if( count($librosProblematicos) == 0 ){

					$respuesta['error'] = false;
				} else {

					$respuesta['error'] = true;
					$respuesta['descripcionError'] = 'No se han podido insertar los libros situados en las filas ' . implode(', ', $librosProblematicos) . ' del fichero Excel, por favor, revise los campos.';
				}

				// borrar archivos excel y zip de la ruta temporal
				unlink( $_FILES['ficheroExcel']['tmp_name'] );
				unlink( $_FILES['ficheroComprimido']['tmp_name'] );

			} else {

				$respuesta['error'] = true;
				$respuesta['descripcionError'] = 'Los ficheros subidos sólo pueden ser .xls y .xlsx para los libros, y .zip para las portadas.';
			}

		} else {

			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Los ficheros subidos sólo pueden ser .xls, .xlsx o .zip.';
		}
	}

	echo json_encode( $respuesta );

?>