<?php

	require_once("Usuario.php");

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
			$this->idUsuario 	 	  = $resultado['id_usuario'];
			$this->esAdmin 			  = $resultado['es_admin'];
			$this->evitarNotificacion = $resultado['evitar_notificacion'];


			// Cargamos la parte de Usuario
			//parent::cargar( $this->idUsuario );
			$this->usuario->cargar( $this->idUsuario );
	    }

	    public function invitar( $correo ){

	    	// 1) Se prepara el perfil para que el próximo inicio de sesión cambie el usuario los datos

	    	$camposUsuario = array();
			$camposProfesor = array();

			$camposUsuario['idUsuario'] 	  = '';
			$camposUsuario['nombre'] 		  = '';
			$camposUsuario['primerApellido']  = '';
			$camposUsuario['segundoApellido'] = '';
			$camposUsuario['fNacimiento']	  = '';
			$camposUsuario['correo'] 		  = $correo;
			$camposUsuario['nombreUsuario']   = '';
			$camposUsuario['contrasena']	  = generarContrasenaAleatoria();
			$camposUsuario['bloqueado']       = 0;

			$camposProfesor['idProfesor']    	  = '';
			$camposProfesor['idUsuario']          = '';
			$camposProfesor['esAdmin'] 			  = 0;
			$camposProfesor['evitarNotificacion'] = 0;

			$this->rellenar($camposUsuario, $camposProfesor);

			// 2) Se envía al correo que recibimos por parámetro un correo con la password con la que puede acceder

			$asunto   = 'READARKRIT - Alta profesor';

			$mensaje  = '¡Hola!\n\n';
			$mensaje .= 'Estás a un paso de ser un profesor de la comunidad READARKRIT.\n';
			$mensaje .= 'Sólo tienes que: \n';
			$mensaje .= '\r1) Entrar en la aplicación con tu correo y esta contrasena <b>' . $camposUsuario['contrasena'] . '</b>.\n';
			$mensaje .= '\r2) Terminar de completar la infomación de tu perfil.\n';
			$mensaje .= '\r3) Y cambiar la contraseña a alguna que no te duela la cabeza recordar.\n\n\n';
			$mensaje .= 'Saludos ;)';

			//mail($correo, $asunto, $mensaje);
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