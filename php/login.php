<?php

	require_once("./general/bbdd.php");
	require_once("./general/funciones.php");
	require_once("./general/token.php");
	require_once("./clases/Hash.php");
	require_once("./clases/Usuario.php");

	define("MAX_INTENTOS_CONEXION", 3);
	define("TABLA_SQL", "usuario");

	$obj       = $_POST;
	$respuesta = array();

	if( $obj['opcion'] == 'login' && $obj['accion'] == 'comprobar' ){

		$campos    = 'id_usuario';
		$condicion = 'correo = "' . $obj['correo'] . '"';

		$idUsuario = (int) consulta($campos, TABLA_SQL, $condicion);

		if( $idUsuario != 0 ){

			$usuario = new Usuario();

			$usuario->cargar($idUsuario);

			if( $usuario->estaBloqueado() ){

				$respuesta['error']            = true;
				$respuesta['descripcionError'] = 'Usuario bloqueado, por favor, ponte en contacto con el administrador.';

			} else {

				session_start();
				if( Hash::esValido( $obj['contrasena'], $usuario->obtenerContrasena() ) ){

					// Se comprueba si la password necesita ser rehasheada

					Hash::mejorar( $obj['contrasena'], $usuario->obtenerContrasena(), $idUsuario );

					//BUSCAR ROL DE USUARIO
					$rol = '';
					$profesor = consulta('id_profesor', 'profesor', 'id_Usuario = '.$idUsuario);
					if(!empty($profesor)){
						$profesorAdmin = consulta('es_admin', 'profesor', 'id_Usuario = '.$idUsuario);
						if($profesorAdmin){
							$rol = 'admin';
						} else{
							$rol = 'profesor';
						}
					} else{
						$alumno = consulta('id_alumno','alumno','id_Usuario = '.$idUsuario);
						if(!empty($alumno)){
							$rol = 'alumno';
						}
					}
					
					//CREAR TOKEN
					$respuesta['token'] = generarToken($idUsuario, $usuario->obtenerNombre(), $usuario->obtenerNombreUsuario(), $rol, $obj['correo']);
					$respuesta['invitado'] = false;

					//CERRAR SESIÓN
					session_destroy();
					

					$respuesta['error'] = false;

	            }else{

	            	$respuesta['error']            = true;
					$respuesta['descripcionError'] = 'Contraseña incorrecta.';


					if( !isset($_SESSION['intentosConexion']) ){

						$_SESSION['intentosConexion'] = 1;

					}else{

						if( $_SESSION['intentosConexion'] > MAX_INTENTOS_CONEXION ){

							//$usuario->bloquear();

							$respuesta['descripcionError'] = 'Ha alcanzado el número máximo de intentos, su cuenta se ha bloqueado.';

						}else{

							$_SESSION['intentosConexion']--;
						}
					}
	            }
	        }

		} else{
			//Comprobar si el usuario está invitado
			$tabla = "invitacion";
			$campos = "contrasena";
			$condicion = 'correo = "' . $obj['correo'] . '"';
			$contrasena = consulta($campos, $tabla, $condicion);
			if($contrasena){
				session_start();

				if( Hash::esValido( $obj['contrasena'], $contrasena) ){
					$rol = 'invitado';
					//CREAR TOKEN
					$respuesta['token'] = generarToken(0,'', 'Invitado', $rol, $obj['correo']);
					$respuesta['invitado'] = true;
					//CERRAR SESIÓN
					session_destroy();

				} else{
					$respuesta['error']            = true;
					$respuesta['descripcionError'] = 'Contraseña incorrecta.';


					if( !isset($_SESSION['intentosConexion']) ){

						$_SESSION['intentosConexion'] = 1;

					}else{

						if( $_SESSION['intentosConexion'] > MAX_INTENTOS_CONEXION ){

							//$usuario->bloquear();

							$respuesta['descripcionError'] = 'Ha alcanzado el número máximo de intentos, su cuenta se ha bloqueado.';

						}else{

							$_SESSION['intentosConexion']--;
						}
					}
				}
			}
			else{
				$respuesta['error']            = true;
				$respuesta['descripcionError'] = 'Nombre de usuario no registrado.';
			}
		}


		
		
	}

	elseif( $obj['opcion'] == 'login' && $obj['accion'] == 'contrasenaOlvidada' ){
		
		$campos    = 'id_usuario';
		$condicion = 'correo = "' . $obj['correo'] . '" AND nombre_usuario = "' . $obj['nombreUsuario'];

		$idUsuario = (int) consulta($campos, TABLA_SQL, $condicion);

		if( $idUsuario != 0 ){

			$usuario = new Usuario($idUsuario);

			if( $usuario->estaBloqueado() ){

				$respuesta['error']            = true;
				$respuesta['descripcionError'] = 'Usuario bloqueado, por favor, ponte en contacto con el administrador.';

			} else {

				// 1) Se genara la contraseña aleatoria, se hashea y luego se añade a la bbdd
				$contrasenaAleatoria = substr( md5(microtime()), 1, 10 );
				$hash                = new Hash($contrasenaAleatoria);

				$usuario->cambiarContrasena($hash);

				// 2) Se envía al correo dado la contraseña generada
				$asunto   = 'READARKRIT - Olvido de contraseña';

				$mensaje  = '¡Hola ' . $usuario->obtenerNombreUsuario() . '!\n\n';
				$mensaje .= 'Hemos recibido el aviso de que has olvidado tu contraseña.\n';
				$mensaje .= '\rTu nueva contraseña es: ' . $contrasenaAleatoria . '\n\n';
				$mensaje .= 'Entendemos que no te guste, por eso, nada más iniciar sesión con ella, puedes cambiarla y poner la que quieras.\n\n\n';
				$mensaje .= 'Saludos ;)';

				mail($usuario->obtenerCorreo(), $asunto, $mensaje);

				// 3) Informar al usuario
				$respuesta['error'] = false;
				$respuesta['descripcion'] = 'Abre tu correo y tendrás tu nueva contraseña.';
			}

		}else{

			$respuesta['error']            = true;
			$respuesta['descripcionError'] = 'Revisa los datos que has introducido.';
		}
	}


	else{

		// Acceso sin permiso (INTRUSO)

		$respuesta['error']            = true;
		$respuesta['descripcionError'] = 'Petición no registrada.';
	}



	echo json_encode( $respuesta );

?>