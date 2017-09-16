// Crea una petición AJAX

function peticionAJAX(phpUrl, parameterObject, async){

	if (typeof async === 'undefined') { 
		async = true; 
	}
                //alert(sessionStorage.getItem('token'));
	if(sessionStorage.getItem('token') != null){
		parameterObject.token = sessionStorage.getItem('token');
	}

    return $.ajax({
        url: phpUrl,
        data: parameterObject,
        async: async,
        method: 'POST',
        dataType: 'json',
        success:function(data, textStatus, jqXHR){
            console.log("success");
        }
    });
}

// Crea un Avatar (simula el de Google) mediante la inicial del nombre pasado por parámetro

function crearAvatar(nombre){

	// Librería: <script src="../../js/librerias/initial.min.js"></script>

	$('.img-avatar').initial({
	 	name: nombre,
	 	height: 20,
	 	width: 20,
	 	fontSize: 16
	});
}

// Pasa los datos de un formulario a un objeto JS

function formTOobject(){

	var obj = {};
	var arr = [];

	// Paso los datos del formulario a un array (lee el valor del atributo NAME)
	arr = $('form').serializeArray();

	// Transformo ese array en un objeto
	$.each(arr, function(index, element){

	    obj[element['name']] = element['value'];
	});

	return obj;
}

// Comprueba si ya existe un valor en la bbdd

function existeRegistro(tablaSQL, campoSQL, valor){

	var phpUrl     = '';
	var parametros = {};
	var peticion   = {};
	var respuesta  = false;

	tablaSQL = tablaSQL.toLowerCase();

	phpUrl = '../../php/' + tablaSQL + '.php'; // url del controlador

	parametros.opcion = tablaSQL;
    parametros.accion = 'existe';
    parametros.campo  = campoSQL;
    parametros.valor  = valor;


	peticion = peticionAJAX(phpUrl, parametros, false);

    peticion.done(function( data, textStatus, jqXHR ) {

        respuesta = data.existe;
    });


	return respuesta;

}

// Marca el menú basándose en la URL actual

function marcarMenu(){
	
	var actual = (window.location.href).split("/")[4];

	$("li.active").removeClass('active');
	$("#"+actual).addClass('active');
}

// Carga el menú asociado al rol del usuario

function cargarMenu(){
	var parametros = {};
    var respuesta = peticionAJAX('php/cargarMenu.php', parametros);

        respuesta.done(function( data, textStatus, jqXHR ) {
        	if(data.error){
        		window.location = "/";
        	} else{
        		if(data.menu != ''){
        			$('#navBarGeneral').addClass('navBarDivisor');
        			$('#navBarParticular').append(data.menu);
        		}
        	}
        });

}

// Al dar sobre el aspa de la alerta, hará que se cierre

function cerrarAlerta(elemento){

	$(elemento).closest('div.alert').addClass('hidden');
}