<?php

	require_once("./general/bbdd.php");
	require_once("./general/funciones.php");
	require_once("./general/token.php");
	require_once("./clases/Hash.php");

	$obj       = $_POST;
	$respuesta = array();

	if( $obj['opcion'] == 'invitacion' && $obj['accion'] == 'invitar' ){
		if(tienePermiso('profesor')){

			$obj['correo'] = (string) $obj['correo'];

			if( validarCorreo($obj['correo']) ){

				if( !existeRegistro('correo', $obj['correo'], 'usuario') && !existeRegistro('correo', $obj['correo'], 'invitacion') ){

					$campos 	= '';
					$valores 	= array();
					$contrasena = '';

					$campos     = 'id_invitacion, correo, contrasena';

					$contrasena = generarContrasenaAleatoria();
					$hash       = new Hash( $contrasena );

					array_push($valores, "");
					array_push($valores, $obj['correo']);
					array_push($valores, $hash->get());

					if( insertar( $campos, $valores, 'invitacion' ) ){

						$asunto   = 'READARKRIT - Alta profesor';

						$mensaje  = '¡Hola!\n\n';
						$mensaje .= 'Estás a un paso de ser un profesor de la comunidad READARKRIT.\n';
						$mensaje .= 'Sólo tienes que: \n';
						$mensaje .= '\r1) Entrar en la aplicación con tu correo y esta contrasena <b>' . $contrasena . '</b>.\n';
						$mensaje .= '\r2) Terminar de completar la infomación de tu perfil.\n';
						$mensaje .= '\r3) Y cambiar la contraseña a alguna que no te duela la cabeza recordar.\n\n\n';
						$mensaje .= 'Saludos ;)';

						//mail($obj['correo'], $asunto, $mensaje);

						$respuesta['error'] = false;
					}else{

						$respuesta['error']            = true;
						$respuesta['descripcionError'] = 'No se ha podido crear la invitación.';
					}
				}else{

					$respuesta['error'] = true;
					$respuesta['descripcionError'] = 'El correo se encuentra registrado en el sistema, por favor introduzca otro.';
				}
			}else{

				$respuesta['error'] = true;
				$respuesta['descripcionError'] = 'El correo no sigue un formato correcto.';
			}
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	} 

	echo json_encode( $respuesta );
?>