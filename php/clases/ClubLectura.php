<?php

	class ClubLectura{

		private $idClub 	  = 0;
		private $creadoPor 	  = '';
		private $nombre    	  = 0;
		private $fInicio 	  = '';
		private $fFin    	  = null;
		private $idTitulacion = 0;
		private $curso   	  = 0;

		private $tablaSQL     = '';
		private $camposSQL    = '';

		public function __construct() {

			$this->tablaSQL  = 'club_lectura';
			$this->camposSQL = 'id_club, creado_por, nombre, f_inicio, f_fin, id_titulacion, curso';
		}


		public function rellenar( $arrAsocValores ) {	

			$this->creadoPor    = $arrAsocValores['creadoPor'];
			$this->nombre  		= $arrAsocValores['nombre'];

			
			$this->fInicio      = generarFechaActual();
			$arrAsocValores['fInicio'] = formatearFecha( $this->fInicio, 'bbdd');


			$this->idTitulacion = $arrAsocValores['idTitulacion'];
			$this->curso 		= $arrAsocValores['curso'];			

			$this->idClub = insertar( $this->camposSQL, $arrAsocValores, $this->tablaSQL );
	    }


	    public function cargar( $idClub ) {
	        
			$condicion = 'id_club = ' . $idClub;

			$resultado = consulta($this->camposSQL, $this->tablaSQL, $condicion);

			$this->idClub 	  	= $idClub;
			$this->creadoPor 	= $resultado['creado_por'];
			$this->nombre    	= $resultado['nombre'];
			$this->fInicio 	  	= $resultado['f_inicio'];
			$this->fFin    	  	= $resultado['f_fin'];
			$this->idTitulacion = $resultado['id_titulacion'];
			$this->curso   	  	= $resultado['curso'];
	    }

	    public function obtenerId(){
	    	return $this->idClub;
	    }

	    public function cerrar(){
			// Se añade fecha de baja

	    	$fechaActual = formatearFecha( generarFechaActual(), 'bbdd' );
	    	$condicion   = 'id_club = ' . $this->idClub;

	    	return actualizar( 'f_fin', $fechaActual, $this->tablaSQL, $condicion );
		}

    }

?>