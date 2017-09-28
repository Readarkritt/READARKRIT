
class Usuario {


    constructor(idUsuario, nombre, primerApellido, segundoApellido, fNacimiento, correo, nombreUsuario, contrasena, bloqueado, fBaja) {

		this.idUsuario 	     = idUsuario;
		this.nombre 		 = nombre;
		this.primerApellido  = primerApellido;
		this.segundoApellido = segundoApellido;
		this.fNacimiento	 = fNacimiento;
		this.correo 		 = correo;
		this.nombreUsuario   = nombreUsuario;
		this.contrasena	     = contrasena;
		this.bloqueado       = bloqueado;
		this.fBaja           = fBaja;
	}



    saludar(nombre) {

        alert(nombre);
    }  

}