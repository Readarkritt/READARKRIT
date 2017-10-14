<?php

	class Estanteria{

		private $idEstanteria = 0;
		private $nombre 	  = '';
		private $creadaPor    = 0;

		private $tablaSQL     = '';
		private $camposSQL    = '';

		public function __construct() {

			$this->tablaSQL  = 'estanteria';
			$this->camposSQL = 'id_estanteria, nombre, creada_por';
		}


		public function rellenar( $arrAsocValores ) {	

			$this->nombre  		= $arrAsocValores['nombre'];
			$this->creadaPor    = $arrAsocValores['creadaPor'];

			$this->idEstanteria = insertar( $this->camposSQL, $arrAsocValores, $this->tablaSQL );
	    }

	    // Crea la estantería por defecto 'Libros leídos'
	    public function rellenarDefault( $idUsuario ) {

	    	$arrValores = array();

	    	$this->nombre  		= 'Libros Leídos';
			$this->creadaPor    = $idUsuario;

			array_push($arrValores, '');	// id estantería
			array_push($arrValores, $this->nombre);
			array_push($arrValores, $this->creadaPor);

			$this->idEstanteria = insertar( $this->camposSQL, $arrValores, $this->tablaSQL );
			
	    }

	    public function cargar( $idUsuario ) {
	        
			$condicion = 'creada_por = ' . $idUsuario;


			$resultado = consulta($this->camposSQL, $this->tablaSQL, $condicion);


			$this->creadaPor    = $idUsuario;
			$this->nombre 		= $resultado['nombre'];
			$this->idEstanteria = $resultado['id_estanteria'];

			return $resultado;
	    }

	    public function obtenerId(){
	    	return $this->idEstanteria;
	    }

	    public function cambiarNombre( $nuevoNombre ){

	    	$condicion = 'creada_por = ' . $this->creadaPor;

	    	$this->nombre = $nuevoNombre;

	    	actualizar( 'nombre', $this->nombre, $this->tablaSQL, $condicion );
	    }

    }

?>