<?php

	function validarCamposUsuario( $usuario ){

		// CASTEO
		$usuario['nombre'] 			= (string) $usuario['nombre'];
	  	$usuario['primerApellido'] 	= (string) $usuario['primerApellido'];
	  	$usuario['segundoApellido'] = (string) $usuario['segundoApellido'];
	  	$usuario['fNacimiento'] 	= (string) $usuario['fNacimiento'];
	  	$usuario['correo'] 			= (string) $usuario['correo'];
	  	$usuario['nombreUsuario'] 	= (string) $usuario['nombreUsuario'];
	  	$usuario['contrasena'] 		= (string) $usuario['contrasena'];
	  	$usuario['bloqueado'] 		= (int) $usuario['bloqueado'];
	  	$usuario['fBaja']           = (string) $usuario['fBaja'];
	  	

	  	// VALIDACIÓN
		if( $usuario['nombre'] == '' || strlen($usuario['nombre']) > 40 || !preg_match('/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/', $usuario['nombre']) )
			return false;
		if( $usuario['primerApellido'] == '' || strlen($usuario['primerApellido']) > 30 || !preg_match('/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/', $usuario['primerApellido']) )
			return false;
		if( $usuario['segundoApellido'] == '' || strlen($usuario['segundoApellido']) > 30 || !preg_match('/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/', $usuario['segundoApellido']) )
			return false;
		if( strlen($usuario['fNacimiento']) != 10 || strlen($usuario['fNacimiento']) == 0 || !preg_match('/^([0-9]{2}\/[0-9]{2}\/[0-9]{4})$/', $usuario['fNacimiento']) || !fechaPermitida($usuario['fNacimiento']) )
			return false;
		else
			$usuario['fNacimiento'] = formatearFecha( $usuario['fNacimiento'], 'bbdd' );
		if( $usuario['correo'] == '' || strlen($usuario['correo']) > 50 || !preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $usuario['correo']) || existeRegistro( 'correo', $usuario['correo'], 'usuario') )
			return false;
		if( $usuario['nombreUsuario'] == '' || strlen($usuario['nombreUsuario']) > 20 || !preg_match('/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/', $usuario['nombreUsuario']) || existeRegistro('nombre_usuario', $usuario['nombreUsuario'], 'usuario') )
			return false;
		if( $usuario['contrasena'] == '' || strlen($usuario['contrasena']) > 20 || !preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/', $usuario['contrasena']) )
			return false;
		if( $usuario['bloqueado'] != 0 && $usuario['bloqueado'] != 1 )
			return false;
		if( !is_null($usuario['fBaja']) && ( strlen($usuario['fBaja']) != 10 || strlen($usuario['fBaja']) == 0 || !preg_match('/^([0-9]{2}\/[0-9]{2}\/[0-9]{4})$/', $usuario['fBaja']) || !fechaPermitida($usuario['fBaja']) ) )
			return false;

		return $usuario;
	}

	function validarCamposProfesor( $profesor ){

		// CASTEO
		if( $profesor['esAdmin'] == 'true' )
			$profesor['esAdmin'] = 1;
		else
			$profesor['esAdmin'] = 0;

		if( $profesor['evitarNotificacion'] == 'true' )
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

	function validarCamposAlumno( $alumno ){

		// CASTEO 
		$alumno['numExpediente']	= (int) $alumno['numExpediente'];
	  	$alumno['idTitulacion'] 	= (int) $alumno['idTitulacion'];
	  	$alumno['curso'] 			= (int) $alumno['curso'];

		// VALIDACIÓN
	  	if( $alumno['numExpediente'] <= 0 || $alumno['numExpediente'] > 99999999 || existeRegistro('num_expediente', $alumno['numExpediente'], 'alumno') )
			return false;
		if( $alumno['idTitulacion'] <= 0 )
			return false;
		if( $alumno['curso'] <= 0 )
			return false;

		return $alumno;
	}

?>