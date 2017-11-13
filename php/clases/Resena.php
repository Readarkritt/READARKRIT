<?php

	class Resena{
		private $idResena	= 0;
		private $nota		= 0;
		private $comentario = '';
		private $idLibro	= 0;
		private $idUsuario	= 0;
		private $fAlta		= '';

		private $tablaSQL	= '';
		private $camposSQL	= '';

		public function __construct(){
			$this->tablaSQL		= 'resena';
			$this->camposSQL	= 'id_resena, nota, comentario, id_libro, id_usuario, fecha_alta'; 
		}

		public function rellenar($valoresResena){

			$this->nota     	= $valoresResena['nota'];
			$this->comentario   = $valoresResena['comentario'];
			$this->idLibro     	= $valoresResena['idLibro'];
			$this->idUsuario    = $valoresResena['idUsuario'];
			$this->fAlta     	= $valoresResena['fAlta'];

			$this->idResena = insertar( $this->camposSQL, $valoresResena, $this->tablaSQL );
		}

		public function eliminar(){

		}

		public function obtenerId(){
			return $this->idResena;
		}

		public function obtenerNota(){
			return $this->nota;
		}

		public function obtenerComentario(){
			return $this->comentario;
		}

		public function obtenerIdLibro(){
			return $this->idLibro;
		}

		public function obtenerIdUsuario(){
			return $this->idUsuario;
		}

		public function obtenerFAlta(){
			return $this->fAlta;
		}

		public function toArray(){
			$resena = array(
				'idResena'	 => $this->idResena,
				'nota'		 => $this->nota,
				'comentario' => $this->comentario,
				'idLibro'	 => $this->idLibro,
				'idUsuario'	 => $this->idUsuario,
				'fAlta'		 => $this->fAlta
				);

			return $resena;
		}
	}