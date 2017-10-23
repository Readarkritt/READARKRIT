<?php

	require_once("./general/bbdd.php");
	require_once("./general/funciones.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Hash.php");
	require_once("./clases/LibroAnadido.php");

	// CONTROLADOR

	$obj       = $_POST;
	$respuesta = array();
	$libro     = array();

	if( $obj['opcion'] == 'libroAnadido' && $obj['accion'] == 'listar' ){

		$sql = 'select a.id_libro_anadido, l.portada, l.titulo, l.titulo_original, l.autor, l.ano, CASE WHEN l.anadido_por = 0 THEN "ARKRIT" ELSE concat(u.primer_apellido, " ", u.segundo_apellido, ", ", u.nombre) END as anadido_por, t.nombre as titulacion, p.nombre as pais, cl.nombre as categoria, a.posicion_ranking, a.media_num_usuarios, a.nivel_especializacion from libro l inner join libro_anadido a on l.id_libro = a.id_libro left join usuario u on l.anadido_por = u.id_usuario inner join titulacion t on l.id_titulacion = t.id_titulacion inner join pais p on a.id_pais = p.id_pais inner join categoria_libro cl on a.id_categoria = cl.id_categoria where l.f_baja is null';

		$respuesta['librosAnadidos'] = consulta( '', '', '', $sql);
		$respuesta['error']          = ($respuesta['librosAnadidos'] === false);
	}

	if( $obj['opcion'] == 'libroAnadido' && $obj['accion'] == 'alta' ){

	}

	if( $obj['opcion'] == 'libroAnadido' && $obj['accion'] == 'existe' ){

		$respuesta['existe'] = existeRegistro($obj['campo'], $obj['valor'], $obj['opcion']);
	}

	if( $obj['opcion'] == 'libroAnadido' && $obj['accion'] == 'modificar' ){

		$libro = array();
		$libro['idLibro'] 			= $obj['idLibro'];
		$libro['titulo'] 			= $obj['titulo'];
		$libro['tituloOriginal'] 	= $obj['tituloOriginal'];
		$libro['ano'] 				= $obj['ano'];
		$libro['autor'] 			= $obj['autor'];
		$libro['idTitulacion'] 		= $obj['idTitulacion'];
		$libro['anadidoPor'] 		= '';
		$libro['portadaAñadida']	= ($obj['portadaAñadida'] === 'true');

		$libroAnadido = array();
		$libroAnadido['idPais'] = $obj['idPais'];
		$libroAnadido['idCategoria'] = $obj['idCategoria'];
		$libroAnadido['posicionRanking'] = $obj['posicionRanking'];
		$libroAnadido['nivelEspecializacion'] = $obj['nivelEspecializacion'];
		$libroAnadido['idLibroAnadido'] = $obj['idLibroAnadido'];



		$libroValidado = validarCamposLibro($libro);
		$libroAnadidoValidado = validarCamposLibroAnadido($libroAnadido);
		$portadaValidada = (!$libro['portadaAñadida'] || ( $libro['portadaAñadida']&& isset($_FILES["portada"]))); 

		if($libroValidado && $libroAnadidoValidado && $portadaValidada){

			$libroAnadidoOriginal = new LibroAnadido();
			$libroAnadidoOriginal->cargar($libroAnadido['idLibroAnadido']);
			$libroOriginal = $libroAnadidoOriginal->obtenerLibro();

			if($libroOriginal->obtenerTitulo() != $libro['titulo']){
				$libroOriginal->cambiarTitulo($libro['titulo']);
			}
			if($libroOriginal->obtenerTituloOriginal() != $libro['tituloOriginal']){
				$libroOriginal->cambiarTituloOriginal($libro['tituloOriginal']);
			}
			if($libroOriginal->obtenerAutor() != $libro['autor']){
				$libroOriginal->cambiarAutor($libro['autor']);
			}

			if($libroOriginal->obtenerAno() != $libro['ano']){
				$libroOriginal->cambiarAno($libro['ano']);
			}

			if($libroAnadidoOriginal->obtenerIdPais() != $libroAnadido['idPais']){
				$libroAnadidoOriginal->cambiarIdPais($libroAnadido['idPais']);
			}

			if($libroOriginal->obtenerIdTitulacion() != $libro['idTitulacion']){
				$libroOriginal->cambiarIdTitulacion($libro['idTitulacion']);
			}

			if($libroAnadidoOriginal->obtenerIdCategoria() != $libroAnadido['idCategoria']){
				$libroAnadidoOriginal->cambiarIdCategoria($libroAnadido['idCategoria']);
			}

			if($libroAnadidoOriginal->obtenerNivelEspecializacion() != $libroAnadido['nivelEspecializacion']){
				$libroAnadidoOriginal->cambiarNivelEspecializacion($libroAnadido['nivelEspecializacion']);
			}

			if($libroAnadidoOriginal->obtenerPosicionRanking() != $libroAnadido['posicionRanking']){
				$libroAnadidoOriginal->cambiarPosicionRanking($libroAnadido['posicionRanking']);
			}

			if($libro['portadaAñadida']){
				$folder = '../img/portadasLibros/';

				//Eliminar imagen anterior
				$ruta = $folder.$libroOriginal->obtenerPortada();
				if(file_exists($ruta))
					unlink($ruta);

				//Añadir nueva imagen
				$nuevoNombre = generarFechaMicrosegundos() . '.' . obtenerExtension($_FILES['portada']['name']);
				move_uploaded_file($_FILES["portada"]["tmp_name"], $folder.$nuevoNombre);
				$libroOriginal->cambiarPortada($nuevoNombre);

			}



			$sql = 'select a.id_libro_anadido, l.portada, l.titulo, l.titulo_original, l.autor, l.ano, CASE WHEN l.anadido_por = 0 THEN "ARKRIT" ELSE concat(u.primer_apellido, " ", u.segundo_apellido, ", ", u.nombre) END as anadido_por, t.nombre as titulacion, p.nombre as pais, cl.nombre as categoria, a.posicion_ranking, a.media_num_usuarios, a.nivel_especializacion from libro l inner join libro_anadido a on l.id_libro = a.id_libro left join usuario u on l.anadido_por = u.id_usuario inner join titulacion t on l.id_titulacion = t.id_titulacion inner join pais p on a.id_pais = p.id_pais inner join categoria_libro cl on a.id_categoria = cl.id_categoria where l.id_libro = '.$libro['idLibro'];

			$respuesta['libro'] = consulta( '', '', '', $sql);

			$respuesta['error'] = false;
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Datos manipulados.';			
		}


	}

	if( $obj['opcion'] == 'libroAnadido' && $obj['accion'] == 'eliminar' ){

		$obj['idLibroAnadido'] = (int) $obj['idLibroAnadido'];

		$libro = new LibroAnadido();
		$libro->cargar( $obj['idLibroAnadido'] );

		$respuesta['error'] = !$libro->eliminar();
	}

	if( $obj['opcion'] == 'libroAnadido' && $obj['accion'] == 'obtener' ){
		$respuesta['libroAnadido']	= consulta('*',"libro_anadido","id_libro_anadido = ".$obj['idLibroAnadido']);
		$respuesta['libro']			= consulta('*',"libro","id_Libro = ".$respuesta['libroAnadido']['ID_LIBRO']);
		$respuesta['error'] 		= false;
	}

	echo json_encode( $respuesta );

?>