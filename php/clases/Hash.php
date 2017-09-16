<?php

	require_once("./bbdd.php");

	class Hash{

		/* HASH es un algoritmo de cifra que no se puede volver atrás, es decir, se cifra pero no se puede descifrar.

		    Por eso, cuando los usuarios olviden la contraseña se mandará una aleatoria. */

		private const ALGORITMO_CIFRADO = PASSWORD_DEFAULT;
    	private const COST              = 12;
    	private $hash;


	    public function __construct( $textoClaro ) {

	        $this->generar( $textoClaro );
	    }

		
	    private function generar( $textoClaro ) {

	        $this->hash = password_hash($textoClaro, self::ALGORITMO_CIFRADO, ['cost' => self::COST]);
	    }


	    public static function esValido( $textoClaro, $passwordHasheada ){    	

	    	return password_verify($textoClaro, $passwordHasheada);
        }


        public static function mejorar( $textoClaro, $passwordHasheada, $idUsuario ) {

        	if( $textoClaro != '' && $passwordHasheada != '' && $idUsuario > 0 ){

        		if( password_needs_rehash($passwordHasheada, self::ALGORITMO_CIFRADO, ['cost' => self::COST]) ) {

	            	$this->generar( $textoClaro );

	            	actualizar( 'contrasena', $hash, 'usuario', 'id_usuario = ' . $idUsuario );
            	}
            }

	    }


        public function get(){

        	return $this->hash;
        }

    }

?>