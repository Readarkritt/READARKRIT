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


// Crea un Avatar en el elemento seleccionado (simula el de Google) mediante la inicial del nombre pasado por parámetro

function crearAvatarElemento(nombre,idElemento){
	alert(nombre);
	alert(idElemento);
	alert($('#'+idElemento)).html();
	$('#'+idElemento).initial({
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

function existeRegistro(campoSQL, valor, tablaSQL, conAngular){

	var phpUrl     = '';
	var parametros = {};
	var peticion   = {};
	var respuesta  = false;
	var opcionAr   = {};
	var opcion     = '';


	opcionAr = tablaSQL.split('_');
	opcion = opcionAr[0];

	for (var i = 1; i < opcionAr.length; i++) {
		opcionAr[i] = opcionAr[i].charAt(0).toUpperCase() + opcionAr[i].slice(1);
		opcion += opcionAr[i];
	}

	tablaSQL = tablaSQL.toLowerCase();

	if (typeof conAngular === 'undefined')
		phpUrl = './php/' + opcion + '.php'; // url del controlador
	else
		phpUrl = '../../php/' + opcion + '.php';


	parametros.opcion = opcion;
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
        		/*if(data.menu != ''){
        			$('#navBarGeneral').addClass('navBarDivisor');
        			$('#navBarParticular').append(data.menu);
        		}*/
        		if(data.menu != ''){
        			//$('#navBarGeneral').addClass('navBarDivisor');
        			$('#navBarGeneral').append(data.menu);
        		}

        		if(data.nombre != ''){
        			$scope.nombreUsuario = data.nombre;
                    $scope.$apply();
        			crearAvatar(data.nombre);
        		}
        	}
        	 $(".sidebar-wrapper > .nav > li").click(function(e){

        // 1) Quitamos la clase 'active' al elemento que esté marcado
        // 2) Y se la ponemos al elemento en el que se ha hecho click

        $("li.active").removeClass('active');

        $(this).addClass('active');
      });
        });
}

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

// comprueba que la fecha enviada por parámetro (string con formato dd/mm/yyyy) sea menor o igual al día en el que se ejecuta
function fechaMenorQueActual(fecha){
	var menor = false;
	var fechaActual = new Date();

	fecha = fecha.split("/");
	fecha = fecha[1]+"/"+fecha[0]+"/"+fecha[2];
	fecha = new Date(fecha);

	if(fecha<=fechaActual){
		menor = true;
	}

	return menor;
}

// convierte un valor smallInt a SI/NO

function smallintTOsino(dato){

	if( dato == 1 )
		return 'SI';
	else
		return 'NO';
}


// busca en un array de objetos, el valor de la propiedad indicada y devuelve el índice en el que se encuentra

function buscarValorEnArrObj(arrObj, propiedad, valor){

	var encontrado = -1;
	var i 		   = 0;

    for (i=0; i < arrObj.length && encontrado == -1; i++)
    	if( arrObj[i][propiedad] == valor )
    		encontrado = i;

    return encontrado;
}

function fechaActual(formato){

	var f = new Date();

	if( formato == 'dd/mm/yyyy' )
		return f.getDate() + '/' + (f.getMonth() +1) + '/' + f.getFullYear();
	else if( formato == 'dd-mm-yyyy' )
		return f.getDate() + '-' + (f.getMonth() +1) + '-' + f.getFullYear();
	else // yyyy-mm-dd
		return f.getFullYear() + '-' + (f.getMonth() +1) + '-' + f.getDate();
}