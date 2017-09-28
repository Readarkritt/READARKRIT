<?php

	class Usuario{

		private $idUsuario 		 = 0;
		private $nombre 		 = '';
		private $primerApellido  = '';
		private $segundoApellido = '';
		private $fNacimiento	 = '';
		private $correo 		 = '';
		private $nombreUsuario 	 = '';
		private $contrasena		 = '';
		private $bloqueado       = 0;
		private $fBaja           = null;

		private $tablaSQL        = '';
		private $camposSQL       = '';

		public function __construct() {

			$this->tablaSQL  = 'usuario';
			$this->camposSQL = 'id_usuario, nombre, primer_apellido, segundo_apellido, f_nacimiento, correo, nombre_usuario, contrasena, bloqueado, f_baja';
		}


		public function rellenar( $arrAsocValores ) {

			$hash = new Hash($arrAsocValores['contrasena']);
			$arrAsocValores['contrasena'] = $hash->get();			

			$this->nombre 		   = $arrAsocValores['nombre'];
			$this->primerApellido  = $arrAsocValores['primerApellido'];
			$this->segundoApellido = $arrAsocValores['segundoApellido'];
			$this->fNacimiento	   = $arrAsocValores['fNacimiento'];
			$this->correo 		   = $arrAsocValores['correo'];
			$this->nombreUsuario   = $arrAsocValores['nombreUsuario'];
			$this->contrasena      = $arrAsocValores['contrasena'];

			$this->idUsuario = insertar( $this->camposSQL, $arrAsocValores, $this->tablaSQL );
	    }

	    public function cargar( $idUsuario ) {
	        
			$condicion = 'id_usuario = ' . $idUsuario;


			$resultado = consulta($this->camposSQL, $this->tablaSQL, $condicion);


			$this->idUsuario       = $idUsuario;
			$this->nombre 		   = $resultado['nombre'];
			$this->primerApellido  = $resultado['primer_apellido'];
			$this->segundoApellido = $resultado['segundo_apellido'];
			$this->fNacimiento	   = formatearFecha($resultado['f_nacimiento'], 'objeto');
			$this->correo 		   = $resultado['correo'];
			$this->nombreUsuario   = $resultado['nombre_usuario'];
			$this->contrasena	   = $resultado['contrasena'];
			$this->bloqueado       = $resultado['bloqueado'];
			$this->fBaja           = $resultado['f_baja'];
	    }

	    public function eliminar(){

	    	$condicion = 'id_usuario = ' . $this->idUsuario;

			return borrar( $this->tablaSQL, $condicion );
	    }

	    public function obtenerId(){
	    	return $this->idUsuario;
	    }

	    public function obtenerNombre(){
	    	return $this->nombre;
	    }

	    public function obtenerPrimerApellido(){
	    	return $this->primerApellido;
	    }

	    public function obtenerSegundoApellido(){
	    	return $this->segundoApellido;
	    }

	    public function obtenerFNacimiento(){
	    	return $this->fNacimiento;
	    }

	    public function obtenerCorreo(){
	    	return $this->correo;
	    }

	    public function obtenerNombreUsuario(){
	    	return $this->nombreUsuario;
	    }

	    public function obtenerContrasena(){
	    	return $this->contrasena;
	    }

	    public function estaBloqueado(){
	    	return $this->bloqueado;
	    }

	    public function cambiarContrasena( $hash ) {

	    	$condicion = 'id_usuario = ' . $this->idUsuario;

	    	$this->contrasena = $hash;

	    	actualizar( 'contrasena', $this->contrasena, $this->tablaSQL, $condicion );
	    }

	    public function bloquear() {

	    	$this->bloqueado = 1;

	    	$condicion = 'id_usuario = ' . $this->idUsuario;

	    	actualizar( 'bloqueado', $this->bloqueado, $this->tablaSQL, $condicion );
	    }

    }

?>