// Crea una petición AJAX

function peticionAJAX(phpUrl, parameterObject, async){
	if (typeof async === 'undefined') { 
		async = true; 
	}
                //alert(sessionStorage.getItem('token'));

	if(sessionStorage.getItem('tokenREADARKRIT') != null){
		parameterObject.token = sessionStorage.getItem('tokenREADARKRIT');
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

function existeRegistro(campoSQL, valor, tablaSQL){

	var phpUrl     = '';
	var parametros = {};
	var peticion   = {};
	var respuesta  = false;

	tablaSQL = tablaSQL.toLowerCase();

	phpUrl = './php/' + tablaSQL + '.php'; // url del controlador

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

// Al dar sobre el aspa de la alerta, hará que se cierre

function cargarMenu($scope){

	var parametros = {};
    var respuesta = peticionAJAX('php/general/cargarMenu.php', parametros);

        respuesta.done(function( data, textStatus, jqXHR ) {
        	if(data.error){
        		sessionStorage.removeItem('tokenREADARKRIT');
       			window.location.replace('./');
        	} else{
        		if(data.menu != ''){
        			$('#navBarGeneral').addClass('navBarDivisor');
        			$('#navBarParticular').append(data.menu);
        		}

        		if(data.nombre != ''){
        			$scope.nombreUsuario = data.nombre;
        			crearAvatar(data.nombre);
        		}
        	}
        });
<<<<<<< HEAD
}

=======

    }

    
>>>>>>> d607fb5721d315e6f2adc54d7d41c590af82e33f
function cerrarAlerta(elemento){

	$(elemento).closest('div.alert').addClass('hidden');
}

// Carga un JS y comprueba que anteriormente no se ha insertado, para después usarlo

function cargarJS(rutaFichero) {

	var fichero       = rutaFichero.split("/").slice(-1)[0];
	var scriptCargado = false;


	$('script[src]').each(function() {

	  	if( $(this).attr('src').split('/').slice(-1)[0] == fichero )
	  		scriptCargado = true;
	});
    

	if( !scriptCargado ){

		var jsElm = document.createElement("script");
    
	    jsElm.type = "application/javascript";
	    jsElm.src  = rutaFichero;

	    document.body.appendChild(jsElm);
	}
}


function emailCorrecto(email) {

    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    return re.test(email);
}

function contrasenaSegura(contrasena) {

	var re = (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/);

	/*
		(/^
		(?=.*\d)                // 1 dígito
		(?=.*[a-z])             // 1 minúscula
		(?=.*[A-Z])             // 1 mayúscula
		[a-zA-Z0-9]{8,}         // debe tener como mínimo 8 caracteres de longitud
		$/)
	*/

	return re.test(contrasena);
}

// comprueba si una fecha con formato dd/mm/aaaa existe en el calendario

function fechaPermitida(fecha){

	var currVal = fecha;

    if(currVal == '')
        return false;
    
    var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
    var dtArray = currVal.match(rxDatePattern); // is format OK?
    
    if (dtArray == null) 
        return false;
    
    //Checks for dd/mm/yyyy format.
    dtDay   = dtArray[1];
    dtMonth = dtArray[3];
    dtYear  = dtArray[5];        
    
    if (dtDay < 1 || dtDay> 31)
        return false;
    else if (dtMonth < 1 || dtMonth > 12)
        return false;
    else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) 
        return false;
    else if (dtMonth == 2) 
    {
        var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
        if (dtDay> 29 || (dtDay ==29 && !isleap)) 
                return false;
    }

    return true;
}

// convierte un valor smallInt a SI/NO

function smallintTOsino(dato){

	if( dato == 1 )
		return 'SI';
	else
		return 'NO';
}