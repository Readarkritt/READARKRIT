<?php

	require_once("./general/bbdd.php");
	require_once("./general/funciones.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Hash.php");
	require_once("./clases/Alumno.php");
	require_once("./clases/Estanteria.php");

	// CONTROLADOR

	$obj       = $_POST;
	$respuesta = array();
	$alumno    = array();
	$estanteria = array();


	if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'alta' ){

		$usuarioValidado = validarCamposUsuario( $obj['usuario'] );
		$alumnoValidado  = validarCamposAlumno( $obj['alumno'] );

		if( $usuarioValidado && $alumnoValidado && count($obj['librosLeidos']) != 0 ){

			// 1) Crear usuario
			$alumno = new Alumno();
			$alumno->rellenar( $usuarioValidado, $alumnoValidado );

			// 2) Crear la estantería por defecto ('Libros Leídos') para ese usuario
			$estanteria = new Estanteria();
			$estanteria->rellenarDefault( $alumno->obtenerIdUsuario() );

			// 3) Relacionar la estantería con los libros leídos
			$camposSQL = 'id_rel_libro_estanteria, id_libro, id_estanteria, libro_leido';
			$arrValores = array();

			for($i=0; $i<count($obj['librosLeidos']); $i++){

				//$idLibro = consulta( 'id_libro', 'libro_anadido', 'id_libro_anadido = '. $obj['librosLeidos'][$i] );

				$arrValores[0] = ''; // id_rel_libro_estanteria
				$arrValores[1] = $obj['librosLeidos'][$i]; 
				$arrValores[2] = $estanteria->obtenerId();
				$arrValores[3] = 1;

				insertar( $camposSQL, $arrValores, 'rel_libro_estanteria' );
			}

			// 4) Respuesta de la petición
			$respuesta['idUsuario'] = $alumno->obtenerIdUsuario();
			$respuesta['idAlumno']  = $alumno->obtenerId();

			$respuesta['error']     = false;
		} else {

			$respuesta['error']            = true;
			$respuesta['descripcionError'] = 'Datos manipulados';
		}
	}

	if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'existe' ){

		$respuesta['existe'] = existeRegistro($obj['campo'], $obj['valor'], $obj['opcion']);
	}

	if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'modificar' ){

		$alumnoPeticion = $obj['alumno'];
		$usuarioPeticion = $alumnoPeticion['usuario'];

		$respuesta['errorCorreo'] = false;
		$respuesta['errorTitulacion'] = false;
		$respuesta['errorCurso'] = false;
		$respuesta['errorContrasena'] = false;

		$id = recuperarDeToken('id');
		if($id != null){
			$alumno = new Alumno();
			$alumno->cargar($alumnoPeticion['idAlumno']);
			$usuario = $alumno->obtenerUsuario();

			if($usuarioPeticion['contrasena'] != ''){
				if(Hash::esValido( $usuarioPeticion['contrasenaVieja'], $usuario->obtenerContrasena()) && validarContrasena($usuarioPeticion['contrasena'], $usuarioPeticion['contrasenaRepetida'])){
					$usuario->cambiarContrasena($usuarioPeticion['contrasena']);
				} else{			
					$respuesta['errorContrasena'] = true;
				}
			}

			if($usuario->obtenerCorreo() != $usuarioPeticion['correo']){
				if(validarCorreo($usuarioPeticion['correo'])){
					$usuario->modificarCorreo($usuarioPeticion['correo']);
				} else{
					$respuesta['errorCorreo'] = true;	
				}		
			}
			if(!$respuesta['errorCorreo']){
				$respuesta['correo'] = $usuarioPeticion['correo'];
			}

			if($alumno->obtenerIdTitulacion() != $alumnoPeticion['idTitulacion']){
				if(parseInt($alumnoPeticion['idTitulacion']) > 0){
					$alumno->cambiarIdTitulacion($alumnoPeticion['idTitulacion']);
				} else{
					$respuesta['errorTitulacion'] = true;
				}
			}

			if($alumno->obtenerCurso() != $alumnoPeticion['curso']){
				if(parseInt($alumnoPeticion['curso']) > 0){
					$alumno->cambiarCurso($alumnoPeticion['curso']);
				} else{
					$respuesta['errorCurso'] = true;
				}
			}
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Token erróneo';			
		}
	}

	if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'recuperarConectado' ){
		$id = recuperarDeToken('id');
		if($id != null){
			$idAlumno = consulta( 'id_Alumno', 'alumno', 'id_Usuario = '.$id );
			if($idAlumno != null){
				$respuesta['tokenErroneo'] = false;
				$alumno = new Alumno();
				$alumno->cargar($idAlumno);
				$respuesta['alumno'] = $alumno->toArray();
				$respuesta['alumno']['usuario']['contrasena'] = '';
			} else{
				$respuesta['tokenErroneo'] = true;
			}
		} else{
			$respuesta['tokenErroneo'] = true;
		}
	}

	else if( $obj['opcion'] == 'alumno' && $obj['accion'] == 'listar' ){
		$sql = 'select u.nombre, u.primer_apellido, u.segundo_apellido, u.nombre_usuario, u.correo, a.num_expediente, a.id_titulacion, a.curso from usuario u inner join alumno a on u.id_usuario = a.id_usuario where f_baja is null';

		$respuesta['alumnos'] = consulta( '', '', '', $sql);
		$respuesta['error']      = ($respuesta['alumnos'] === false);
	}

	echo json_encode( $respuesta );

?>