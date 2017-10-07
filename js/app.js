var app = angular.module('readArkrit', ['ngRoute', 'datatables']);

app.config(['$routeProvider', '$locationProvider','$httpProvider', function ($routeProvider, $locationProvider,$httpProvider) {

    $locationProvider.html5Mode(true);

    /* LAS RUTAS DEL HTML, PARTEN DESDE DONDE ESTÁ menuApp.html */
    $httpProvider.defaults.headers.common['token'] = sessionStorage.getItem('tokenREADARKRIT');


    $routeProvider
        .when('/', {
            templateUrl: './html/estadistica/listarEstadistica.html'
        })
        .when('/estadisticas', {
            templateUrl: './html/estadistica/listarEstadistica.html'
        })
        /*.when('/estadistica', {
            templateUrl: '../estadistica/listarEstadistica.html'
        })*/

        // ALUMNO
        .when('/alumno/crear', {
            templateUrl: './html/libro/listarLibro.html',
            controller: 'altaAlumnoCtrl'
        })
        .when('/alumno/modificar', {
            templateUrl: './html/alumno/formAlumno.html',
            controller: 'modificarAlumnoCtrl'
        })

        //LIBRO
        .when('/libro', {
            templateUrl: './html/libro/listarLibro.html',
            controller: 'LibroCtrl'
        })
        .when('/libro/nuevo',{
            templateUrl: './html/libro/formLibro.html',
            controller:'altaLibroCtrl'
        })
        .when('/libro/modificar',{
            templateUrl: './html/libro/formLibro.html',
            controller:'modificarLibroCtrl'
        })

        //PROFESOR
        .when('/profesor', {
            templateUrl: './html/profesor/listarProfesor.html',
            controller: 'profesorCtrl'
        })
        .when('/profesor/modificar', {
            templateUrl: './html/profesor/formProfesor.html',
            controller: 'modificarProfesorCtrl'
        })
        .when('/profesor/terminar',{
            templateUrl: './html/profesor/formProfesor.html',
            controller: 'terminarProfesorCtrl'
        })


        .otherwise({
            redirectTo: '/estadisticas'
        });

}]);