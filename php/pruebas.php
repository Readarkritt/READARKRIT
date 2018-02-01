<?php
	require_once('./clases/Hash.php');
	require_once('./general/funciones.php');


	$hash = new Hash('Contraseña');
	echo $hash->get();

	/*$img = redimensionarImagen('../img/portadasLibros/1511359788574.jpg', 120,180);
	imagejpeg($img, '../img/portadasLibros/PRUEBA.jpg');
	//unlink("../img/portadasLibros/1511560885465.jpg");

	echo 'TARARA'*/
?>