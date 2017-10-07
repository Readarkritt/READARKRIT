// Obtiene los campos de una tabla SQL - realizado para cargar <select></select>

class Titulacion{

    constructor() {

        this.idAlumno      = idAlumno;
        this.idUsuario     = idUsuario;
        this.numExpediente = numExpediente;
        this.idTitulacion  = idTitulacion;
        this.curso         = curso; 
    }
}

function obtenerValores(camposSQL, tablaSQL){

	var phpUrl     = '';
	var parametros = {};
	var peticion   = {};
	var respuesta  = false;

	tablaSQL = tablaSQL.toLowerCase();

	phpUrl = './php/' + tablaSQL + '.php'; // url del controlador

	parametros.opcion = tablaSQL;
    parametros.accion = 'listar';
    parametros.campos = camposSQL;


	peticion = peticionAJAX(phpUrl, parametros);

    peticion.done(function( data, textStatus, jqXHR ) {

    	if( !data.error ){
    		
        	respuesta = data.valores;
    	}
        else
        	respuesta = false;
    });

	return respuesta;
}