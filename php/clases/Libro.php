<?php

	class Libro{
		private $idLibro 			= 0;
		private $portada 			= '';
		private $titulo 			= '':
		private $tituloOriginal 	= '';
		private $autor				= '';
		private $ano				= 0;
		private $idAnadidoPor		= 0;
		private $idTitulacion		= 0;

		private $tablaSQL			= '';
		private $camposSQL			= '';

		public function __construct(){

			$this->tablaSQL = 'libro';
			$this->camposSQL = 'id_libro, portada, titulo, titulo_original, autor, ano, anadido_por, id_titulacion';
		}

		public function rellenar($arrAsocValores){

			$this->portada 			= $arrAsocValores['portada'];
			$this->titulo 			= $arrAsocValores['titulo'];
			$this->tituloOriginal 	= $arrAsocValores['tituloOriginal'];
			$this->autor 			= $arrAsocValores['autor'];
			$this->ano 				= $arrAsocValores['ano'];
			$this->idAnadidoPor		= $arrAsocValores['idAnadidoPor'];
			$this->idTitulacion 	= $arrAsocValores['idTitulacion'];

			$this->idLibro = insertar($this->camposSQL,$arrAsocValores,$this->tablaSQL);
		}

		public function cargar($idLibro){

			$condicion = 'id_libro = '.$idLibro;

			$resultado = consulta($this->camposSQL, $this->tablaSQL, $condicion);

			$this->idLibro 			= $idLibro;
			$this->portada 			= $resultado['portada'];
			$this->titulo 			= $resultado['titulo'];
			$this->tituloOriginal 	= $resultado['titulo_original'];
			$this->autor 			= $resultado['autor'];
			$this->ano 				= $resultado['ano'];
			$this->idAnadidoPor		= $resultado['id_anadido_por'];
			$this->idTitulacion 	= $resultado['id_titulacion'];
		}

		public function eliminar(){
			//FALTA
		}

		public function obtenerId(){
			return $this->idLibro;
		}

		public function obtenerPortada(){
			return $this->portada;
		}

		public function obtenerTitulo(){
			return $this->titulo;
		}

		public function obtenerTituloOriginal(){
			return $this->tituloOriginal;
		}

		public function obtenerAutor(){
			return $this->autor;
		}

		public function obtenerAno(){
			return $this->ano;
		}

		public function obtenerIdAnadidoPor(){
			return $this->idAnadidoPor;
		}

		public function obtenerIdTitulacion(){
			return $this->idTitulacion;
		}



		public function modificarPortada($portada){
			$condicion = 'id_libro = '.$this->idLibro;

			$this->portada = $portada;

			actualizar('portada',$this->portada, $this->tablaSQL, $condicion);
		}

		public function modificarTitulo($titulo){
			$condicion = 'id_libro = '.$this->idLibro;

			$this->titulo = $titulo;

			actualizar('titulo',$this->titulo, $this->tablaSQL, $condicion);
		}

		public function modificarTituloOriginal($){
			$condicion = 'id_libro = '.$this->idLibro;

			$this->tituloOriginal = $tituloOriginal;

			actualizar('titulo_original',$this->tituloOriginal, $this->tablaSQL, $condicion);
		}

		public function modificarAutor($){
			$condicion = 'id_libro = '.$this->idLibro;

			$this->autor = $autor;

			actualizar('autor',$this->autor, $this->tablaSQL, $condicion);
		}

		public function modificarAno($){
			$condicion = 'id_libro = '.$this->idLibro;

			$this->ano = $ano;

			actualizar('ano',$this->ano, $this->tablaSQL, $condicion);
		}

		public function modificarIdAnadidoPor($){
			$condicion = 'id_libro = '.$this->idLibro;

			$this->idAnadidoPor = $idAnadidoPor;

			actualizar('id_anadido_por',$this->idAnadidoPor, $this->tablaSQL, $condicion);
		}

		public function modificarIdTitulacion($){
			$condicion = 'id_libro = '.$this->idLibro;

			$this->idTitulacion = $idTitulacion;

			actualizar('id_titulacion',$this->idTitulacion, $this->tablaSQL, $condicion);
		}

		public function toArray(){
			$libro = [
				'idLibro'			=> $this->idLibro,
				'portada'			=> $this->portada,
				'titulo' 			=> $this->titulo,
				'tituloOriginal' 	=> $this->tituloOriginal,
				'autor'				=> $this->autor,
				'ano'				=> $this->ano,
				'idAnadidoPor'		=> $this->idAnadidoPor,
				'idTitulacion'		=> $this->idTitulacion
			];

			return $libro;
		}
	}