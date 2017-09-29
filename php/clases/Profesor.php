<?php

	require_once(dirname(__FILE__)."/Usuario.php");

	class Profesor{

		private $idProfesor    		= 0;
		private $idUsuario 	   		= 0;
		private $esAdmin 			= 0;
		private $evitarNotificacion = 0;

		private $usuario;

		private $tablaSQL       = '';
		private $camposSQL      = '';
		

		public function __construct() {

			$this->tablaSQL  = 'profesor';
			$this->camposSQL = 'id_profesor, id_usuario, es_admin, evitar_notificacion';

			//parent::__construct();
			$this->usuario = new Usuario();
		}

		public function rellenar( $valoresUsuario, $valoresProfesor ) {

			//parent::rellenar($valoresUsuario); 
			$this->usuario->rellenar($valoresUsuario);

			//$valoresAlumno['idUsuario'] = parent::obtenerId();
			$valoresProfesor['idUsuario'] = $this->usuario->obtenerId();

			$this->idUsuario          = $valoresProfesor['idUsuario']; 	 
			$this->esAdmin 			  = $valoresProfesor['esAdmin'];
			$this->evitarNotificacion = $valoresProfesor['evitarNotificacion'];

			$this->idProfesor = insertar( $this->camposSQL, $valoresProfesor, $this->tablaSQL );
	    }

	    public function cargar( $idProfesor ) {
	        
			$condicion = 'id_profesor = ' . $idProfesor;


			$resultado = consulta($this->camposSQL, $this->tablaSQL, $condicion);

			$this->idProfesor         = $idProfesor;
			$this->idUsuario 	 	  = (int) $resultado['id_usuario'];
			$this->esAdmin 			  = $resultado['es_admin'];
			$this->evitarNotificacion = $resultado['evitar_notificacion'];


			// Cargamos la parte de Usuario
			//parent::cargar( $this->idUsuario );
			$this->usuario->cargar( $this->idUsuario );
	    }

	    public function eliminar() {

	        return $this->usuario->eliminar();
	    }

	    public function obtenerId(){
	    	return $this->idProfesor;
	    }

	    public function obtenerIdUsuario(){
	    	return $this->idUsuario;
	    }

	    public function obtenerUsuario(){
	    	return $this->usuario;
	    }

	    public function toArray(){
	    	$profesor = array(
				"idProfesor" => $this->idProfesor,
				'idUsuario' => $this->idUsuario,
				'esAdmin' => $this->esAdmin,
				'evitarNotificacion' => $this->evitarNotificacion,
				'usuario' => $this->usuario->toArray()
			);
			return $profesor;
	    }

    }

?>