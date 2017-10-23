<?php

	require_once("Usuario.php");

	class Alumno /*extends Usuario*/{

		private $idAlumno      = 0;
		private $idUsuario 	   = 0;
		private $numExpediente = 0;
		private $idTitulacion  = 0;
		private $curso		   = 0;

		private $usuario;

		private $tablaSQL       = '';
		private $camposSQL      = '';
		

		public function __construct() {

			$this->tablaSQL  = 'alumno';
			$this->camposSQL = 'id_alumno, id_usuario, num_expediente, id_titulacion, curso';

			//parent::__construct();
			$this->usuario = new Usuario();
		}


		public function rellenar( $valoresUsuario, $valoresAlumno ) {

			//parent::rellenar($valoresUsuario); 
			$this->usuario->rellenar($valoresUsuario);

			//$valoresAlumno['idUsuario'] = parent::obtenerId();
			$valoresAlumno['idUsuario'] = $this->usuario->obtenerId();

			$this->idUsuario     = $valoresAlumno['idUsuario']; 	 
			$this->numExpediente = $valoresAlumno['numExpediente'];
			$this->idTitulacion	 = $valoresAlumno['idTitulacion'];
			$this->curso 		 = $valoresAlumno['curso'];

			$this->idAlumno = insertar( $this->camposSQL, $valoresAlumno, $this->tablaSQL );
	    }

	    public function cargar( $idAlumno ) {
	        
			$condicion = 'id_alumno = ' . $idAlumno;


			$resultado = consulta($this->camposSQL, $this->tablaSQL, $condicion);

			$this->idAlumno      = $idAlumno;
			$this->idUsuario 	 = $resultado['id_usuario'];
			$this->numExpediente = $resultado['num_expediente'];
			$this->idTitulacion  = $resultado['id_titulacion'];
			$this->curso		 = $resultado['curso'];


			// Cargamos la parte de Usuario
			//parent::cargar( $this->idUsuario );
			$this->usuario->cargar( $this->idUsuario );
	    }

	    public function obtenerId(){
	    	return $this->idAlumno;
	    }

	    public function obtenerIdUsuario(){
	    	return $this->idUsuario;
	    }

	    public function obtenerNumExpediente(){
	    	return $this->numExpediente;
	    }

	    public function obtenerIdTitulacion(){
	    	return $this->idTitulacion;
	    }

	    public function obtenerCurso(){
	    	return $this->curso;
	    }

	    public function obtenerUsuario(){
	    	return $this->usuario;
	    }

	    public function cambiarIdTitulacion( $idTitulacion ) {

	    	$condicion = 'id_usuario = ' . $this->idUsuario;

	    	$this->idTitulacion = $idTitulacion;

	    	actualizar( 'id_titulacion', $this->idTitulacion, $this->tablaSQL, $condicion );
	    }

	    public function cambiarCurso( $curso ) {

	    	$condicion = 'id_usuario = ' . $this->idUsuario;

	    	$this->curso = $curso;

	    	actualizar( 'curso', $this->curso, $this->tablaSQL, $condicion );
	    }

	    public function cambiarNumExpediente( $numExpediente ) {

	    	$condicion = 'id_usuario = ' . $this->idUsuario;

	    	$this->numExpediente = $numExpediente;

	    	actualizar( 'num_Expediente', $this->numExpediente, $this->tablaSQL, $condicion );
	    }

		public function toArray(){
			$alumno = array(
				'idAlumno' 		=> $this->idAlumno,
				'idUsuario' 	=> $this->idUsuario,
				'numExpediente'	=> $this->numExpediente,
				'idTitulacion' 	=> $this->idTitulacion,
				'curso' 		=> $this->curso,
				'usuario' 		=> $this->usuario->toArray()
			);
			
			return $alumno;
		}

    }

?>