
class Usuario {


    constructor(idUsuario, nombre, primerApellido, segundoApellido, fNacimiento, correo, nombreUsuario, contrasena, bloqueado) {

		this.idUsuario 	     = idUsuario;
		this.nombre 		 = nombre;
		this.primerApellido  = primerApellido;
		this.segundoApellido = segundoApellido;
		this.fNacimiento	 = fNacimiento;
		this.correo 		 = correo;
		this.nombreUsuario   = nombreUsuario;
		this.contrasena	     = contrasena;
		this.bloqueado       = bloqueado;
	}



    saludar(nombre) {

        alert(nombre);
    }  

}