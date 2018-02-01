<?php

	require_once("./general/bbdd.php");
	require_once("./general/funciones.php");
	require_once("./general/token.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Libro.php");
	require_once("./clases/LibroAnadido.php");

	$obj       = $_POST;
	$respuesta = array();


	function asignarPortadaALibro( $arrLibros ){

		$libroProblematico = true;
		$libroRepetido     = false;
		$resultado		   = array();		// Array que contiene las filas del excel que están repetidas en la BBDD o han sido problemáticas
		$rutaTmp 		   = 'C:/xampp/htdocs/READARKRIT/img/tmp/';
		$rutaDefinitiva    = 'C:/xampp/htdocs/READARKRIT/img/portadasLibros/';

		// 0) Creamos la estructura del array $resultado

		$resultado['numLibrosAInsertar']  = count($arrLibros);
		$resultado['numLibrosInsertados'] = 0;
		$resultado['librosRepetidos']     = array();
		$resultado['librosProblematicos'] = array();

		// 1) Asignamos el nombre de la portada que hay en el servidor al $arrLibros
		for( $i=0; $i < count($arrLibros); $i++ ) { 

			$ficheroTmp = $rutaTmp . $arrLibros[$i]['PORTADA'];
	
			if( file_exists( $ficheroTmp ) ){

				$nuevoNombre = generarFechaMicrosegundos() . '.' . obtenerExtension($arrLibros[$i]['PORTADA']);

				rename($ficheroTmp, $rutaDefinitiva.$nuevoNombre);

				$arrLibros[$i]['PORTADA'] = $nuevoNombre;

				// 1.2) Comprobamos que los datos que no requieren de ID son válidos
				if( $arrLibros[$i]['TITULO'] != '' && $arrLibros[$i]['TITULO_ORIGINAL'] != '' && !existeRegistro('titulo', $arrLibros[$i]['TITULO'], 'libro') && !existeRegistro('titulo_original', $arrLibros[$i]['TITULO_ORIGINAL'], 'libro') && $arrLibros[$i]['AUTOR'] != '' && ( (int)$arrLibros[$i]['ANO'] >= 1500 && (int)$arrLibros[$i]['ANO'] <= (int) date('Y') ) ){

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
								$condicion = 'nombre LIKE "%' . $arrLibros[$i]['CATEGORIA'] . '%"';
								$arrLibros[$i]['ID_CATEGORIA'] = consulta('id_categoria', 'categoria_libro', $condicion);

								if( $arrLibros[$i]['ID_CATEGORIA'] ){

									// 6) Posición en el ranking, se consulta si algún libro está en ese puesto, y si no es así se deja como está; si está ocupado se deja a 0 (para que lo asigne más tarde y manualmente)
									if( existeRegistro( 'posicion_ranking', 'POSICION_RANKING', 'libro_anadido' ) )
										$arrLibros[$i]['POSICION_RANKING'] = 0;
									
									// 7) MEDIA_NUM_USUARIOS se pone a 0
									$arrLibros[$i]['MEDIA_NUM_USUARIOS'] = 0;

									// 8) NIVEL_ESPECIALIZACION se comprueba si es básico o especialidad
									if( $arrLibros[$i]['NIVEL_ESPECIALIZACION'] == 'Básico' || $arrLibros[$i]['NIVEL_ESPECIALIZACION'] == 'Especialidad' ){

										// 9) RESEÑA se comprueba si no se sale del límite
										if( (strlen($arrLibros[$i]['RESENA']) <= 2000 && preg_match('/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ ,.;_\s]+$/', $arrLibros[$i]['RESENA'])) || $arrLibros[$i]['RESENA'] == '' )
											$libroProblematico = false;
									}
								}
							}
						}
					} 
				} else
					$libroRepetido = true;
			}

			// 9) Compruebo que el libro no haya dado ningún problema y lo inserto en la BBDD

			if( $libroRepetido || $libroProblematico ){

				unlink($rutaDefinitiva.$nuevoNombre);	// borro la portada del libro del servidor

				if( $libroRepetido ){

					array_push($resultado['librosRepetidos'], $i+2);
					$libroRepetido = false;
				} else
					array_push($resultado['librosProblematicos'], $i+2);

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
				$valoresLibroAnadido['resena'] 				 = $arrLibros[$i]['RESENA'];

				// 12) Creamos el libro

				$libroAnadido = new LibroAnadido();
				$libroAnadido->rellenar($valoresLibro, $valoresLibroAnadido);

				$resultado['numLibrosInsertados']++;

				// 13) establezco de nuevo el valor de libro problemático
				$libroProblematico = true;
			}

		} // fin del for

		// Borro todo el contenido de la ruta tmp
		borrarDirectorio($rutaTmp, true);

		// Devolvemos los elementos que han dado problemas
		return $resultado;
	}



	if( $obj['opcion'] == 'libro' && $obj['accion'] == 'procesarLibrosExcel' ){

		if(tienePermiso('admin')){

			if( isset($_FILES['ficheroExcel'], $_FILES['ficheroComprimido']) ){

				if( extensionValida( $_FILES['ficheroExcel']['name'], 'excel' ) && extensionValida( $_FILES['ficheroComprimido']['name'], 'zip' ) ){

					$arrLibros = excelTOarray( $_FILES['ficheroExcel']['tmp_name'] );
					descomprimirZIP( $_FILES['ficheroComprimido']['tmp_name'], '', 'img' );

					$resultado = asignarPortadaALibro( $arrLibros );

					if( $resultado['numLibrosAInsertar'] == $resultado['numLibrosInsertados'] )
						$respuesta['error'] = false;
					else {

						$respuesta['descripcionError'] = 'Se han encontrado algunos problemas en la subida: </br></br><ul>';

						if( count($resultado['librosRepetidos']) != 0 )
							$respuesta['descripcionError'] .= '<li>Los libros situados en las filas ' . implode(', ', $resultado['librosRepetidos']) . ' del fichero Excel, ya se encuentran registrados.</li>';

						if( count($resultado['librosProblematicos']) != 0 )
							$respuesta['descripcionError'] .= '<li>No se han podido insertar los libros situados en las filas ' . implode(', ', $resultado['librosProblematicos']) . ' del fichero Excel, por favor, revise los campos.</li>';

						$respuesta['descripcionError'] .= '</ul>';

						$respuesta['error'] = true;
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
				$respuesta['descripcionError'] = 'Es necesario un fichero .xls, .xlsx, y otro .zip.';
			}
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	}

	else if( $obj['opcion'] == 'libro' && $obj['accion'] == 'alta' ){
		if(tienePermiso('admin')){

	//		print_r($obj);

			//Validación
			$libro = array();
			$libroAnadido = array();

			$libro['idLibro'] 			= '';
			$libro['portada']			= '';
			$libro['titulo'] 			= $obj['titulo'];
			$libro['tituloOriginal'] 	= $obj['tituloOriginal'];
			$libro['autor'] 			= $obj['autor'];
			$libro['ano'] 				= $obj['ano'];
			$libro['anadidoPor'] 		= 0;
			$libro['idTitulacion'] 		= $obj['idTitulacion'];
			$libro['fBaja']				= null;

			$libroAnadido['idLibroAnadido']			= '';
			$libroAnadido['idLibro'] 				= '';
			$libroAnadido['idPais']					= $obj['idPais'];
			$libroAnadido['idCategoria']			= $obj['idCategoria'];
			$libroAnadido['posicionRanking']		= $obj['posicionRanking'];
			$libroAnadido['mediaNumUsuarios']		= 0;
			$libroAnadido['nivelEspecializacion']	= $obj['nivelEspecializacion'];
			$libroAnadido['resena']					= $obj['resena'];

			if(!existeRegistro('titulo', $libro['titulo'], 'libro') || !existeRegistro('titulo_original',$libro['tituloOriginal'], 'libro')){
				$libroValidado = validarCamposLibro($libro);
				$libroAnadidoValidado = validarCamposLibroAnadido($libroAnadido,true);
				$portadaVacia = !isset($_FILES["portada"]);


				if($libroValidado && $libroAnadidoValidado && !$portadaVacia){

					//Inserción imagen
					$folder = '../img/portadasLibros/';
					$nuevoNombre = generarFechaMicrosegundos() . '.' . obtenerExtension($_FILES['portada']['name']);
					move_uploaded_file($_FILES["portada"]["tmp_name"], $folder.$nuevoNombre);

					$libro['portada'] = $nuevoNombre;
					//Inserción libro
					$libroAnadidoObj = new LibroAnadido();
					$libroAnadidoObj->rellenar($libro, $libroAnadido);
					$sql = 'select l.id_libro, a.id_libro_anadido, a.id_pais, a.id_categoria, l.portada, l.titulo, l.titulo_original, l.id_titulacion, l.autor, l.ano, CASE WHEN l.anadido_por = 0 THEN "ARKRIT" ELSE concat(u.primer_apellido, " ", u.segundo_apellido, ", ", u.nombre) END as anadido_por, t.nombre as titulacion, p.nombre as pais, cl.nombre as categoria, a.posicion_ranking, a.media_num_usuarios, a.nivel_especializacion, a.resena from libro l inner join libro_anadido a on l.id_libro = a.id_libro left join usuario u on l.anadido_por = u.id_usuario inner join titulacion t on l.id_titulacion = t.id_titulacion inner join pais p on a.id_pais = p.id_pais inner join categoria_libro cl on a.id_categoria = cl.id_categoria where l.id_libro = '.$libroAnadidoObj->obtenerIdLibro();

					$respuesta['libro'] = consulta( '', '', '', $sql);
					$respuesta['error'] = false;
				} else{
					$respuesta['error'] = true;
					$respuesta['descripcionError'] = 'Datos manipulados.';
				}
			} else{
				$respuesta['error'] = true;
				$respuesta['descripcionError'] = 'Datos manipulados.';
			}
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}

	}

	else if( $obj['opcion'] == 'libro' && $obj['accion'] == 'obtenerPosicionesRanking'){
		if(tienePermiso('admin')){
			
			$sql = 'SELECT count(l.id_libro) FROM libro l INNER JOIN libro_anadido a on l.id_libro = a.id_libro  WHERE f_baja is null';

			$numPosiciones = consulta('','','',$sql);

			if($numPosiciones != null){
				$respuesta['error'] = false;
				$respuesta['posicionesRanking'] = $numPosiciones;
			} else{
				$respuesta['error'] = true;			
			}

		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	}

	else if( $obj['opcion'] == 'libro' && $obj['accion'] == 'listar'){
		$sql = 'select l.nombre, l.primer_apellido, u.segundo_apellido, u.nombre_usuario, u.correo, p.es_admin, p.evitar_notificacion, p.id_profesor from usuario u inner join profesor p on u.id_usuario = p.id_usuario where f_baja is null';

		$respuesta['profesores'] = consulta( '', '', '', $sql);
		$respuesta['error']      = ($respuesta['profesores'] === false);
	} else 	if( $obj['opcion'] == 'libro' && $obj['accion'] == 'existe' ){

		$respuesta['existe'] = existeRegistro($obj['campo'], $obj['valor'], $obj['opcion']);
	}

	echo json_encode( $respuesta );

?>