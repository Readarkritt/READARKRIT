<?php

	require_once(dirname(__FILE__).'/funciones.php');

	require_once(dirname(__FILE__).'/token.php');



	function tienePermiso($permiso){

		if($permiso == 'visitante'){

			return true;

		} else{

			$token = recuperarToken();

			if(is_null($token)){

				include('./general/autoToken.php');

				exit();

			} else{

				$rol = recuperarDeToken('rol');



				if($permiso == 'admin' && $rol == 'admin')

					return true;

				else if($permiso == 'profesor' && ($rol=='admin' || $rol=='profesor' || $rol =='profesorInvitado') )

					return true;

				else if($permiso == 'alumno')

					return true;

			}

		}

	}



	function generarRecomendacionesArkrit($idUsuario){



		$maxLibrosNivelIgual    = 3;

		$maxLibrosNivelInferior = 2;

		$recomendaciones        = array();





		$librosTitulacionInferiorBasico	  = array();

		$librosTitulacionInferiorAvanzado = array();

		$librosTitulacionIgualBasico	  = array();

		$librosTitulacionIgualAvanzado	  = array();





		$sql = 'select l.portada, l.titulo, l.id_titulacion, la.nivel_especializacion, la.id_categoria, la.posicion_ranking, rle.libro_leido from libro_anadido la inner join libro l on la.id_libro = l.id_libro left join rel_libro_estanteria rle on l.id_libro = rle.id_libro left join estanteria e on rle.id_estanteria = e.id_estanteria left join usuario u on e.creada_por = u.id_usuario left join alumno a on u.id_usuario = a.id_usuario where (rle.id_libro is null or rle.libro_leido = 0) and l.f_baja is null and (u.id_usuario is null or u.id_usuario = ' . $idUsuario . ')';



		$recomendaciones = consulta( '', '', '', $sql);



		if( !$recomendaciones === false ){



			// 1) Consulto la titulacion en la que está el usuario

			$idTitulacion = consulta( 'id_titulacion', 'alumno', 'id_usuario = ' . $idUsuario );



				// 1.1) Si es de Grado no tiene titulaciones por debajo de él

			if( $idTitulacion == 1 ){

				$maxLibrosNivelIgual    = 5;

				$maxLibrosNivelInferior = 0;

			}



			// 2) Hacemos cuatro arrays para poder diferenciar los libros que son de: 

			//		titulacion anterior y nivel básico,

			//		titulacion anterior y nivel avanzado,

			//		titulacion actual(igual) y nivel básico y

			// 		titulacion actual(igual) y nivel avanzado

			for( $i=0; $i<count( $recomendaciones ); $i++ ){



				if( $recomendaciones[$i]['id_titulacion'] <= $idTitulacion ){



					if( $recomendaciones[$i]['id_titulacion'] < $idTitulacion ){



						if( $recomendaciones[$i]['nivel_especializacion'] == 'Básico' )

							array_push($librosTitulacionInferiorBasico, $recomendaciones[$i]);

						else

							array_push($librosTitulacionInferiorAvanzado, $recomendaciones[$i]);

					} else {



						if( $recomendaciones[$i]['nivel_especializacion'] == 'Básico' )

							array_push($librosTitulacionIgualBasico, $recomendaciones[$i]);

						else

							array_push($librosTitulacionIgualAvanzado, $recomendaciones[$i]);

					}

				}



			}



			// 3) Ordenamos todos los arrays según la posición que tienen en el ranking los libros

			$numLibrosTitulacionInferiorBasico   = count($librosTitulacionInferiorBasico);

			$numLibrosTitulacionInferiorAvanzado = count($librosTitulacionInferiorAvanzado);

			$numLibrosTitulacionIgualBasico		 = count($librosTitulacionIgualBasico);

			$numLibrosTitulacionIgualAvanzado	 = count($librosTitulacionIgualAvanzado);



			if( $numLibrosTitulacionInferiorBasico != 0 )

				$librosTitulacionInferiorBasico = ordenarArrayMultidimensional($librosTitulacionInferiorBasico, 'posicion_ranking', SORT_ASC);



			elseif( $numLibrosTitulacionInferiorAvanzado != 0 )

				$librosTitulacionInferiorAvanzado = ordenarArrayMultidimensional($librosTitulacionInferiorAvanzado, 'posicion_ranking', SORT_ASC);



			elseif( $numLibrosTitulacionIgualBasico != 0 )

				$librosTitulacionIgualBasico = ordenarArrayMultidimensional($librosTitulacionIgualBasico, 'posicion_ranking', SORT_ASC);



			elseif( $numLibrosTitulacionIgualAvanzado != 0 )

				$librosTitulacionIgualAvanzado = ordenarArrayMultidimensional($librosTitulacionIgualAvanzado, 'posicion_ranking', SORT_ASC);		



			// 4) Generamos las 5 recomendaciones ARKRIT



			$recomendaciones = array();



				// 4.1) Generamos dos recomendaciones de titulaciones anteriores si no se está en grado



			for( $i=0; $i < $numLibrosTitulacionInferiorBasico && $i< $maxLibrosNivelInferior; $i++ )

				array_push($recomendaciones, $librosTitulacionInferiorBasico[$i]);



			if( $maxLibrosNivelInferior > count($recomendaciones) )

				for( $i=0; $i < $numLibrosTitulacionInferiorAvanzado && $i< $maxLibrosNivelInferior; $i++ )

					array_push($recomendaciones, $librosTitulacionInferiorAvanzado[$i]);



				// 4.2) Generamos tres o cinco (si se está en grado) recomendaciones de la titulacion actual



			for( $i=0; $i < $numLibrosTitulacionIgualBasico && $i< $maxLibrosNivelIgual; $i++ )

				array_push($recomendaciones, $librosTitulacionIgualBasico[$i]);

	

			if( ( $maxLibrosNivelInferior + $maxLibrosNivelIgual ) > count($recomendaciones) )

				for( $i=0; $i < $numLibrosTitulacionIgualAvanzado && $i< ($maxLibrosNivelInferior + $maxLibrosNivelIgual); $i++ )

					array_push($recomendaciones, $librosTitulacionIgualAvanzado[$i]);



		}



		return $recomendaciones;

	}



	function validarCamposResena($resena){

		//CASTEO

		$resena['nota']				= (int) $resena['nota'];

		$resena['comentario']		= (string) $resena['comentario'];

		$resena['idLibro']			= (int) $resena['idLibro'];

		$resena['idUsuario']		= (int) $resena['idUsuario'];

		$resena['fAlta']			= (string) $resena['fAlta'];



		//VALIDACION



		$sql = "SELECT COUNT(id_resena) FROM resena WHERE id_libro = ".$resena['idLibro']." AND id_usuario = ".$resena['idUsuario'];



		if(consulta('','','',$sql) != 0)

			return false;



		if($resena['nota']<0 || $resena['nota']>10)

			return false;



		if($resena['comentario']<0 || $resena['nota']>10)

			return false;



		if( $resena['idLibro'] <= 0 || !existeRegistro('id_libro', $resena['idLibro'], 'libro'))

			return false;



		return $resena;

	}





	function validarCamposUsuario( $usuario , $comprobarExistente = false, $comprobarContrasena = true, $comprobarBloqueado = false){



		// CASTEO

		$usuario['nombre'] 			= (string) $usuario['nombre'];

	  	$usuario['primerApellido'] 	= (string) $usuario['primerApellido'];

	  	$usuario['segundoApellido'] = (string) $usuario['segundoApellido'];

	  	$usuario['fNacimiento'] 	= (string) $usuario['fNacimiento'];

	  	$usuario['correo'] 			= (string) $usuario['correo'];

	  	$usuario['nombreUsuario'] 	= (string) $usuario['nombreUsuario'];

	  	$usuario['contrasena'] 		= (string) $usuario['contrasena'];

	  	$usuario['fBaja']           = (string) $usuario['fBaja']; 



	  	if(isset($usuario['bloqueado'])){  		

			if( $usuario['bloqueado'] === 'true' || $usuario['bloqueado'] === 1)

				$usuario['bloqueado'] = 1;

			else

				$usuario['bloqueado'] = 0;

	  	}	else{

	  		$usuario['bloqueado'] = -1;

	  	}

	  		  	// VALIDACI���N

		if( $usuario['nombre'] == '' || strlen($usuario['nombre']) > 40 || !preg_match('/^[a-zA-Z���������������������������������������������\'\s]+$/', $usuario['nombre']) )

			return false;

		if( $usuario['primerApellido'] == '' || strlen($usuario['primerApellido']) > 30 || !preg_match('/^[a-zA-Z���������������������������������������������\'\s]+$/', $usuario['primerApellido']) )

			return false;

		if( $usuario['segundoApellido'] == '' || strlen($usuario['segundoApellido']) > 30 || !preg_match('/^[a-zA-Z���������������������������������������������\'\s]+$/', $usuario['segundoApellido']) )

			return false;

		if( strlen($usuario['fNacimiento']) != 10 || strlen($usuario['fNacimiento']) == 0 || !preg_match('/^([0-9]{2}\/[0-9]{2}\/[0-9]{4})$/', $usuario['fNacimiento']) || !fechaPermitida($usuario['fNacimiento']) || !fechaMenorQueActual($usuario['fNacimiento']))

			return false;

		else

			$usuario['fNacimiento'] = formatearFecha( $usuario['fNacimiento'], 'bbdd' );

		if( !validarCorreo($usuario['correo'], $comprobarExistente) )

			return false;

		if( $usuario['nombreUsuario'] == '' || strlen($usuario['nombreUsuario']) > 20 || !preg_match('/^[a-zA-Z���������������������������������������������\'\s', $usuario['nombreUsuario']) || ($comprobarExistente && existeRegistro('nombre_usuario', $usuario['nombreUsuario'], 'usuario') ) )

			return false;



		if( $comprobarContrasena && ($usuario['contrasena'] == '' || strlen($usuario['contrasena']) > 20 || !preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/', $usuario['contrasena']) ) )

			return false;

			

		if( $usuario['bloqueado'] != 0 && $usuario['bloqueado'] != 1 )

			return false;

		if( $usuario['fBaja'] != '' )

			return false;

		else

			$usuario['fBaja'] = null;



		if($comprobarBloqueado && ($usuario['bloqueado'] != 0 && $usuario['bloqueado'] != 1))

			return false;



		return $usuario;

	}



	function validarCamposProfesor( $profesor ){



		// CASTEO

		if( $profesor['esAdmin'] == 'true' || $profesor['esAdmin'] == 1)

			$profesor['esAdmin'] = 1;

		else

			$profesor['esAdmin'] = 0;



		if( $profesor['evitarNotificacion'] == 'true' || $profesor['evitarNotificacion'] == 1)

			$profesor['evitarNotificacion'] = 1;

		else

			$profesor['evitarNotificacion'] = 0;



		// VALIDACI���N

	  	if( $profesor['esAdmin'] != 0 && $profesor['esAdmin'] != 1 )

			return false;

		if( $profesor['evitarNotificacion'] != 0 && $profesor['evitarNotificacion'] != 1 )

			return false;



		return $profesor;

	}



	function validarCamposAlumno( $alumno , $comprobarExistente = true){



		// CASTEO 

		$alumno['numExpediente']	= (int) $alumno['numExpediente'];

	  	$alumno['idTitulacion'] 	= (int) $alumno['idTitulacion'];

	  	$alumno['curso'] 			= (int) $alumno['curso'];



		// VALIDACI���N

		

	  	if( $alumno['numExpediente'] <= 0 || $alumno['numExpediente'] > 99999999 || ($comprobarExistente && existeRegistro('num_expediente', $alumno['numExpediente'], 'alumno') ) )

			return false;

		if( $alumno['idTitulacion'] <= 0 )

			return false;

		if( $alumno['curso'] <= 0 )

			return false;



		return $alumno;

	}



	function validarCamposLibro($libro){



		//CASTEO

		$libro['titulo'] 			= (string) $libro['titulo'];

		$libro['tituloOriginal'] 	= (string) $libro['tituloOriginal'];

		$libro['autor'] 			= (string) $libro['autor'];		

		$libro['ano'] 				= (int) $libro['ano'];

		$libro['idTitulacion'] 		= (int) $libro['idTitulacion'];

		$libro['anadidoPor'] 		= (int) $libro['anadidoPor'];



		//VALIDACI���N

		//if( $libro['titulo'] == '' || strlen($libro['titulo']) > 100 || !preg_match('/^[a-zA-Z0-9�����������я��⏫���������ҏ�㏪���������ҏ�㏪���������я��⏫Џ�Џ�䏪�_,.&\s]+$/', $libro['titulo']) )
		if( $libro['titulo'] == '' || strlen($libro['titulo']) > 100 || !preg_match('/^[a-zA-Z0-9���������������������������������������������_\-.:;\'&\s]+$/', $libro['titulo']) )
			return false;



		if( $libro['tituloOriginal'] == '' || strlen($libro['tituloOriginal']) > 100 || !preg_match('/^[a-zA-Z0-9���������������������������������������������_\-.:;\'&\s]+$/', $libro['tituloOriginal']) )

			return false;



		if( $libro['autor'] == '' || strlen($libro['autor']) > 50 || !preg_match('/^[a-zA-Z0-9���������������������������������������������_\-.;\'\s]+$/', $libro['autor']) )

			return false;	



		if($libro['ano'] < 1900 || $libro['ano'] > date("Y"))

			return false;



		if(!existeRegistro('id_titulacion', $libro['idTitulacion'], 'titulacion'))

			return false;



		if(!existeRegistro('id_usuario', $libro['anadidoPor'], 'usuario') && $libro['anadidoPor'] != 0)

			return false;


		return $libro;



	}



	function validarCamposLibroPropuesto($libroPropuesto){



		//CASTEO

		$libroPropuesto['propuestoPara'] 	= (string) $libroPropuesto['propuestoPara'];

		$libroPropuesto['motivo'] 			= (string) $libroPropuesto['motivo'];





		//VALIDACI���N

		if( $libroPropuesto['motivo'] == '' || strlen($libroPropuesto['motivo']) > 2000 )

			return false;


		if(utf8_encode($libroPropuesto['propuestoPara']) !=  utf8_encode('añadir') && $libroPropuesto['propuestoPara'] != 'eliminar')

			return false;



		return $libroPropuesto;

	}



	function validarCamposLibroAnadido($libroAnadido,$rankingAnadir = false){

		$posicionesRanking =consulta('count(id_libro)','libro');



		//CASTEO

		$libroAnadido['idPais']					= (int) $libroAnadido['idPais'];

		$libroAnadido['idCategoria']			= (int) $libroAnadido['idCategoria'];

		$libroAnadido['posicionRanking']		= (int) $libroAnadido['posicionRanking'];

		$libroAnadido['nivelEspecializacion']	= (string) $libroAnadido['nivelEspecializacion'];



		//VALIDACI���N

		if(!existeRegistro('id_Pais', $libroAnadido['idPais'], 'pais'))

			return false;

		if(!existeRegistro('id_Categoria', $libroAnadido['idCategoria'], 'categoria_libro'))

			return false;

		if($rankingAnadir)

			$posicionesRanking++;



		if($libroAnadido['posicionRanking'] < 0 || $libroAnadido['posicionRanking'] > $posicionesRanking )

			return false;



		if($libroAnadido['nivelEspecializacion'] =! 'basico' && $libroAnadido['nivelEspecializacion'] != 'especialidad')

			return false;



		if( strlen($libroAnadido['resena']) > 5000 )

			return false;

			

		return $libroAnadido;

	}



	function validarCamposNominacion($nominacion){

		//CASTEO



		$nominacion['titulo'] 			= (string) $nominacion['titulo'];

		$nominacion['tituloOriginal'] 	= (string) $nominacion['tituloOriginal'];

		$nominacion['autor'] 			= (string) $nominacion['autor'];		

		$nominacion['ano'] 				= (int) $nominacion['ano'];

		$nominacion['propuestoPara'] 	= (string) $nominacion['propuestoPara'];

		$nominacion['motivo'] 			= (string) $nominacion['motivo'];



		//VALIDACI���N

		if( $nominacion['titulo'] == '' || strlen($nominacion['titulo']) > 100 || !preg_match('/^[a-zA-Z0-9���������������������������������������������_\-.:;\'&\s]+$/', $nominacion['titulo']) )

			return false;



		if( $nominacion['tituloOriginal'] == '' || strlen($nominacion['tituloOriginal']) > 100 || !preg_match('/^[a-zA-Z0-9���������������������������������������������_\-.:;\'&\s]+$/', $nominacion['tituloOriginal']) )

			return false;



		if( $nominacion['autor'] == '' || strlen($nominacion['autor']) > 50 || !preg_match('/^[a-zA-Z0-9���������������������������������������������_\-.;\'\s]+$/', $nominacion['autor']) )

			return false;



		if($nominacion['ano'] < 1900 || $nominacion['ano'] > date("Y"))

			return false;



		if( $nominacion['motivo'] == '' || strlen($nominacion['motivo']) > 2000 )

			return false;



		if($nominacion['propuestoPara'] != 'a���adir' && $nominacion['propuestoPara'] != 'eliminar')

			return false;





		return $nominacion;

	}



	function validarCamposClubLectura($clubLectura){



		//CASTEO

		$clubLectura['creadoPor']	 = (int) $clubLectura['creadoPor'];

		$clubLectura['nombre']		 = (string) $clubLectura['nombre'];

		$clubLectura['fFin']         = null;

		$clubLectura['idTitulacion'] = (int) $clubLectura['idTitulacion'];

		$clubLectura['curso']		 = (int) $clubLectura['curso']; 



		//VALIDACI���N

		if( $clubLectura['creadoPor'] < 0 || !existeRegistro('id_usuario', $clubLectura['creadoPor'], 'profesor') )

			return false;



		if( $clubLectura['nombre'] == '' || strlen($clubLectura['nombre']) > 20 || !preg_match('/^[a-zA-Z0-9���������������������������������������������\s]+$/', $clubLectura['nombre']) || existeRegistro('nombre', $clubLectura['nombre'], 'clubLectura') )

			return false;



		if( $clubLectura['idTitulacion'] < 0 || consulta('id_titulacion', 'titulacion', 'id_titulacion = ' . $clubLectura['idTitulacion']) === false )

			return false;

		else

			if( $clubLectura['curso'] < 0 || $clubLectura['curso'] > consulta('duracion', 'titulacion', 'id_titulacion = ' . $clubLectura['idTitulacion']) )

				return false;



		return $clubLectura;

	}

?>

