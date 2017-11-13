<?php

	require_once("Libro.php");

	class LibroPropuesto{
		private $idLibroPropuesto		= 0;
		private $idLibro 				= 0;
		private $propuestoPara 			= 0;
		private $motivo	 				= 0;

		private $libro;

		private $tablaSQL	= '';
		private $camposSQL	= '';

		public function __construct(){
			$this->tablaSQL = 'libro_propuesto';
			$this->camposSQL = 'id_libro_propuesto, id_libro, propuesto_para, motivo';

			$this->libro = new Libro();
		}

		public function rellenar($valoresLibro, $valoresLibroPropuesto){

			$this->libro->rellenar($valoresLibro);

			$valoresLibroPropuesto['idLibro'] = $this->libro->obtenerId();

			$this->idLibro 			= $valoresLibroPropuesto['idLibro'];
			$this->idLibroPropuesto = $valoresLibroPropuesto['idLibroPropuesto'];
			$this->propuestoPara	= $valoresLibroPropuesto['propuestoPara'];
			$this->motivo	 		= $valoresLibroPropuesto['motivo'];

			$this->idLibroPropuesto = insertar($this->camposSQL, $valoresLibroPropuesto, $this->tablaSQL);
		}

		public function obtenerId(){
			return $this->idLibroPropuesto;
		}


		public function cargar($idLibroPropuesto){
			$condicion = 'id_libro_propuesto = '.$idLibroPropuesto;

			$resultado = consulta($this->camposSQL, $this->tablaSQL, $condicion);

			$this->idLibroPropuesto 	= $idLibroPropuesto;
			$this->idLibro 				= $resultado['id_libro'];
			$this->propuestoPara 		= $resultado['propuesto_para'];
			$this->motivo				= $resultado['motivo'];

			$this->libro->cargar($this->idLibro);
		}

		public function eliminar(){
			return $this->libro->eliminar();
		}


	}
?>
