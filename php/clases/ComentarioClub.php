<?php

	class ComentarioClub{

		private $idComentarioClub = 0;
		private $idClub 	      = 0;
		private $idUsuario    	  = 0;
		private $fecha 	  		  = null;
		private $comentario    	  = '';

		private $tablaSQL     = '';
		private $camposSQL    = '';

		public function __construct() {

			$this->tablaSQL  = 'comentario_club';
			$this->camposSQL = 'id_comentario_club, id_club, id_usuario, fecha, comentario';
		}


		public function rellenar( $arrAsocValores ) {	

			$this->idClub    = $arrAsocValores['idClub'];
			$this->idUsuario = $arrAsocValores['idUsuario'];

			
			$this->fecha     		 = generarFechaActual();
			$arrAsocValores['fecha'] = formatearFecha( $this->fecha, 'bbdd');


			$this->comentario = $arrAsocValores['comentario'];		

			$this->idComentarioClub = insertar( $this->camposSQL, $arrAsocValores, $this->tablaSQL );
	    }


	    /*public function cargar( $idClub ) {
	        
			$condicion = 'id_club = ' . $idEstanteria;

			$resultado = consulta($this->camposSQL, $this->tablaSQL, $condicion);


			$this->creadaPor    = $resultado['creada_por'];
			$this->nombre 		= $resultado['nombre'];
			$this->idEstanteria = $idEstanteria;

			return $resultado;
	    }*/

	    public function obtenerId(){
	    	return $this->idComentarioClub;
	    }

	    /*public function cambiarNombre( $nuevoNombre ){

	    	$condicion = 'creada_por = ' . $this->creadaPor . ' and id_estanteria = ' . $this->idEstanteria;

	    	$this->nombre = $nuevoNombre;

	    	actualizar( 'nombre', $this->nombre, $this->tablaSQL, $condicion );
	    }*/

    }

?>