<?php

	function conectar(){

		$servername  = "localhost";
		$db_username = "root";
		$db_password = "";
		$db_name     = "readarkrit";

		$conn = new mysqli($servername, $db_username, $db_password, $db_name);
		// Con puerto: new mysqli("127.0.0.1", "usuario", "contraseña", "basedatos", 3306);

		if ($conn->connect_errno) {
		    die("No se pudo conectar a la base de datos: (" . $conn->connect_errno . ") " . $conn->connect_error);
		}

		if (!$conn->set_charset("utf8")) {
		    die("Error cargando el conjunto de caracteres utf8: " . $conn->error);
		}

		return $conn;
	}


	function desconectar( $link ){

		return $link->close();
	}


	function consulta( $campos, $tabla, $condicion = '', $sql = '' ){

		$link = conectar();

		$response = array();


		$link->set_charset("utf8");


		if( $sql == '' ){

			if( $condicion != '' )
				$condicion = ' WHERE ' . $condicion;
		
      		$sql = 'SELECT ' . $campos . ' FROM ' . $tabla . $condicion;
		}
		

      	$result = $link->query($sql);


      	if( is_object($result) && $result->num_rows != 0 ){ // si es un objeto accedo a las propiedades de él (num_rows)


	      	if( count( $result->fetch_fields() ) != 1 ){

	      		if( $result->num_rows == 1 ){								// CASO 1: X campos 1 resultado

					$fila = $result->fetch_assoc();

	      			$response = $fila;

	      		} else {													// CASO 2: X campos X resultados 

	      			while( $fila = $result->fetch_assoc() ) {

			      		array_push($response, $fila);
			      	}
	      		}
		      	
	      	} else {

	      		if( $result->num_rows == 1 ){								// CASO 3: 1 campo 1 resultado

	      			$fila = $result->fetch_row();

	      			$response = $fila[0];

	      		} else {													// CASO 4: 1 campo X resultados

	      			while( $fila = $result->fetch_row() ) {

						array_push($response, $fila[0]);
			      	}
	      		}

	      	}


	      	$result->free();

	    } 
	    elseif( is_object($result) && $result->num_rows == 0 ) {			// CASO 5: no se encuentran resultados

	      	$response = null;
	    }
	    else{																// CASO 6: no se ha podido realizar la consulta
	    	$response = false;
	    }


	    desconectar($link);

      	return $response;
	}


	function insertar( $campos, $valores, $tabla ){

		if( $campos == '' || count($valores) == 0 || $tabla == '' ){

			return false;

		} else {

			$link       = conectar();
			$idRegistro = 0;
			$strValores = '';

			foreach ($valores as $valor){

				if( is_string($valor) )
					$valor = '"' . $valor . '", ';
				else
					$valor = $valor . ', ';


				$strValores .= $valor;
			}

			$strValores = rtrim($strValores, ', ');


			$sql = 'INSERT INTO ' . $tabla . ' (' . $campos . ') VALUES (' . $strValores . ')';

			if( $link->query($sql) === TRUE ){

				$idRegistro = $link->insert_id; // devuelvo el id del registro insertado
			}

			desconectar($link);

			return $idRegistro;
		}

	}

	function borrar( $tabla, $condicion = '' ){

		if( $tabla == '' ){

			return false;

		} else {

			$link = conectar();
			$registroBorrado = false;

			$link->autocommit(FALSE);
			$link->commit();
			
			if( $condicion != '' ){

				$condicion = ' WHERE ' . $condicion;
			}

			$sql = 'DELETE FROM ' . $tabla . $condicion;
			
			$registroBorrado = $link->query($sql);
			

			if( !$registroBorrado )
				$link->rollback();

			$link->autocommit(TRUE);

			desconectar($link);

			return $registroBorrado;
		}
	}

	function actualizar( $campo, $valor, $tabla, $condicion = '' ){

		if( $campo == '' || $valor == '' || $tabla == '' ){

			return false;

		} else {

			$link = conectar();
			$registroActualizado = false;

			
			if( $condicion != '' ){

				$condicion = ' WHERE ' . $condicion;
			}

			$sql = 'UPDATE ' . $tabla . ' SET ' . $campo . ' = ' . $valor . $condicion;


			$registroActualizado = $link->query($sql) === TRUE;


			desconectar($link);

			return $registroActualizado;
		}
	}

	function existeRegistro( $campo, $valor, $tabla ){

		if( $campo == '' || $valor == '' || $tabla == '' ){

			return false;

		} else {

			$sql = 'SELECT COUNT(*) FROM ' . $tabla . ' WHERE ' . $campo . ' = "' . $valor . '"';

			return (bool) consulta( '', '', '', $sql);
		}
	}

?>