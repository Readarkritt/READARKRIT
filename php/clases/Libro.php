<?php

	class Libro{
		private $idLibro 			= 0;
		private $portada 			= '';
		private $titulo 			= '';
		private $tituloOriginal 	= '';
		private $autor				= '';
		private $ano				= 0;
		private $anadidoPor		    = 0;
		private $idTitulacion		= 0;
		private $fBaja 				= null;

		private $tablaSQL			= '';
		private $camposSQL			= '';

		public function __construct(){

			$this->tablaSQL = 'libro';
			$this->camposSQL = 'id_libro, portada, titulo, titulo_original, autor, ano, anadido_por, id_titulacion, f_baja';
		}

		public function rellenar($arrAsocValores){

			$this->portada 			= $arrAsocValores['portada'];
			$this->titulo 			= $arrAsocValores['titulo'];
			$this->tituloOriginal 	= $arrAsocValores['tituloOriginal'];
			$this->autor 			= $arrAsocValores['autor'];
			$this->ano 				= $arrAsocValores['ano'];
			$this->anadidoPor		= $arrAsocValores['anadidoPor'];
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
			$this->anadidoPor		= $resultado['anadido_por'];
			$this->idTitulacion 	= $resultado['id_titulacion'];
			$this->fBaja            = $resultado['f_baja'];
		}

		public function eliminar(){
			// Se añade fecha de baja

	    	$fechaActual = date("Y") . "-" . date("m") . "-" . date("d");
	    	$condicion   = 'id_libro = ' . $this->idLibro;

	    	return actualizar( 'f_baja', $fechaActual, $this->tablaSQL, $condicion );
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

		public function obteneranadidoPor(){
			return $this->anadidoPor;
		}

		public function obtenerIdTitulacion(){
			return $this->idTitulacion;
		}



		public function cambiarPortada($portada){
			$condicion = 'id_libro = '.$this->idLibro;

			$this->portada = $portada;

			actualizar('portada',$this->portada, $this->tablaSQL, $condicion);
		}

		public function cambiarTitulo($titulo){
			$condicion = 'id_libro = '.$this->idLibro;

			$this->titulo = $titulo;

			actualizar('titulo',$this->titulo, $this->tablaSQL, $condicion);
		}

		public function cambiarTituloOriginal($tituloOriginal){
			$condicion = 'id_libro = '.$this->idLibro;

			$this->tituloOriginal = $tituloOriginal;

			actualizar('titulo_original',$this->tituloOriginal, $this->tablaSQL, $condicion);
		}

		public function cambiarAutor($autor){
			$condicion = 'id_libro = '.$this->idLibro;

			$this->autor = $autor;

			actualizar('autor',$this->autor, $this->tablaSQL, $condicion);
		}

		public function cambiarAno($ano){
			$condicion = 'id_libro = '.$this->idLibro;

			$this->ano = $ano;

			actualizar('ano',$this->ano, $this->tablaSQL, $condicion);
		}

		public function cambiarAnadidoPor($anadidoPor){

			$condicion = 'id_libro = '.$this->idLibro;

			$this->anadidoPor = $anadidoPor;

			actualizar('anadido_por',$this->anadidoPor, $this->tablaSQL, $condicion);
		}

		public function cambiarIdTitulacion($idTitulacion){
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
				'anadidoPor'		=> $this->anadidoPor,
				'idTitulacion'		=> $this->idTitulacion,
				'fBaja'				=> $this->fBaja
			];

			return $libro;
		}
	}