<?php

	require_once("./general/bbdd.php");
	require_once("./general/funciones.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/LibroPropuesto.php");
	require_once("./clases/LibroAnadido.php");

	// CONTROLADOR

	$obj       = $_POST;
	$respuesta = array();
	$libro     = array();

	if( $obj['opcion'] == 'libroPropuesto' && $obj['accion'] == 'alta' ){
		if(tienePermiso('alumno')){
			//Validación
			$libro = array();
			$libroPropuesto = array();

			$libro['idLibro'] 			= '';
			$libro['portada']			= '';
			$libro['titulo'] 			= $obj['titulo'];
			$libro['tituloOriginal'] 	= $obj['tituloOriginal'];
			$libro['autor'] 			= $obj['autor'];
			$libro['ano'] 				= $obj['ano'];
			$libro['anadidoPor'] 		= 0;
			$libro['idTitulacion'] 		= $obj['idTitulacion'];
			$libro['fBaja']				= null;

			$libroPropuesto['idLibroPropuesto']	= '';
			$libroPropuesto['idLibro'] 			= '';
			$libroPropuesto['propuestoPara']	= $obj['propuestoPara'];
			$libroPropuesto['motivo']			= $obj['motivo'];

			if(!existeRegistro('titulo', $libro['titulo'], 'libro') || !existeRegistro('titulo_original',$libro['tituloOriginal'], 'libro')){
				$libroValidado = validarCamposLibro($libro);
				$libroPropuestoValidado = validarCamposLibroPropuesto($libroPropuesto);
				$portadaVacia = !isset($_FILES["portada"]);

				if($libroValidado && $libroPropuestoValidado && !$portadaVacia){

					//Inserción imagen
					$folder = '../img/portadasLibros/';
					$nuevoNombre = generarFechaMicrosegundos() . '.' . obtenerExtension($_FILES['portada']['name']);
					move_uploaded_file($_FILES["portada"]["tmp_name"], $folder.$nuevoNombre);

					$libroValidado['portada'] = $nuevoNombre;	
					//Inserción libro
					$libroPropuestoObj = new LibroPropuesto();
					$libroPropuestoObj->rellenar($libroValidado, $libroPropuestoValidado);

					$sql = 'select pr.id_libro_propuesto, count(cv.id_control_votacion) as num_votos, pr.id_libro, pr.propuesto_para, pr.motivo, l.portada, l.titulo, l.titulo_original, l.autor, l.ano, t.nombre as titulacion from libro_propuesto pr inner join libro l on pr.id_libro = l.id_libro left join titulacion t on l.id_titulacion = t.id_titulacion left join control_votacion cv on cv.id_libro_propuesto = pr.id_libro_propuesto where pr.id_libro_propuesto = '.$libroPropuestoObj->obtenerId();

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

	} else if( $obj['opcion'] == 'libroPropuesto' && $obj['accion'] == 'listar' ){

		$sql = 'select count(id_libro_propuesto) from libro_propuesto p inner join libro l on p.id_libro = l.id_libro where f_baja is null';
		
		if(consulta('','','',$sql)>0){
			$sql = 'select pr.id_libro_propuesto, pr.id_libro, pr.propuesto_para, pr.motivo, count(cv.ID_CONTROL_VOTACION) as num_votos, l.portada, l.titulo, l.titulo_original, l.autor, l.ano, t.nombre as titulacion from libro_propuesto pr inner join libro l on pr.id_libro = l.id_libro left join control_votacion cv on  pr.id_libro_propuesto = cv.id_libro_propuesto left join titulacion t on l.id_titulacion = t.id_titulacion where l.f_baja is null GROUP BY pr.ID_LIBRO_PROPUESTO';

			if(isset($obj['propuestoPara'])){
				$sql = $sql.' and pr.propuesto_para = "'.$obj['propuestoPara'].'"';
			}
			
			$respuesta['librosPropuestos'] = consulta('','','',$sql);
		} else{
			$respuesta['librosPropuestos'] = null;

		}
		$respuesta['error'] = false;
	} else if( $obj['opcion'] == 'libroPropuesto' && $obj['accion'] == 'eliminar' ){

		if(tienePermiso('profesor')){
			$obj['idLibroPropuesto'] = (int) $obj['idLibroPropuesto'];

			$libro = new LibroPropuesto();
			$libro->cargar( $obj['idLibroPropuesto'] );
			//var_dump($libro);

			$respuesta['error'] = !$libro->eliminar();
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	} else if( $obj['opcion'] == 'libroPropuesto' && $obj['accion'] == 'anadirColeccion' ){
		if(tienePermiso('profesor')){
			$libroAnadidoValidado = validarCamposLibroAnadido($obj['libroAnadido']);

			if($libroAnadidoValidado){
				$datos = array(
					'idLibroAnadido' 		=> '',
					'idLibro' 				=> $obj['libroAnadido']['idLibro'],
					'idPais' 				=> $obj['libroAnadido']['idPais'],
					'idCategoria' 			=> $obj['libroAnadido']['idCategoria'],
					'posicionRanking' 		=> $obj['libroAnadido']['posicionRanking'],
					'mediaNumUsuarios' 		=> 0,
					'nivelEspecializacion' 	=> $obj['libroAnadido']['nivelEspecializacion']
				);

				$libroAnadido = new LibroAnadido();
				$libroAnadido->anadirAColeccion($datos);

				$respuesta['error'] = false;
			} else{
				$respuesta['error'] = true;
				$respuesta['descripcionError'] ="Datos manipulados.";
			}
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	} else if( $obj['opcion'] == 'libroPropuesto' && $obj['accion'] == 'votar' ){
		if(tienePermiso('alumno')){
			$idUsuario = recuperarDeToken('id');
			$sql = "SELECT count(id_libro_propuesto) FROM control_votacion WHERE id_libro_propuesto = ".$obj['idLibroPropuesto']." AND id_usuario = ".$idUsuario;
			$votosRealizados = consulta("","","",$sql);

			if($votosRealizados == 0){
				insertar( "id_libro_propuesto,id_usuario", $obj['idLibroPropuesto'].",".$idUsuario, 'control_votacion');
				
				$respuesta['error'] = false;
			} else{
				$respuesta['error'] = true;
				$respuesta['descripcionError'] ="No se puede votar más de una vez una propuesta.";
			}
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	} else if( $obj['opcion'] == 'libroPropuesto' && $obj['accion'] == 'nominar' ){
		
		if(tienePermiso('alumno')){
			$nominacionValidada = validarCamposNominacion($obj['nominacion']);

			if($nominacionValidada){
				$correo = '';
				$asunto   = 'READARKRIT - Nueva nominación';

				$mensaje  = 'Se ha propuesto un nuevo libro para añadir a READARKRIT. Los datos son los siguientes:\n';
				$mensaje .= '';
				$mensaje .= 'Nombre de usuario: '.recuperarDeToken('nombreUsuario').'\n';
				$mensaje .= 'Fecha de la nominación: '.date('j-n-Y G:i').'\n';
				$mensaje .= 'Título del libro: '.$nominacionValidada['titulo'].'\n';
				$mensaje .= 'Título original del libro: '.$nominacionValidada['tituloOriginal'].'\n';
				$mensaje .= 'Autor: '.$nominacionValidada['autor'].'\n';
				$mensaje .= 'Año: '.$nominacionValidada['ano'].'\n';
				$mensaje .= 'Propuesto para: '.$nominacionValidada['propuestoPara'].'\n';
				$mensaje .= 'Motivo: '.$nominacionValidada['motivo'].'\n';
				$mensaje .= '\n';
				$mensaje .= 'Recuerde que para agregar la nominación a READARKRIT para que sea votada es necesario hacerlo manualmente.\n';

				mail($correo , $asunto, $mensaje);

				$respuesta['error'] = false;
			} else{
				$respuesta['error'] = true;
				$respuesta['descripcionError'] = 'Datos manipulados.';
			}
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}

	}

	echo json_encode( $respuesta );


