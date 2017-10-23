<?php
	require_once(dirname(__FILE__).'./funciones.php');

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

	  	// VALIDACIÓN
		if( $usuario['nombre'] == '' || strlen($usuario['nombre']) > 40 || !preg_match('/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/', $usuario['nombre']) )
			return false;
		if( $usuario['primerApellido'] == '' || strlen($usuario['primerApellido']) > 30 || !preg_match('/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/', $usuario['primerApellido']) )
			return false;
		if( $usuario['segundoApellido'] == '' || strlen($usuario['segundoApellido']) > 30 || !preg_match('/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/', $usuario['segundoApellido']) )
			return false;
		if( strlen($usuario['fNacimiento']) != 10 || strlen($usuario['fNacimiento']) == 0 || !preg_match('/^([0-9]{2}\/[0-9]{2}\/[0-9]{4})$/', $usuario['fNacimiento']) || !fechaPermitida($usuario['fNacimiento']) || !fechaMenorQueActual($usuario['fNacimiento']))
			return false;
		else
			$usuario['fNacimiento'] = formatearFecha( $usuario['fNacimiento'], 'bbdd' );
		if( !validarCorreo($usuario['correo'], $comprobarExistente) )
			return false;
		if( $usuario['nombreUsuario'] == '' || strlen($usuario['nombreUsuario']) > 20 || !preg_match('/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/', $usuario['nombreUsuario']) || ($comprobarExistente && existeRegistro('nombre_usuario', $usuario['nombreUsuario'], 'usuario') ) )
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

		// VALIDACIÓN
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

		// VALIDACIÓN
		
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

		//VALIDACIÓN
		if( $libro['titulo'] == '' || strlen($libro['titulo']) > 100 || !preg_match('/^[a-zA-Z0-9áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/', $libro['titulo']) )
			return false;

		if( $libro['tituloOriginal'] == '' || strlen($libro['tituloOriginal']) > 100 || !preg_match('/^[a-zA-Z0-9áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/', $libro['tituloOriginal']) )
			return false;

		if( $libro['autor'] == '' || strlen($libro['autor']) > 50 || !preg_match('/^[a-zA-Z0-9áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/', $libro['autor']) )
			return false;	

		if($libro['ano'] < 1900 || $libro['ano'] > date("Y"))
			return false;

		if(!existeRegistro('id_titulacion', $libro['idTitulacion'], 'titulacion'))
			return false;

		if(!existeRegistro('id_usuario', $libro['anadidoPor'], 'usuario') && $libro['anadidoPor'] != 0)
			return false;

		return $libro;

	}

	function validarCamposLibroAnadido($libroAnadido,$rankingAnadir = false){
		$posicionesRanking =consulta('count(id_libro)','libro');

		//CASTEO
		$libroAnadido['idPais']					= (int) $libroAnadido['idPais'];
		$libroAnadido['idCategoria']			= (int) $libroAnadido['idCategoria'];
		$libroAnadido['posicionRanking']		= (int) $libroAnadido['posicionRanking'];
		$libroAnadido['nivelEspecializacion']	= (string) $libroAnadido['nivelEspecializacion'];

		//VALIDACIÓN
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
			
		return $libroAnadido;
	}
?>
