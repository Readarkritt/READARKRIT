<?php

	require_once('./clases/Hash.php');
	$hash = new Hash('Contraseña');
	echo $hash->get();
	
?>