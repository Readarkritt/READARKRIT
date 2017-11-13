<?php

	require_once("./general/bbdd.php");
	require_once("./general/funciones.php");
	require_once("./general/token.php");
	require_once("./general/readarkrit.php");
	require_once("./clases/Resena.php");

	$obj       = $_POST;
	$respuesta = array();


	if( $obj['opcion'] == 'resena' && $obj['accion'] == 'listar' ){
		$sql = 'select r.id_resena, r.nota, r.comentario, r.fecha_alta, r.id_libro, u.nombre, l.titulo from resena r inner join usuario u on u.id_usuario = r.id_usuario inner join libro l on l.id_libro = r.id_libro';
		$respuesta['resenas'] = consulta('','','',$sql);
		$respuesta['error']   = false;
	} else	if( $obj['opcion'] == 'resena' && $obj['accion'] == 'listarResenasLibro' ){
		$sql = 'select r.id_resena, r.nota, r.comentario, r.fecha_alta, r.id_libro, u.nombre from resena r inner join usuario u on u.id_usuario = r.id_usuario where id_libro = '.$obj['idLibro'];
		$respuesta['resenas'] = consulta('','','',$sql);
		$respuesta['error']   = false;
	}
	else if( $obj['opcion'] == 'resena' && $obj['accion'] == 'listarResenasConectado' ){	
		if(tienePermiso('alumno')){

			$id = recuperarDeToken('id');
			$sql = 'select r.id_resena, r.nota, r.comentario, r.fecha_alta, r.id_libro, l.titulo, l.portada from resena r inner join libro l on l.id_libro = r.id_libro where id_usuario = '.$id;
			$respuesta['resenas'] = consulta('','','',$sql);
			$respuesta['error']   = false;

		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	} else if( $obj['opcion'] == 'resena' && $obj['accion'] == 'alta'  ){

		if(tienePermiso('alumno')){
			$resena = array(
					'idResena'	 => '',
					'nota'		 => $obj['resena']['nota'] ,
					'comentario' => $obj['resena']['comentario'] ,
					'idLibro'	 => $obj['resena']['idLibro'] ,
					'idUsuario'	 => recuperarDeToken('id'),
					'fAlta'		 => date("Y-m-d")
			);

			//VALIDACIÓN
			$resenaValidada = validarCamposResena($resena);

			//INSERCIÓN
			if($resenaValidada){
				$resena = new Resena();
				$resena->rellenar($resenaValidada);

				$respuesta['resena'] = $resena->toArray();
				$respuesta['resena']['nombre'] = consulta('nombre','usuario','id_usuario = '.$resena->obtenerIdUsuario());
				$respuesta['resena']['titulo'] = consulta('titulo','libro','id_libro = '.$resena->obtenerIdLibro());
				$respuesta['resena']['portada'] = consulta('portada','libro','id_libro = '.$resena->obtenerIdLibro());
				$respuesta['error'] = false;
			} else{
				$respuesta['error']            = true;
				$respuesta['descripcionError'] = 'Datos manipulados';
			}
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}

	} else if( $obj['opcion'] == 'resena' && $obj['accion'] == 'comentarioHechoConectado'  ){
		if(tienePermiso('alumno')){

			$idUsuario = recuperarDeToken('id');
			$sql = "SELECT COUNT(id_resena) FROM resena WHERE id_libro = ".$obj['idLibro']." AND id_usuario = ".$idUsuario;

			if(consulta('','','',$sql) == 0){
				$existe = false;
			} else {
				$existe = true;
			}

			$respuesta['existe'] = $existe;
			$respuesta['error'] = false;
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}
	}else if( $obj['opcion'] == 'resena' && $obj['accion'] == 'eliminar'  ){
		if(tienePermiso('alumno')){
			$idResena = $obj['idResena'];
			$idUsuario = recuperarDeToken('id');

			if(consulta('count(id_Resena)','resena',"id_Resena = ".$idResena) > 0){

				if((isset($obj['administraccion']) && $obj['administraccion']) || consulta('count(id_Resena)','resena',"id_Resena = ".$idResena." AND id_usuario =".$idUsuario) > 0){
					borrar('resena',"id_Resena = ".$idResena);
					$respuesta['error'] = false;
				} else{
				$respuesta['error'] = true;
				$respuesta['descripcionError'] = "Sólo puedes eliminar tus propios comentarios.";
				}
			}else{
				$respuesta['error'] = true;
				$respuesta['descripcionError'] = "No se ha encontrado la reseña solicitada.";

			}
		
		} else{
			$respuesta['error'] = true;
			$respuesta['descripcionError'] = 'Falta de permisos';				
		}

	}

	echo json_encode( $respuesta );

?>