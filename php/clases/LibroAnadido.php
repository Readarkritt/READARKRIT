<?php

	require_once("Libro.php");

	class LibroAnadido{
		private $idLibroAnadido			= 0;
		private $idLibro 				= 0;
		private $idPais 				= 0;
		private $idCategoria 			= 0;
		private $posicionRanking 		= 0;
		private $mediaNumUsuarios		= 0;
		private $nivelEspecializacion 	= '';

		private $libro;

		private $tablaSQL	= '';
		private $camposSQL	= '';

		public function __construct(){
			$this->tablaSQL = 'libro_anadido';
			$this->camposSQL = 'id_libro_anadido, id_libro, id_pais, id_categoria, posicion_ranking, media_num_usuarios, nivel_especializacion';

			$this->libro = new Libro();
		}

		private function rellenarCamposLibroAnadido($valores){

			$this->idLibro 					= $valores['idLibro'];
			$this->idPais 					= $valores['idPais'];
			$this->idCategoria 				= $valores['idCategoria'];
			$this->posicionRanking 			= $valores['posicionRanking'];
			$this->mediaNumUsuarios 		= $valores['mediaNumUsuarios'];
			$this->nivelEspecializacion 	= $valores['nivelEspecializacion'];

			$this->idLibroAnadido = insertar($this->camposSQL, $valores, $this->tablaSQL);

		}

		public function rellenar($valoresLibro, $valoresLibroAnadido){
			$this->libro->rellenar($valoresLibro);

			$valoresLibroAnadido['idLibro'] = $this->libro->obtenerId();

			$this->rellenarCamposLibroAnadido($valoresLibroAnadido);
		}

		public function cargar($idLibroAnadido){
			$condicion = 'id_libro_anadido = '.$idLibroAnadido;

			$resultado = consulta($this->camposSQL, $this->tablaSQL, $condicion);

			$this->idLibroAnadido 	= $idLibroAnadido;
			$this->idLibro 			= $resultado['id_libro'];
			$this->idPais 			= $resultado['id_pais'];
			$this->idCategoria		= $resultado['id_categoria'];
			$this->posicionRanking	= $resultado['posicion_ranking'];
			$this->mediaNumUsuarios	= $resultado['media_num_usuarios'];
			$this->nivelEspecializacion	= $resultado['nivel_especializacion'];

			$this->libro->cargar($this->idLibro);
		}

		public function anadirAColeccion($valoresLibroAnadido){
			$this->rellenarCamposLibroAnadido($valoresLibroAnadido);

			borrar('libro_propuesto','id_libro = '.$this->idLibro);
		}

		public function eliminar(){
			return $this->libro->eliminar();
		}

		public function reactivar(){
			return $this->libro->reactivar();
		}

		public function obtenerId(){
			return $this->idLibroAnadido;
		}

		public function obtenerIdLibro(){
			return $this->idLibro;
		}

		public function obtenerIdPais(){
			return $this->idPais;
		}

		public function obtenerIdCategoria(){
			return $this->idCategoria;
		}

		public function obtenerPosicionRanking(){
			return $this->posicionRanking;
		}

		public function obtenerMediaNumUsuarios(){
			return $this->mediaNumUsuarios;
		}

		public function obtenerNivelEspecializacion(){
			return $this->nivelEspecializacion;
		}

		public function obtenerLibro(){
			return $this->libro;
		}


		public function cambiarIdPais($idPais){
			$condicion = 'id_libro_anadido = '.$this->idLibroAnadido;

			$this->idPais = $idPais;

			actualizar('id_pais', $this->idPais, $this->tablaSQL, $condicion);
		}

		public function cambiarIdCategoria($idCategoria){
			$condicion = 'id_libro_anadido = '.$this->idLibroAnadido;

			$this->idCategoria = $idCategoria;

			actualizar('id_categoria', $this->idCategoria, $this->tablaSQL, $condicion);
		}

		public function cambiarPosicionRanking($posicionRanking){
			$condicion = 'id_libro_anadido = '.$this->idLibroAnadido;

			$this->posicionRanking = $posicionRanking;

			actualizar('posicion_ranking', $this->posicionRanking, $this->tablaSQL, $condicion);
		}

		public function cambiarMediaNumUsuarios($mediaNumUsuarios){
			$condicion = 'id_libro_anadido = '.$this->idLibroAnadido;

			$this->mediaNumUsuarios = $mediaNumUsuarios;

			actualizar('media_num_usuarios', $this->mediaNumUsuarios, $this->tablaSQL, $condicion);
		}

		public function cambiarNivelEspecializacion($nivelEspecializacion){
			$condicion = 'id_libro_anadido = '.$this->idLibroAnadido;

			$this->nivelEspecializacion = $nivelEspecializacion;

			actualizar('nivel_especializacion', $this->nivelEspecializacion, $this->tablaSQL, $condicion);
		}



		public function toArray(){
			$libroAnadido = array(
				'idLibro' 				=> $this->idLibro,
				'idLibroAnadido' 		=> $this->idLibroAnadido,
				'idPais' 				=> $this->idPais,
				'idCategoria' 			=> $this->idCategoria,
				'posicionRanking' 		=> $this->posicionRanking,
				'mediaNumUsuarios' 		=> $this->mediaNumUsuarios,
				'nivelEspecializacion' 	=> $this->nivelEspecializacion,
				'libro' 				=> $this->libro->toArray()
			);

			return $libroAnadido;
		}

		public function toSingleArray(){		
		
			$libro 				= $this->libro->toArray();
			$libroAnadido = array(
				'idLibro' 				=> $this->idLibro,
				'idLibroAnadido' 		=> $this->idLibroAnadido,
				'idPais' 				=> $this->idPais,
				'idCategoria' 			=> $this->idCategoria,
				'posicionRanking' 		=> $this->posicionRanking,
				'mediaNumUsuarios' 		=> $this->mediaNumUsuarios,
				'nivelEspecializacion' 	=> $this->nivelEspecializacion,

				'portada'				=> $libro['portada'],
				'titulo'				=> $libro['titulo'],
				'tituloOriginal'		=> $libro['tituloOriginal'],
				'autor'					=> $libro['autor'],
				'ano'					=> $libro['ano'],
				'anadidoPor'			=> $libro['anadidoPor'],
				'idTitulacion'			=> $libro['idTitulacion'],
				'fBaja'					=> $libro['fBaja'],

			);
			return $libroAnadido;
		}


	}	