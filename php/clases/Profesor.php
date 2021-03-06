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

	    public function esAdmin(){
	    	return $this->esAdmin;
	    }

	    public function evitarNotificacion(){
	    	return $this->evitarNotificacion;
	    }

	    public function cambiarEsAdmin($esAdmin){
	    	$condicion = 'id_profesor = ' . $this->idProfesor;

	    	$this->esAdmin = $esAdmin;

	    	actualizar( 'es_admin', $this->esAdmin, $this->tablaSQL, $condicion );
	    }

	    public function cambiarEvitarNotificacion($evitarNotificacion){
	    	$condicion = 'id_profesor = ' . $this->idProfesor;

	    	$this->evitarNotificacion = $evitarNotificacion;
	    	
	    	actualizar( 'evitar_notificacion', $this->evitarNotificacion, $this->tablaSQL, $condicion );
	    }

	    public function toArray(){

	    	if($this->evitarNotificacion == 1){
	    		$evitar = true;
	    	} else{
	    		$evitar = false;
	    	}
	    	$profesor = array(
				"idProfesor" => $this->idProfesor,
				'idUsuario' => $this->idUsuario,
				'esAdmin' => $this->esAdmin,
				'evitarNotificacion' => $evitar,
				'usuario' => $this->usuario->toArray()
			);
			return $profesor;
	    }
	    public function toSingleArray(){

	    	if($this->evitarNotificacion == 1){
	    		$evitar = true;
	    	} else{
	    		$evitar = false;
	    	}


	    	$profesor = array(
				"idProfesor" 			=> $this->idProfesor,
				'idUsuario' 			=> $this->idUsuario,
				'esAdmin' 				=> $this->esAdmin,
				'evitarNotificacion' 	=> $evitar,

	    		'nombre' 			=> $this->usuario->obtenerNombre(),
	    		'primerApellido' 	=> $this->usuario->obtenerPrimerApellido(),
	    		'segundoApellido' 	=> $this->usuario->obtenerSegundoApellido(),
	    		'fNacimiento' 		=> $this->usuario->obtenerFNacimiento(),
	    		'correo'			=> $this->usuario->obtenerCorreo(),
	    		'nombreUsuario' 	=> $this->usuario->obtenerNombreUsuario(),
	    		'bloqueado' 		=> $this->usuario->obtenerBloqueado(),
	    		'fBaja' 			=> $this->usuario->obtenerFBaja()

			);
			return $profesor;
	    }

    }

?>